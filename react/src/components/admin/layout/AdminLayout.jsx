import React, { useState, useEffect } from 'react';
import { Outlet } from 'react-router-dom';
import AdminSidebar from './AdminSidebar';
import AdminHeader from './AdminHeader';
import styles from './AdminLayout.module.css';

const AdminLayout = () => {
    const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(
        JSON.parse(localStorage.getItem('isSidebarCollapsed')) || false
    );

    useEffect(() => {
        localStorage.setItem('isSidebarCollapsed', JSON.stringify(isSidebarCollapsed));
    }, [isSidebarCollapsed]);

    return (
        <div className={styles.layout}>
            <div className={styles.sidebarWrapper}>
                <AdminSidebar isCollapsed={isSidebarCollapsed} />
            </div>
            <div className={styles.mainWrapper}>
                <AdminHeader onToggleSidebar={() => setIsSidebarCollapsed(!isSidebarCollapsed)} />
                <div className={styles.contentWrapper}>
                    <Outlet />
                </div>
            </div>
        </div>
    );
};

export default AdminLayout;