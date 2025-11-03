# Social Media Application

A modern, responsive social media application built with Laravel, Vue.js, and Tailwind CSS. This platform allows users to connect, share posts, interact with content, and stay updated with notifications.

## ✨ Features

- 🔐 User authentication (register, login, password reset)
- 📝 Create and manage posts
- 💬 Comment on posts
- ❤️ Like/unlike posts
- 👥 User profiles
- 👥 Group pages
- 🔔 notifications system
- 📱 Dark/Light mode support

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue.js 3, Inertia.js 2
- **Styling**: Tailwind CSS
- **Database**: MySQL
- **Authentication**: Laravel Breeze

## 🚀 Installation Guide

### Prerequisites

- Docker
- Git
- Composer
- Node.js 16+ and NPM 

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/yamen-taleb/social-media.git
   cd social-media
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```
   
3. **Start Development Server**
   ```bash
   ./vendor/bin/sail up -d
   ```
   
4. **Environment setup**
   ```bash
   cp .env.example .env
   ./vendor/bin/sail php artisan key:generate
   ```

5. **Configure Database**
   Update your `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=social-media
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```
6. **Run Migrations**
   ```bash
   ./vendor/bin/sail php artisan migrate
   ```
   
7. **Build Assets**
   For development:
   ```bash
   npm run dev
   ```

8. **Access the Application**
   Open your browser and visit: [http://localhost:8000](http://localhost:8000)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/)
- [Vue.js](https://vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Inertia.js](https://inertiajs.com/)
