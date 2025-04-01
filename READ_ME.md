Task Manager Application
Overview
The Task Manager Application is a web-based tool that allows users to manage tasks (create, update, delete) and share completed tasks on social media platforms like Facebook and Google My Business. The application includes user authentication and integrates with third-party APIs for seamless sharing.

Key Features:
User Authentication :
Default login credentials: admin / admin123.
Task Management :
Create, update, and delete tasks.
Mark tasks as "Completed."
Social Media Sharing :
Share completed tasks on Facebook .
Share completed tasks on Google My Business (requires OAuth 2.0 setup).
Admin Dashboard :
A centralized dashboard to view and manage all tasks.
Prerequisites
To run this project locally, ensure you have the following installed:

PHP (>= 7.4)
Composer (for managing dependencies)
MySQL (or any database supported by PHP)
Web Server (e.g., Apache, Nginx, or PHP built-in server)
Google Cloud Account (for Google My Business API integration)
Facebook Developer Account (for Facebook API integration)
Installation
Step 1: Clone the Repository
Clone the project repository to your local machine:

php -S localhost:8000 -t
Usage

1. Login
   Access the application at http://localhost:8000/views/login.php.
   Use the default admin credentials:
   Username : admin
   Password : admin123
2. Manage Tasks
   Navigate to the Dashboard to view all tasks.
   Use the Create Task button to add new tasks.
   Click Edit to update a task’s details.
   Mark a task as Completed to enable sharing.
3. Share Tasks
   After marking a task as completed, click the Share button.
   Select the platform (Facebook or Google ) to share the task.
