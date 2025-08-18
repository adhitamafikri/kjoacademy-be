# LMS Database Entity Relationship Diagram (ERD)

## Database Overview
This ERD represents the complete database structure for the KJO Academy LMS (Learning Management System) with comprehensive user management, course management, progress tracking, and onboarding systems.

---

## Core Tables

### 1. **roles** (User Roles & Permissions)
```
┌─────────────────────────────────────────────────────────┐
│                        roles                            │
├─────────────────────────────────────────────────────────┤
│ id (ULID) [PK]                                         │
│ name (string, unique)                                  │
│ description (text)                                     │
│ permissions (json)                                     │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

### 2. **users** (User Management)
```
┌─────────────────────────────────────────────────────────┐
│                        users                            │
├─────────────────────────────────────────────────────────┤
│ id (ULID) [PK]                                         │
│ role_id (ULID) [FK → roles.id]                         │
│ name (string)                                          │
│ phone (string, unique)                                 │
│ email (string, unique, nullable)                       │
│ email_verified_at (timestamp, nullable)                │
│ password (string, nullable)                            │
│ onboarding_completed_at (timestamp, nullable)          │
│ onboarding_started_at (timestamp, nullable)            │
│ remember_token (string, nullable)                      │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

### 3. **sessions** (User Sessions - Single Session Auth)
```
┌─────────────────────────────────────────────────────────┐
│                       sessions                          │
├─────────────────────────────────────────────────────────┤
│ id (ULID) [PK]                                         │
│ user_id (ULID) [FK → users.id]                         │
│ ip_address (ipAddress)                                 │
│ device_info (json)                                     │
│ user_agent (text)                                      │
│ payload (string)                                       │
│ expires_at (timestamp)                                 │
│ created_at (timestamp)                                 │
│ last_activity (timestamp)                              │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

### 4. **otps** (One-Time Passwords)
```
┌─────────────────────────────────────────────────────────┐
│                         otps                            │
├─────────────────────────────────────────────────────────┤
│ id (bigint) [PK]                                       │
│ user_id (ULID) [FK → users.id]                         │
│ otp_code (char(6))                                     │
│ purpose (string(50))                                   │
│ expires_at (timestamp)                                 │
│ verified_at (timestamp, nullable)                      │
│ attempts (tinyInteger, default: 0)                     │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

---

## Course Management Tables

### 5. **course_categories** (Course Categories)
```
┌─────────────────────────────────────────────────────────┐
│                  course_categories                      │
├─────────────────────────────────────────────────────────┤
│ id (ULID) [PK]                                         │
│ title (string)                                         │
│ slug (string)                                          │
│ description (text)                                     │
│ courses_count (integer, default: 0)                    │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

### 6. **courses** (Course Information)
```
┌─────────────────────────────────────────────────────────┐
│                       courses                           │
├─────────────────────────────────────────────────────────┤
│ id (ULID) [PK]                                         │
│ title (string)                                         │
│ slug (string, unique)                                  │
│ description (text)                                     │
│ thumbnail_url (text)                                   │
│ enrollment_count (integer, default: 0)                 │
│ duration_seconds (integer, default: 0)                 │
│ is_published (boolean, default: false)                 │
│ metadata (json, nullable)                              │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

### 7. **course_course_category** (Many-to-Many: Courses ↔ Categories)
```
┌─────────────────────────────────────────────────────────┐
│                course_course_category                   │
├─────────────────────────────────────────────────────────┤
│ course_id (ULID) [FK → courses.id]                     │
│ course_category_id (ULID) [FK → course_categories.id]  │
│ is_primary (boolean, default: false)                   │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ PRIMARY KEY (course_id, course_category_id)            │
└─────────────────────────────────────────────────────────┘
```

### 8. **course_modules** (Course Modules)
```
┌─────────────────────────────────────────────────────────┐
│                    course_modules                       │
├─────────────────────────────────────────────────────────┤
│ id (ULID) [PK]                                         │
│ course_id (ULID) [FK → courses.id]                     │
│ title (string)                                         │
│ order (integer)                                        │
│ lessons_count (integer, default: 0)                    │
│ duration_seconds (integer, default: 0)                 │
│ is_published (boolean, default: false)                 │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

### 9. **course_lessons** (Course Lessons)
```
┌─────────────────────────────────────────────────────────┐
│                   course_lessons                        │
├─────────────────────────────────────────────────────────┤
│ id (ULID) [PK]                                         │
│ course_module_id (ULID) [FK → course_modules.id]       │
│ title (string)                                         │
│ order (integer)                                        │
│ lesson_type (string)                                   │
│ lesson_content_url (string)                            │
│ duration_seconds (integer)                             │
│ is_published (boolean, default: false)                 │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

---

## Enrollment & Progress Tracking Tables

### 10. **enrollments** (User Course Enrollments)
```
┌─────────────────────────────────────────────────────────┐
│                     enrollments                         │
├─────────────────────────────────────────────────────────┤
│ id (bigint) [PK]                                       │
│ user_id (ULID) [FK → users.id]                         │
│ course_id (ULID) [FK → courses.id]                     │
│ status (enum: enrolled, in_progress, completed, dropped)│
│ progress_percentage (integer, default: 0)              │
│ enrolled_at (timestamp)                                │
│ completed_at (timestamp, nullable)                     │
│ last_accessed_at (timestamp, nullable)                 │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
│ UNIQUE (user_id, course_id)                            │
└─────────────────────────────────────────────────────────┘
```

### 11. **module_progress** (Module Progress Tracking)
```
┌─────────────────────────────────────────────────────────┐
│                   module_progress                       │
├─────────────────────────────────────────────────────────┤
│ id (bigint) [PK]                                       │
│ user_id (ULID) [FK → users.id]                         │
│ course_module_id (ULID) [FK → course_modules.id]       │
│ course_enrollment_id (bigint) [FK → enrollments.id]    │
│ status (enum: not_started, in_progress, completed)     │
│ progress_percentage (integer, default: 0)              │
│ lessons_completed_count (integer, default: 0)          │
│ started_at (timestamp, nullable)                       │
│ completed_at (timestamp, nullable)                     │
│ last_accessed_at (timestamp, nullable)                 │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
│ UNIQUE (user_id, course_module_id)                     │
└─────────────────────────────────────────────────────────┘
```

### 12. **lesson_progress** (Lesson Progress Tracking)
```
┌─────────────────────────────────────────────────────────┐
│                   lesson_progress                       │
├─────────────────────────────────────────────────────────┤
│ id (bigint) [PK]                                       │
│ user_id (ULID) [FK → users.id]                         │
│ course_lesson_id (ULID) [FK → course_lessons.id]       │
│ course_enrollment_id (bigint) [FK → enrollments.id]    │
│ status (enum: not_started, in_progress, completed)     │
│ started_at (timestamp, nullable)                       │
│ completed_at (timestamp, nullable)                     │
│ time_spent_seconds (integer, default: 0)               │
│ video_progress_seconds (integer, default: 0)           │
│ last_accessed_at (timestamp, nullable)                 │
│ attempts_count (integer, default: 0)                   │
│ score (decimal(5,2), nullable)                         │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
│ UNIQUE (user_id, course_lesson_id)                     │
└─────────────────────────────────────────────────────────┘
```

### 13. **onboarding_progress** (Onboarding Course Progress)
```
┌─────────────────────────────────────────────────────────┐
│                onboarding_progress                      │
├─────────────────────────────────────────────────────────┤
│ id (bigint) [PK]                                       │
│ user_id (ULID) [FK → users.id]                         │
│ onboarding_course_id (ULID) [FK → courses.id]          │
│ status (enum: not_started, in_progress, completed)     │
│ progress_percentage (integer, default: 0)              │
│ started_at (timestamp, nullable)                       │
│ completed_at (timestamp, nullable)                     │
│ last_accessed_at (timestamp, nullable)                 │
│ created_at (timestamp)                                 │
│ updated_at (timestamp)                                 │
│ deleted_at (timestamp, nullable)                       │
│ UNIQUE (user_id, onboarding_course_id)                 │
└─────────────────────────────────────────────────────────┘
```

---

## System Tables

### 14. **password_reset_tokens** (Password Reset)
```
┌─────────────────────────────────────────────────────────┐
│               password_reset_tokens                     │
├─────────────────────────────────────────────────────────┤
│ email (string) [PK]                                    │
│ payload (string)                                       │
│ created_at (timestamp, nullable)                       │
└─────────────────────────────────────────────────────────┘
```

### 15. **cache** (Laravel Cache)
```
┌─────────────────────────────────────────────────────────┐
│                        cache                            │
├─────────────────────────────────────────────────────────┤
│ key (string) [PK]                                      │
│ value (longText)                                       │
│ expiration (integer)                                   │
└─────────────────────────────────────────────────────────┘
```

### 16. **jobs** (Queue Jobs)
```
┌─────────────────────────────────────────────────────────┐
│                         jobs                            │
├─────────────────────────────────────────────────────────┤
│ id (bigint) [PK]                                       │
│ queue (string)                                         │
│ payload (longText)                                     │
│ attempts (tinyInteger)                                 │
│ reserved_at (integer, nullable)                        │
│ available_at (integer)                                 │
│ created_at (integer)                                   │
└─────────────────────────────────────────────────────────┘
```

---

## Entity Relationships

### Primary Relationships:
```
users (1) ←→ (Many) enrollments (Many) ←→ (1) courses
users (1) ←→ (Many) module_progress (Many) ←→ (1) course_modules
users (1) ←→ (Many) lesson_progress (Many) ←→ (1) course_lessons
users (1) ←→ (Many) onboarding_progress (Many) ←→ (1) courses
users (1) ←→ (Many) sessions (Many) ←→ (1) users
users (1) ←→ (Many) otps (Many) ←→ (1) users
roles (1) ←→ (Many) users (Many) ←→ (1) roles
courses (Many) ←→ (Many) course_categories (Many) ←→ (Many) courses
courses (1) ←→ (Many) course_modules (Many) ←→ (1) courses
course_modules (1) ←→ (Many) course_lessons (Many) ←→ (1) course_modules
enrollments (1) ←→ (Many) module_progress (Many) ←→ (1) enrollments
enrollments (1) ←→ (Many) lesson_progress (Many) ←→ (1) enrollments
```

### Key Features:
- **Single Session Authentication**: Users can only be logged in on one device at a time
- **Many-to-Many Course Categories**: Courses can belong to multiple categories
- **Comprehensive Progress Tracking**: Individual tracking for courses, modules, and lessons
- **Onboarding System**: Mandatory onboarding completion before accessing other content
- **Video Resume**: Track exact video progress for seamless resuming
- **Soft Deletes**: Data preservation across all major tables
- **Performance Optimized**: Strategic indexing for common queries

---

## Database Schema Summary

| Table | Purpose | Key Features |
|-------|---------|--------------|
| `roles` | User roles and permissions | JSON permissions, soft deletes |
| `users` | User management | Onboarding tracking, role-based access |
| `sessions` | Single session auth | Device tracking, IP logging |
| `otps` | One-time passwords | Expiration, verification tracking |
| `course_categories` | Course organization | Slug-based routing, course counting |
| `courses` | Course information | Publishing status, enrollment tracking |
| `course_course_category` | Course-category relationships | Primary category designation |
| `course_modules` | Course structure | Ordering, lesson counting |
| `course_lessons` | Learning content | Multiple lesson types, content URLs |
| `enrollments` | User course access | Progress tracking, status management |
| `module_progress` | Module completion | Individual module tracking |
| `lesson_progress` | Lesson completion | Video resume, time tracking |
| `onboarding_progress` | Onboarding completion | Mandatory course tracking |
| `password_reset_tokens` | Password recovery | Laravel standard |
| `cache` | Application caching | Laravel standard |
| `jobs` | Queue processing | Laravel standard |

This ERD represents a comprehensive LMS system with robust user management, flexible course organization, detailed progress tracking, and mandatory onboarding workflows.
