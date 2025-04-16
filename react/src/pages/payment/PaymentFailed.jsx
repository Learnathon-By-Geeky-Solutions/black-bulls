import React from 'react';
import { Link } from 'react-router-dom';
import styles from './PaymentFailed.module.css';

const PaymentFailed = () => {
    return (
        <div className={styles.container}>
            <div className={styles.content}>
                <div className={styles.icon}>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                    </svg>
                </div>
                <h1 className={styles.title}>Payment Failed</h1>
                <p className={styles.message}>
                    We're sorry, but your payment could not be processed. Please try again or contact support if the problem persists.
                </p>
                <div className={styles.actions}>
                    <Link to="/checkout" className={styles.button}>
                        Try Again
                    </Link>
                    <Link to="/contact" className={styles.buttonSecondary}>
                        Contact Support
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default PaymentFailed; 