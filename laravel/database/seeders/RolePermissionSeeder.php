<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{

    public function run()
    {
        // Permissions for Admin (only admin specific unique permissions)
        $adminPermissions = [
            'create role',
            'view roles',
            'view role details',
            'update role',
            'delete role',
            'assign role to user',
            'remove role from user',
            'create permission',
            'view permissions',
            'view permission details',
            'update permission',
            'delete permission',
            'assign permission to role',
            'remove permission from role',
            'assign permission to user',
            'remove permission from user',
            'sync permissions to role',

            'process payment',
            'view payment details',
            'create coupon',
            'view all coupons',
            'apply coupon to purchase',

            'view admin dashboard',
            'view admin reports for users',
            'view admin reports for courses',
            'view admin reports for payments',
        ];

        // Permissions for Instructor
        $instructorPermissions = [
            'create course',
            'view courses',
            'view course details',
            'update course',
            'delete course',
            'add lessons to course',
            'view lessons of course',
            'view lesson details',
            'add assignments to course',
            'view assignments of course',
            'upload video to lesson',
            'view video of lesson',
            'delete video from lesson',
            'upload material to lesson',
            'view material of lesson',
            'delete material from lesson',

            'enroll in course',
            'save course progress',
            'generate certificate of completion',
            'schedule live session',
            'view live session details',
            'submit review for course',
            'view reviews of course',
            'update review for course',
            'delete review for course',

            'view instructor dashboard',
            'view instructor courses',
            'view students of instructor course',
            'process refunds for instructor course',
        ];

        // Permissions for Student
        $studentPermissions = [
            'view courses',
            'enroll in course',
            'save course progress',
            'view user progress',
            'generate certificate of completion',
            'schedule live session',
            'view live session details',
            'query AI chatbot',
            'generate MCQs with AI',
            'get course recommendations with AI',
            'submit review for course',
            'view reviews of course',
            'add course to wishlist',
            'view user wishlist',
            'view user notifications',
            'view enrolled courses for user',
            'mark course as completed',
            'view payment details',
            'view payment history for user',
            'join live session',
            'view user progress for all courses',
            'view user progress for specific course',
            'mark course completion',
        ];

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $instructor = Role::firstOrCreate(['name' => 'instructor']);
        $student = Role::firstOrCreate(['name' => 'student']);

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($instructorPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($studentPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin->givePermissionTo(Permission::all());
        $instructor->givePermissionTo($instructorPermissions);
        $student->givePermissionTo($studentPermissions);
    }
}
