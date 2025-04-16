<?php
namespace App\Modules\Payment\Services;
use App\Modules\Payment\Services\SslCommerzNotification;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
class SslCommerzPaymentService
{
    private const TRANSACTION_SUCCESSFUL_MESSAGE = 'Transaction is already Successful';
    protected $sslCommerz;
    public function __construct(SslCommerzNotification $sslCommerz)
    {
        $this->sslCommerz = $sslCommerz;
    }
    public function initiatePayment(array $data, string $type = 'hosted'): array
    {
        try {
            $data['user_id'] = JWTAuth::user()->id;
            $post_data = $this->preparePaymentData($data);
            // Save order as pending
            $this->saveOrder($post_data);
            // Initiate payment
            $payment_options = $this->sslCommerz->makePayment($post_data, $type);
            if (!is_array($payment_options)) {
                return $this->generateResponse(false, 'Payment initiation failed', null, 400);
            }
            return $this->generateResponse(true, 'Payment initiated successfully', $payment_options);
        } catch (\Exception $e) {
            return $this->generateResponse(false, 'Payment initiation failed: ' . $e->getMessage(), null, 500);
        }
    }
    public function handleSuccess($request): array
    {
        try {
            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $currency = $request->input('currency');
            $order = $this->getOrder($tran_id);
            if ($order->status == 'Pending') {
                $validation = $this->sslCommerz->orderValidate($request->all(), $tran_id, $amount, $currency);
                if ($validation) {
                    $this->updateOrderStatus($tran_id, 'Processing');
                    
                    DB::table('course_enrollments')
                    ->updateOrInsert(
                        [
                            'course_id' => $order->course_id,
                            'user_id' => $order->user_id,
                        ], // Conditions to find the record
                        [
                            'order_id' => $order->id,
                            'price_paid' => $order->amount,
                            'status' => 'active',
                        ] // Values to update or insert
                    );
                    return $this->generateResponse(true, 'Transaction is successfully Completed', ['transaction_id' => $tran_id]);
                }
            }
            return $this->handleOrderStatus($order, $tran_id, 'Transaction is successfully Completed', 'Invalid Transaction');
        } catch (\Exception $e) {
            return $this->generateResponse(false, 'Transaction validation failed: ' . $e->getMessage(), null, 500);
        }
    }
    public function handleFailure($tran_id): array
    {
        try {
            $order = $this->getOrder($tran_id);
            
            if ($order->status == 'Pending') {
                $this->updateOrderStatus($tran_id, 'Failed');
                return $this->generateResponse(false, 'Transaction is Failed', ['transaction_id' => $tran_id], 400);
            }
            return $this->handleOrderStatus($order, $tran_id, self::TRANSACTION_SUCCESSFUL_MESSAGE, 'Transaction is Invalid');
        } catch (\Exception $e) {
            return $this->generateResponse(false, 'Transaction failure handling failed: ' . $e->getMessage(), null, 500);
        }
    }
    public function handleCancel($tran_id): array
    {
        try {
            $order = $this->getOrder($tran_id);
            
            if ($order->status == 'Pending') {
                $this->updateOrderStatus($tran_id, 'Canceled');
                return $this->generateResponse(false, 'Transaction is Canceled', ['transaction_id' => $tran_id], 400);
            }
            return $this->handleOrderStatus($order, $tran_id, self::TRANSACTION_SUCCESSFUL_MESSAGE, 'Transaction is Invalid');
        } catch (\Exception $e) {
            return $this->generateResponse(false, 'Transaction cancellation handling failed: ' . $e->getMessage(), null, 500);
        }
    }
    public function handleIpn($request): array
    {
        try {
            $tran_id = $request->input('tran_id');
            $amount = $request->input('amount');
            $currency = $request->input('currency');
            $order = $this->getOrder($tran_id);
            if ($order->status == 'Pending') {
                $validation = $this->sslCommerz->orderValidate($request->all(), $tran_id, $amount, $currency);
                if ($validation) {
                    $this->updateOrderStatus($tran_id, 'Processing');
                    return $this->generateResponse(true, 'IPN processed successfully', ['transaction_id' => $tran_id]);
                }
            }
            return $this->generateResponse(true, 'IPN already processed', ['transaction_id' => $tran_id]);
        } catch (\Exception $e) {
            return $this->generateResponse(false, 'IPN processing failed: ' . $e->getMessage(), null, 500);
        }
    }
    protected function preparePaymentData(array $data): array
    {
        $course = DB::table('courses')->where('id', $data['course_id'])->first();
        return [
            'tran_id' => uniqid(),
            'total_amount' => $course->price,
            'course_id' => $data['course_id'],
            'user_id' => $data['user_id'],
            'currency' => "BDT",
            'cus_name' => $data['customer_name'],
            'cus_email' => $data['customer_email'],
            'cus_phone' => $data['customer_mobile'],
            'cus_add1' => $data['address'],
            'cus_add2' => "",
            'cus_city' => "",
            'cus_state' => $data['state'],
            'cus_postcode' => $data['zip'],
            'cus_country' => $data['country'],
            'cus_fax' => "",
            'ship_name' => $data['customer_name'],
            'ship_add1' => $data['address'],
            'ship_add2' => "",
            'ship_city' => "",
            'ship_state' => $data['state'],
            'ship_postcode' => $data['zip'],
            'ship_country' => $data['country'],
            'ship_phone' => $data['customer_mobile'],
            'shipping_method' => "NO",
            'product_name' => $course->title, // Use course title
            'product_category' => "Education",
            'product_profile' => "physical-goods",
            'value_a' => "ref001",
            'value_b' => "ref002",
            'value_c' => "ref003",
            'value_d' => "ref004"
        ];
    }
    protected function saveOrder(array $data)
    {
        DB::table('orders')
            ->updateOrInsert(
                ['transaction_id' => $data['tran_id']],
                [
                    'course_id' => $data['course_id'],
                    'user_id' => $data['user_id'],
                    'name' => $data['cus_name'],
                    'email' => $data['cus_email'],
                    'phone' => $data['cus_phone'],
                    'amount' => $data['total_amount'],
                    'status' => $data['total_amount'] == 0 ? 'Complete' : 'Pending',
                    'address' => $data['cus_add1'],
                    'transaction_id' => $data['tran_id'],
                    'currency' => $data['currency']
                ]
            );
        $order = DB::table('orders')->where('transaction_id', $data['tran_id'])->first();
        // If the total amount is 0, enroll the user in the course
        if ($data['total_amount'] == 0) {
            DB::table('course_enrollments')
                ->updateOrInsert(
                    [
                        'course_id' => $data['course_id'],
                        'user_id' => $data['user_id'],
                    ],
                    [
                        'order_id' => $order->id,
                        'price_paid' => $data['total_amount'],
                        'status' => 'active',
                    ]
                );
        }
    }
    protected function getOrder($tran_id)
    {
        return DB::table('orders')
            ->where('transaction_id', $tran_id)->first();
    }
    protected function updateOrderStatus($tran_id, $status): void
    {
        DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->update(['status' => $status]);
    }

    private function generateResponse(bool $isSuccess, string $message, array $data = null, int $status = 200): array
    {
        return [
            'is_success' => $isSuccess,
            'message' => $message,
            'data' => $data,
            'status' => $status
        ];
    }

    private function handleOrderStatus($order, string $tran_id, string $successMessage, string $failureMessage): array
    {
        if ($order->status == 'Pending') {
            $this->updateOrderStatus($tran_id, 'Processing');
            return $this->generateResponse(true, $successMessage, ['transaction_id' => $tran_id]);
        }

        if ($order->status == 'Processing' || $order->status == 'Complete') {
            return $this->generateResponse(true, self::TRANSACTION_SUCCESSFUL_MESSAGE, ['transaction_id' => $tran_id]);
        }

        return $this->generateResponse(false, $failureMessage, ['transaction_id' => $tran_id], 400);
    }
}
