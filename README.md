# ToDo List App
 Đây là dự án cá nhân tôi tự làm để thực hành về Backend
 Các chức năng từ đăng nhập , đăng ký , thêm / sửa / xoá dữ liệu ,... đều được gọi API tới server thực tế 
 Tuy nhiên vẫn còn nhiều thiếu xót , mong mọi người góp ý để tôi có thể cải thiện và phát triển bản thân

 This is a personal project I built to practice backend development.
 Features such as login, registration, and adding / editing / deleting data are all handled by calling APIs to a real server.
 However, the project still has many shortcomings. I would really appreciate any feedback so I can continue improving and developing my skills.

----------

## Link Demo
Chưa có


----------

## 📌 Features (Các tính năng)
- Đăng nhập / Đăng ký & Đăng xuất
- Thêm , sửa , xoá các task
- Đánh dấu hoàn thành các task
- Sắp xếp thứ tự ưu tiên các task theo 3 mức độ Cao / Trung bình / Thấp
- Thống kê các task (Tổng số lượng các task / Số task đã hoàn thành / Số task còn lại chưa hoàn thành)

- User login / register & logout
- Add , edit , delete tasks
- Mark task as completed
- Sort task by priority (High / Medium / Low)
- Task statistics (Total / Done / Left)

----------

## 🛠️ Tech Stack (Các công nghệ)
- Giao diện : HTML , CSS , JAVASCRIPT
- Máy chủ : PHP
- Cơ sở dữ liệu : MySQL

- Frontend: HTML, CSS, JAVASCRIPT
- Backend: PHP
- Database: MySQL

----------

## 📁 Project Structure (Cấu trúc thư mục)
TO_DO_LIST_PROJECT/
├── CSS/
│   ├── auth.css
│   └── style.css
├── HTML/
│   ├── auth.html
│   └── index.html
├── Image/
│   ├── background_TodoList.jpg
│   └── backgroundLogin.jpg
├── JS/
│   ├── api/
│   │   ├── taskApi.js
│   │   └── userApi.js
│   ├── auth/
│   │   ├── auth.js
│   │   ├── login.js
│   │   ├── logout.js
│   │   ├── register.js
│   │   └── validator.js
│   └── Dashboard/
│       ├── app.js
│       ├── helpers.js
│       └── index.js
└── PHP/
    ├── connect.inc
    └── api/
        ├── task.php
        └── user.php