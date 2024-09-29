# My Laravel Blog Application

## Overview
This is a Laravel-based blog application that allows users to create, view, edit, and delete posts. It also includes features like user authentication, comment system, and post thumbnail support.

## Features
- User authentication (login, register)
- CRUD operations for posts with real-time notifications (via Pusher)
- Comments Section
- Image upload for post thumbnails
- Slug generation for SEO-friendly URLs

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/cdibyayan/dib-blog-appe.git
   cd your-repo-name
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Environment setup**:
   - Duplicate `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure your `.env` file with proper database credentials and Pusher settings.

4. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrate the database**:
   ```bash
   php artisan migrate
   ```

6. **Serve the application**:
   ```bash
   php artisan serve
   ```

7. **Additional Setup**:
   - For broadcasting real-time notifications, ensure you have configured Pusher settings in the `.env` file.

## Key Features Demonstration
- Create posts with title, content, and featured image (thumbnail).
- View all posts with excerpts and authors’ names.
- Comment on posts and receive real-time updates via Pusher.

## License
This project is licensed under the MIT License.
