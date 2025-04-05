# black-bulls

## Team Members
- salauddin-shanto (Team Leader)
- SamaulHossain
- Mehediism

## Mentor
- shadman-ahmed-bs23

## Project Description

# Project Outline for AI-Integrated Learning Management System
## Project Overview
The goal is to build an advanced Learning Management System similar to Udemy or Coursera, with AI-driven features to enhance user engagement, learning outcomes, and platform efficiency. The platform will provide users with courses that include video lessons, quizzes, certificates, and live sessions, while also integrating AI capabilities such as providing instant responses to lesson-related Q&A and MCQ creation.

## Core Features
## 1. User Management
User registration and login (students, instructors, admins).
Role-based access (student, instructor, admin privileges).
User profiles with detailed information, bio, and badges/achievements.
## 2. Course Management
    -Course creation by instructors with features like:
    -Adding courses, uploading videos, course thumbnails, and descriptions.
    -Adding chapters, lessons, and MCQs.
    -Adding lesson related tutorial(text-based)
    -Price settings.
    -Managing live sessions and discussion forums.
    -Support for multiple categories and tags for better course discovery.
    -Integration of discounts and coupons.
    -Ratings and reviews for courses.
## 3. Learning Features
Video lessons with interactive capabilities (pause, rewind, playback speed).
Chapter and lesson structure for courses.
MCQs and quizzes.
Live sessions for real-time interaction between instructors and students. 
Notes-taking facilities during the live session for learners.
User progress tracking.
Certificates of completion for courses.
## 4. Course enrollment
Payment gateway implementation.
Support for one-time course payments.
## 5. Support ticket(complain box)
Troubleshooting login or payment issues.
Complain for course, AI, or other services
## 6. Administration Features
Admin dashboard for platform management.
Instructor dashboard for Managing courses, users, live sessions, payments, and refunds.
## 7. AI Chatbot
Provide instant responses to lesson-related Q&A
MCQ Creation
Analyse topics and video transcript from the lesson
Generate quizzes with multiple-choice questions for the user.
AI suggests courses based on:
Learning patterns

# Technical Stack
1. Frontend
React.js 
Bootstrap or Tailwind CSS
2. Backend
Laravel 
3. Database
MySQL 
4. AI Tools
Llama 2 (by Meta) or LangChain.


# Future Enhancements
Mobile app development for Android and iOS.
Integration facilities with external LMS systems(by exposing API or browser extensions, or packages)
Social Features 
Discussion forums for courses.
Q&A sections for instructors and learners.
Wishlist and cart functionality for users to save or purchase courses.
AI Integration (advanced)
Transcript Generation:
Extract text from video tutorials.
Personalized Recommendations:
AI suggests courses based on:
Learning patterns.
Time spent on specific course categories.
User interests and ratings.
24x7 support for user questions.
Course-related help and guidance.
General platform navigation.
AI can also read the whiteboard notes to generate MCQ and provide suggestions.
Monetization 
Both one-time course-based payment and subscription plans for access to all or select courses.
Analytics and Reporting
Track user activity, time spent on courses, and progress.
Insights for instructors to improve course content.

## Conclusion
This project plan ensures the creation of a robust, scalable, and user-friendly learning platform with AI capabilities to stand out from existing competitors like Udemy and Coursera. With a structured development process, the platform can evolve to meet user needs and industry trends.



## Getting Started
1. Clone the repository
2. Install dependencies
3. Start development

## Development Guidelines
1. Create feature branches
2. Make small, focused commits
3. Write descriptive commit messages
4. Create pull requests for review

## API Development Guidelines
1. Implement a new API endpoint within the existing Laravel/app/Modules/Course/ module, adhering to the current project structure and coding standards:
First check related migrations to understand the database.
Routing: Define the required API routes within the Course module.
2. Model: Create required model if not exist
3. Controller: Create controller inside the Course module. The controller should only handle the request and return a consistent JSON response, following the response format used by other controllers.
4. Service: Implement the core business logic in a dedicated service class within the module. Use the constructor for dynamic binding of the Repository to the RepositoryInterface with the appropriate model instance.
5. Repository: Use the existing shared RepositoryInterface from Modules/Common/Contracts/RepositoryInterface(implemented by Modules/Common/Repository/Repository.php) for all database operations, ensuring proper data layer abstraction.
6. Ensure the implementation strictly follows the existing architectural pattern of controller → service → repository(common) and maintains consistency in response formatting across the application. Remember: don't create individual repository. use the existing shared RepositoryInterface.


## Resources
- [Project Documentation](docs/)
- [Development Setup](docs/setup.md)
- [Contributing Guidelines](CONTRIBUTING.md)
