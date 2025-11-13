# 🌐 TalentFlow – HR Orchestrator

**TalentFlow** is a modern Human Resource (HR) workflow management and orchestration system designed to streamline HR tasks such as employee onboarding, task tracking, and analytics — all in one unified platform.

## 🚀 Features

✅ **User Registration & Login**
- Secure authentication with password hashing (`password_hash` / `password_verify`)  
- Session-based access control  

✅ **Admin Dashboard**
- View and manage user data, tasks, and leave requests  
- Access analytics and performance metrics  

✅ **Task & Leave Management**
- Create, assign, and track HR tasks  
- Leave management with approval system  

✅ **Analytics Dashboard**
- Displays productivity, task stats, and user metrics  

✅ **Responsive UI**
- Built with **Bootstrap 5** for desktop and mobile optimization  

✅ **Security**
- Encrypted passwords  
- SQL Injection prevention using prepared statements  

---

## 🏗️ Tech Stack

| Layer | Technology |
|-------|-------------|
| Frontend | HTML, CSS, Bootstrap 5 |
| Backend | PHP 8+ |
| Database | MySQL (phpMyAdmin) |
| Server | XAMPP / Apache |
| Version Control | Git & GitHub |

---

## 📁 Project Structure

```

TALENTFLOW/
│
├── app/
│   ├── Controllers/
│   │   ├── FaqController.php
│   │   ├── InterviewController.php
│   │   ├── LeaveController.php
│   │   ├── OfferController.php
│   │   └── OnboardingController.php
│   │
│   ├── libs/
│   │   └── PHPMailer/
│   │       └── src/
│   │           ├── Exception.php
│   │           ├── PHPMailer.php
│   │           └── SMTP.php
│   │
│   ├── Models/
│   │   ├── Admin.php
│   │   ├── Artifact.php
│   │   ├── DB.php
│   │   ├── Leave.php
│   │   ├── Run.php
│   │   ├── Task.php
│   │   └── User.php
│   │
│   ├── Services/
│   │   ├── CalendarService.php
│   │   ├── DocsService.php
│   │   ├── FaqBrain
│   │   ├── HRISService.php
│   │   ├── IAMService.php
│   │   ├── ITSMService.php
│   │   ├── LeaveService.php
│   │   ├── MailService.php
│   │   ├── MessagingService.php
│   │   └── Orchestrator.php
│   │
│   ├── Views/
│   │   ├── 404.php
│   │   ├── dashboard.php
│   │   ├── faq.php
│   │   ├── interview_form.php
│   │   ├── layout.php
│   │   ├── leave_form.php
│   │   ├── leave_summary.php
│   │   ├── offer_form.php
│   │   ├── onboarding_form.php
│   │   └── helpers.php
│   │
│   ├── config/
│   │   ├── config.php
│   │   ├── db.php
│   │   ├── mail.php
│   │   └── routes.php
│   │
│   ├── database/
│   │   └── schema.sql
│   │
│   └── mock/
│       ├── calendar.php
│       ├── docs.php
│       ├── hris.php
│       ├── iam.php
│       ├── itsm.php
│       └── messaging.php
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── app.css
│   │   └── js/
│   │       └── app.js
│   │
│   ├── .htaccess
│   ├── admin_dashboard.php
│   ├── admin_login.php
│   ├── admin_logout.php
│   ├── admin_profile.php
│   ├── analytics.php
│   ├── api_live_stats.php
│   ├── index.php
│   ├── leave_action.php
│   ├── login.php
│   ├── signup.php
│   ├── task_action.php
│   ├── task_edit.php
│   ├── update_profile.php
│   │
│   ├── .env.example
│   ├── README.md
│   ├── talentflow.sql
│   └── test_email.php



````

---

## ⚙️ Setup Instructions

### 1️⃣ Clone the repository
```bash
git clone https://github.com/K-PranavEswar/talent-flow.git
````

### 2️⃣ Move into the project directory

```bash
cd talent-flow
```

### 3️⃣ Setup your local environment

* Install **XAMPP** (or similar Apache + MySQL stack)
* Move the folder into `htdocs`
  Example:
  `D:\xampp\htdocs\talentflow`

### 4️⃣ Configure the Database

1. Open [phpMyAdmin](http://localhost/phpmyadmin)
2. Create a new database:

   ```
   CREATE DATABASE talentflow;
   ```
3. Import the provided SQL file (if available) or manually create the `users` table:

   ```sql
   CREATE TABLE users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(100),
     email VARCHAR(100) UNIQUE,
     password VARCHAR(255),
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```

### 5️⃣ Start the Server

* Open **XAMPP Control Panel**
* Start **Apache** and **MySQL**
* Visit your app at:
  👉 [http://localhost/talentflow/public/signup.php](http://localhost/talentflow/public/signup.php)

---

## 📸 Screenshots

Staff Login
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/fa324f31-4202-465e-82bc-84e843d70f76" /><br><br>
SignUp page
<img width="1918" height="1017" alt="image" src="https://github.com/user-attachments/assets/492e1c6a-1f59-4c5a-99b4-4591463e7b1c" /><br><br>
Staff DashBoard
[▶️ **View**](https://drive.google.com/file/d/1Zkb3VYaakmRhfp2PZLPobB8jn58es9lN/view?usp=drive_link)<br><br>
Admin login
<img width="1918" height="1012" alt="image" src="https://github.com/user-attachments/assets/a1182b47-79c3-4e94-a29f-570b319c2106" /><br><br>
Admin Dashboard
[▶️ **Watch**](https://drive.google.com/file/d/1ROCrqZG7v6khu1YIJ-uEgfgAR3TZdd7W/view?usp=drive_link)<br><br>

---

## 🧠 Future Enhancements

* Role-based access (Admin, HR, Employee)
* Attendance tracking
* Email/SMS notifications
* Data export to Excel/PDF
* AI-powered analytics

---

## 👨‍💻 Author

**K. Pranav Eswar**
📎 [LinkedIn Profile](https://www.linkedin.com/in/k-pranav-eswar1/)
💻 MCA | Software Developer | Full-Stack Developer

## Teammates

**Adarsh H**
📎 [LinkedIn Profile](https://www.linkedin.com/in/adarsh-h-04548b327/)
💻 MCA | Software Developer | Aspiring Data Scientist | Python

**Adithya Dev P**
📎 [LinkedIn Profile](https://www.linkedin.com/in/adithya-dev-p-013961321/)
💻 MCA | Idea Creator

**Sanish Mahi S N**
💻 MCA | Documentation | UI designer
📎 [LinkedIn Profile](https://www.linkedin.com/in/sanish-mahi-b41bb3312/)

**Ananthu Krishna SS**
💻 MCA | Frontend Developer

> © 2025 **MACSEEDS** | Hackathon Series – *Powered by lablab.ai*
