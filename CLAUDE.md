# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Install and setup
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate

# Full setup in one command
composer setup         # installs deps, copies .env, generates key, migrates, builds assets

# Development
composer dev           # starts Artisan server + queue listener + log viewer + Vite concurrently
npm run dev            # Vite dev server only
php artisan serve      # Artisan dev server only

# Testing
composer test          # clears config cache then runs Pest suite
php artisan test       # run tests via Artisan
./vendor/bin/pest --filter=TestName   # run a single test

# Code quality
./vendor/bin/pint      # Laravel Pint code formatter

# Database
php artisan migrate
php artisan migrate:fresh --seed

# Real-time (WebSocket server)
php artisan reverb:start
```

## Architecture

Laravel 12 LMS with Blade + Alpine.js frontend, Tailwind CSS, and Laravel Reverb for real-time WebSockets.

### Role-Based Access (Spatie Permission)

Three roles with dedicated middleware and route groups:
- **Admin** — `AdminMiddleware`, prefix `/admin`
- **Teacher** — `TeacherMiddleware`, prefix `/teacher`
- **Student** — `StudentMiddleware`, prefix `/student`

Public routes (`/`, `/home/courses`, `/register`, `/payment/upload`) require no authentication.

### Data Model

The core entity is a **Course** (stored in the `modules` table as `Courses` model). Courses connect:
- **Teachers** via `teacher_modules` pivot
- **Students** via `Enrollment` pivot (with status tracking)
- **Lessons** → each has many **LessonResources** (files: PDF, DOCX, ZIP, PPT)
- **OnlineClasses** → each has many **Assignments** → each has many **Submissions**

**Registration** and **PaymentSlip** are pre-auth models for the student onboarding flow (register → upload payment → admin approves → account created).

**Attendance** tracks students joining online classes (stores `joined_at` and IP).

### Real-Time Broadcasting

`ClassStarted` event is broadcast over Reverb when a teacher starts an online class. Students subscribed to the class channel receive a live notification. Channels are defined in `routes/channels.php`.

### File Storage

All uploads (lesson materials, assignment submissions, payment slips) use Laravel's `public` disk. Files are stored under `storage/app/public/` and served via `storage/` symlink.

### Key Controllers

| Controller | Responsibility |
|---|---|
| `RegisterController` | Public student registration + payment slip upload |
| `StudentController` | Student dashboard, enrolled courses, class joining |
| `TeacherController` | Teacher dashboard, lesson/material/class management |
| `AssignmentController` | Assignment CRUD + student submission + grading |
| `AttendenceController` | Attendance recording when student joins a class |
| `DashboardController` | Admin dashboard |
| `RolesController` / `PermissionsController` | Spatie role/permission CRUD |
| `PaymentController` | Payment slip approval workflow |
| `ReportsController` | Admin financial/activity reports |

### View Structure

Blade views are organized under `resources/views/` by role:
- `layouts/` — shared layout files used across the git-tracked views
- `home_layouts/` — public-facing pages
- `teacherLayouts/` — teacher feature pages
- `studentLayouts/` — student feature pages
- `roles/` — admin role/permission management

### Database

MySQL, database name `lms` (see `.env`). Sessions and cache are database-backed. Queue driver is `sync` in development — switch to `database` or `redis` for production.
