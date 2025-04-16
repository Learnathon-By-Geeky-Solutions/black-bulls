import React, { useState, useEffect } from 'react';
import { usePayment } from '../../hooks/payment/usePayment';
import { useSelector } from 'react-redux';
import styles from './PaymentForm.module.css';

const PaymentForm = () => {
    const course = useSelector((state) => state.course.course); // Retrieve course details from Redux

    const { loading, error, initiatePayment } = usePayment();
    const [formData, setFormData] = useState({
        amount: course?.price || 0, // Use course price from Redux
        customer_name: '',
        customer_mobile: '',
        customer_email: '',
        address: '',
        country: 'Bangladesh',
        state: '',
        zip: ''
    });

    useEffect(() => {
        if (course) {
            setFormData((prev) => ({ ...prev, amount: course.price }));
        }
    }, [course]);

    useEffect(() => {
        console.log('Redux course state:', course); // Log Redux course state
    }, [course]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
    
        // Ensure course_id is included in the payload
        const payload = { ...formData, course_id: course?.id };
        console.log('Submitting payment with payload:', payload);
    
        const response = await initiatePayment(payload);

        if (!response) {
            console.error('No response received from payment API.');
            return;
        }
    
        if (response?.GatewayPageURL) {
            // Navigate to the payment gateway URL
            window.location.href = response.GatewayPageURL;
        } 
        else if (response?.is_success && response?.data?.redirect_url) {
            // Redirect to the provided URL
            window.location.href = response.data.redirect_url;
        }
        else {
            console.error('Failed to initiate payment:', response);
        }
    };

    return (
        <div className={styles.paymentForm}>
            <h4 className={styles.title}>Billing address</h4>
            <form onSubmit={handleSubmit} className={styles.form}>
                <div className={styles.formGroup}>
                    <label htmlFor="customer_name">Full name</label>
                    <input
                        type="text"
                        name="customer_name"
                        id="customer_name"
                        value={formData.customer_name}
                        onChange={handleChange}
                        required
                        placeholder="John Doe"
                    />
                </div>

                <div className={styles.formGroup}>
                    <label htmlFor="customer_mobile">Mobile</label>
                    <div className={styles.inputGroup}>
                        <span className={styles.inputPrefix}>+88</span>
                        <input
                            type="text"
                            name="customer_mobile"
                            id="customer_mobile"
                            value={formData.customer_mobile}
                            onChange={handleChange}
                            required
                            placeholder="01711xxxxxx"
                        />
                    </div>
                </div>

                <div className={styles.formGroup}>
                    <label htmlFor="customer_email">Email</label>
                    <input
                        type="email"
                        name="customer_email"
                        id="customer_email"
                        value={formData.customer_email}
                        onChange={handleChange}
                        required
                        placeholder="you@example.com"
                    />
                </div>

                <div className={styles.formGroup}>
                    <label htmlFor="address">Address</label>
                    <input
                        type="text"
                        name="address"
                        id="address"
                        value={formData.address}
                        onChange={handleChange}
                        required
                        placeholder="1234 Main St"
                    />
                </div>

                <div className={styles.formGroup}>
                    <label htmlFor="country">Country</label>
                    <input
                        type="text"
                        name="country"
                        id="country"
                        value={formData.country}
                        onChange={handleChange}
                        required
                        readOnly
                    />
                </div>

                <div className={styles.formGroup}>
                    <label htmlFor="state">State</label>
                    <input
                        type="text"
                        name="state"
                        id="state"
                        value={formData.state}
                        onChange={handleChange}
                        required
                        placeholder="Dhaka"
                    />
                </div>

                <div className={styles.formGroup}>
                    <label htmlFor="zip">Zip</label>
                    <input
                        type="text"
                        name="zip"
                        id="zip"
                        value={formData.zip}
                        onChange={handleChange}
                        required
                        placeholder="1200"
                    />
                </div>

                <div className={styles.checkboxGroup}>
                    <label htmlFor='same_address' className={styles.checkboxLabel}>Shipping address is the same as my billing address</label>
                    <input
                        type="checkbox"
                        name="same_address"
                        id="same_address"
                    />
                </div>

                <div className={styles.checkboxGroup}>
                    <label htmlFor='save_info' className={styles.checkboxLabel}>Save this information for next time</label>
                    <input
                        type="checkbox"
                        name="save_info"
                        id="save_info"
                    />
                </div>

                {error && <div className={styles.error}>{error}</div>}

                <button
                    type="submit"
                    className={styles.submitButton}
                    disabled={loading}
                >
                    {loading ? 'Processing...' : 'Pay Now'}
                </button>
            </form>
        </div>
    );
};

export default PaymentForm;