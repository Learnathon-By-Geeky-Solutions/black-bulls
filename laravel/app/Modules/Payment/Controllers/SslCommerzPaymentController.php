<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Requests\PaymentRequest;
use App\Modules\Payment\Services\SslCommerzPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class SslCommerzPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(SslCommerzPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function initiatePayment(PaymentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($validatedData['amount'] == 0) {
            $response = $this->handleFreeCourses($validatedData);
            return response()->json([
                'is_success' => $response['is_success'],
                'message' => $response['message'],
                'data' => $response['data']
            ], $response['status']);
        }

        $response = $this->paymentService->initiatePayment($validatedData);

        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function initiateAjaxPayment(PaymentRequest $request): JsonResponse
    {
        $response = $this->paymentService->initiatePayment($request->validated(), 'checkout');
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function handleSuccess(Request $request)
    {
        $response = $this->paymentService->handleSuccess($request);

        if ($response['is_success']) {
            // Redirect to React app's /payments/success route with transaction_id
            return redirect()->away(env('FRONTEND_URL', 'http://localhost:3000') . '/learn/my-courses');
        }

        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function handleFailure(Request $request): JsonResponse
    {
        $response = $this->paymentService->handleFailure($request->input('tran_id'));
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function handleCancel(Request $request): JsonResponse
    {
        $response = $this->paymentService->handleCancel($request->input('tran_id'));
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function ipn(Request $request): JsonResponse
    {
        $response = $this->paymentService->handleIpn($request);
        
        return response()->json([
            'is_success' => $response['is_success'],
            'message' => $response['message'],
            'data' => $response['data']
        ], $response['status']);
    }

    public function handleFreeCourses(array $data){

        DB::table('course_enrollments')
            ->updateOrInsert(
                [
                    'course_id' => $data['course_id'],
                    'user_id' => JWTAuth::user()->id,
                ],
                [
                    'price_paid' => $data['amount'],
                    'status' => 'active',
                ]
            );

        return [
            'is_success' => true,
            'message' => 'Transaction is successfully Completed',
            'data' => ['redirect_url' => env('FRONTEND_URL', 'http://localhost:3000') . '/learn/my-courses'],
            'status' => 200
        ];

    }
}

