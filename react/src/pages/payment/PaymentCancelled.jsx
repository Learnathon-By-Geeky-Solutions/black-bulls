import React from 'react';
import { Link } from 'react-router-dom';
import styles from './PaymentCancelled.module.css';

const PaymentCancelled = () => {
    return (
        <div className={styles.container}>
            <div className={styles.content}>
                <div className={styles.icon}>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z" />
                    </svg>
                </div>
                <h1 className={styles.title}>Payment Cancelled</h1>
                <p className={styles.message}>
                    Your payment has been cancelled. You can try again or return to the course page.
                </p>
                <div className={styles.actions}>
                    <Link to="/checkout" className={styles.button}>
                        Try Again
                    </Link>
                    <Link to="/courses" className={styles.buttonSecondary}>
                        Browse Courses
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default PaymentCancelled; 