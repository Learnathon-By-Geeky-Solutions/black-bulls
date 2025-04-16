import React from 'react';
import { useLocation } from 'react-router-dom';
import styles from './PaymentSuccess.module.css';

const PaymentSuccess = () => {
    const location = useLocation();
    const transactionId = new URLSearchParams(location.search).get('transaction_id');

    return (
        <div className={styles.successContainer}>
            <h1>Payment Successful!</h1>
            {transactionId && (
                <p>Your transaction ID is: <strong>{transactionId}</strong></p>
            )}
            <p>Thank you for your purchase. You can now access your course.</p>
        </div>
    );
};

export default PaymentSuccess;