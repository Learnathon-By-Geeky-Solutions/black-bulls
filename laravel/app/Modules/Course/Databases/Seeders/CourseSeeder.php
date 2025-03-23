<?php

namespace App\Modules\Course\Database\Seeders;

use App\Modules\Course\Models\Category;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categories = [
            [
                'name' => 'Programming',
                'description' => 'Programming related courses',
                'slug' => 'programming'
            ],
            [
                'name' => 'Design',
                'description' => 'Design related courses',
                'slug' => 'design'
            ],
            [
                'name' => 'Business',
                'description' => 'Business related courses',
                'slug' => 'business'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create courses
        $courses = [
            [
                'title' => 'Introduction to PHP',
                'description' => 'Learn the basics of PHP programming',
                'price' => 99.99,
                'duration' => '8 weeks',
                'level' => 'beginner',
                'status' => 'published',
                'category_id' => 1
            ],
            [
                'title' => 'Advanced Laravel Development',
                'description' => 'Master Laravel framework',
                'price' => 199.99,
                'duration' => '12 weeks',
                'level' => 'advanced',
                'status' => 'published',
                'category_id' => 1
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
} 