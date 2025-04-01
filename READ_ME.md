# Task Manager Application

## 📌 Overview

The **Task Manager Application** is a web-based tool that allows users to **create, update, delete, and manage tasks**.  
Users can also **share completed tasks** on social media platforms like **Facebook** and **Google My Business**.  
The application includes **user authentication** and integrates with third-party APIs for seamless task sharing.

---

## 🚀 Key Features

### 🔐 User Authentication

- Default login credentials:
  - **Username:** `admin`
  - **Password:** `admin123`

### ✅ Task Management

- **Create, update, and delete tasks.**
- **Mark tasks as "Completed."**

### 📲 Social Media Sharing

- **Share completed tasks on Facebook.**
- **Share completed tasks on Google My Business** _(requires OAuth 2.0 setup)._

### 🛠 Admin Dashboard

- A centralized dashboard to **view and manage all tasks.**

---

## 📥 Installation

### Step 1: Clone the Repository

Clone the project repository to your local machine:

```bash
git clone https://github.com/your-username/task-manager.git
cd task-manager

rename config/config_example.php file to config.php and update the file

run php -S localhost:8000 -t

🎯 Usage
1️⃣ Login
Access the application at:
👉 http://localhost:8000/views/login.php

Use the default admin credentials:

Username: admin

Password: admin123

2️⃣ Manage Tasks
Navigate to the Dashboard to view all tasks.

Use the "Create Task" button to add new tasks.

Click "Edit" to update a task’s details.

Mark a task as "Completed" to enable sharing.

3️⃣ Share Tasks
After marking a task as completed, click the "Share" button.

Select the platform:

-> Facebook / Google My Business
```
