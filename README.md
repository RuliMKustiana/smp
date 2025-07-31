# Sistem Manajemen Proyek Perangkat Lunak

Aplikasi web Sistem Manajemen Proyek (Software Project Management System) yang dirancang untuk mengelola proyek pengembangan perangkat lunak dengan tiga peran pengguna: Administrator, Project Manager, dan Karyawan.

## 🎯 Fitur Lengkap

### 🔐 **Role-Based Access Control (RBAC)**
- **Administrator**: Mengelola pengguna, validasi laporan, dan oversight sistem
- **Project Manager**: Mengelola proyek, tugas, tim, dan laporan
- **Karyawan**: Mengerjakan tugas, update progress, dan kolaborasi tim

### 📊 **Dashboard Analytics**
- Dashboard berbeda untuk setiap role dengan statistik khusus
- Grafik progress proyek real-time
- Metrik performa tim dan individual
- Quick actions dan shortcuts

### 🏗️ **Manajemen Proyek Komprehensif**
- CRUD operations untuk proyek dengan validasi
- Tampilan Kanban untuk manajemen visual tugas
- Timeline dan milestone tracking
- Manajemen tim dan assignment
- Project status workflow (Planning → Active → Completed)

### ✅ **Sistem Tugas Lanjutan**
- Hierarchical task structure dengan dependencies
- Priority levels (High, Medium, Low)
- Status tracking (Pending → In Progress → Completed)
- Time estimation dan actual hours worked
- Approval workflow untuk task completion
- Attachment support untuk setiap tugas

### 📝 **Task Updates & Progress Tracking**
- Real-time progress updates dari karyawan
- Hour logging untuk time tracking
- File attachments pada setiap update
- Status changes dengan notifications
- Progress history dan audit trail

### 📋 **Sistem Laporan Terintegrasi**
- Template laporan untuk berbagai jenis (Progress, Weekly, Monthly, Final)
- Workflow approval laporan oleh Administrator
- File attachment untuk laporan
- Validasi dengan catatan dan feedback
- Export dan sharing capabilities

### 🔔 **Notifikasi Real-time**
- Database notifications dengan UI dropdown
- Email notifications untuk events penting
- Notification categories (Task Assignment, Updates, Approvals)
- Mark as read/unread functionality
- Bulk actions untuk notifications

### 💬 **Kolaborasi Tim**
- Comment system untuk proyek dan tugas
- File sharing dan version control
- Activity feed dan timeline
- Team communication tools

### 🔍 **Global Search**
- Search across projects, tasks, dan users
- Advanced filtering options
- Real-time search suggestions
- Results categorization

### 📱 **UI/UX Modern**
- Responsive design dengan Bootstrap 5
- Interactive components dengan Livewire
- Drag-and-drop Kanban board
- Professional color scheme dan typography
- Mobile-friendly interface

### 🛡️ **Security & Authorization**
- Middleware-based access control
- Policy-based permissions untuk setiap resource
- CSRF protection
- Input validation dan sanitization
- Secure file upload handling

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x dengan PHP 8.2+
- **Frontend**: Bootstrap 5.3 + Livewire 3
- **Database**: SQLite (development), MySQL/PostgreSQL (production)
- **Authentication**: Laravel Breeze dengan custom RBAC
- **Charts**: Chart.js untuk analytics
- **Icons**: Font Awesome 6.4
- **File Storage**: Laravel Storage dengan local/cloud support

## 📦 Instalasi Lokal

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- SQLite extension untuk PHP

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd sistem_manajemen_proyek
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**
   
   File `.env` sudah dikonfigurasi untuk SQLite. Database akan dibuat otomatis saat migration.
   
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

5. **Jalankan Migration & Seeder**
   ```bash
   php artisan migrate --seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Jalankan Server Development**
   ```bash
   php artisan serve
   ```

8. **Akses Aplikasi**
   
   Buka browser dan akses: `http://localhost:8000`

## Akun Login Default

Setelah menjalankan seeder, gunakan akun berikut untuk login:

### 👨‍💼 **Administrator**
- **Email**: `admin@sistem.com`
- **Password**: `password`
- **Akses**: Manajemen pengguna, validasi laporan, oversight sistem

### 🏗️ **Project Manager**
- **Email**: `pm@sistem.com`
- **Password**: `password`
- **Akses**: Manajemen proyek, tugas, tim, dan laporan

### 👩‍💻 **Karyawan**
- **Email**: `employee@sistem.com`
- **Password**: `password`
- **Akses**: Mengerjakan tugas, update progress

### 👨‍💻 **Karyawan Tambahan**
- **Email**: `employee2@sistem.com` / `employee3@sistem.com`
- **Password**: `password`
- **Akses**: Testing kolaborasi tim

## Struktur Database

### Tabel Utama
- `users` - Data pengguna dengan role-based access
- `roles` - Definisi peran (Admin, PM, Employee)
- `projects` - Data proyek dengan status dan prioritas
- `tasks` - Tugas dengan hierarki dan assignment
- `project_user` - Many-to-many relationship proyek-pengguna

### Tabel Pendukung
- `task_updates` - Log update progress tugas
- `comments` - Sistem komentar (polymorphic)
- `attachments` - File attachment (polymorphic)
- `reports` - Laporan proyek dengan workflow approval
- `notifications` - Sistem notifikasi Laravel

## Perintah Artisan Berguna

```bash
# Reset database dan data
php artisan migrate:fresh --seed

# Generate storage link untuk file uploads
php artisan storage:link

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Queue worker (jika menggunakan jobs)
php artisan queue:work
```

## Development Guidelines

### Struktur Folder
```
app/
├── Http/Controllers/
│   ├── Admin/          # Controller untuk Administrator
│   ├── ProjectManager/ # Controller untuk Project Manager
│   └── Employee/       # Controller untuk Karyawan
├── Livewire/          # Komponen Livewire
├── Models/            # Eloquent Models
├── Policies/          # Authorization Policies
└── Notifications/     # Notification Classes

resources/views/
├── admin/             # Views untuk Administrator
├── pm/                # Views untuk Project Manager
├── employee/          # Views untuk Karyawan
└── components/        # Reusable Blade Components
```

### Naming Conventions
- Routes: `admin.users.index`, `pm.projects.show`, `employee.tasks.update`
- Controllers: `Admin\UserController`, `ProjectManager\ProjectController`
- Models: PascalCase dengan singular (User, Project, Task)
- Views: snake_case dengan folder structure sesuai role

## ✅ Status Implementasi

### 🏁 **Completed Features (100%)**

#### Authentication & Authorization
- ✅ Role-based middleware (AdminMiddleware, PmMiddleware, EmployeeMiddleware)
- ✅ Policy-based authorization (ProjectPolicy, TaskPolicy, ReportPolicy)
- ✅ User management dengan RBAC
- ✅ Login/Register dengan Laravel Breeze

#### Database & Models
- ✅ Complete migration files dengan foreign keys
- ✅ Eloquent models dengan relationships
- ✅ Seeders untuk data awal
- ✅ Polymorphic relationships (comments, attachments)

#### Admin Panel
- ✅ User management (CRUD operations)
- ✅ User status toggle (active/inactive)
- ✅ Report validation workflow
- ✅ Admin dashboard dengan statistics

#### Project Manager Features
- ✅ Project CRUD dengan team management
- ✅ Kanban board view untuk tasks
- ✅ Task creation, assignment, dan monitoring
- ✅ Report creation dengan templates
- ✅ PM dashboard dengan project overview

#### Employee Features
- ✅ Task list dengan filtering dan sorting
- ✅ Task detail view dengan attachments
- ✅ Task status updates (Pending → In Progress → Completed)
- ✅ Progress updates dengan hour logging
- ✅ Employee dashboard dengan task statistics

#### Advanced Features
- ✅ Real-time notifications dengan dropdown UI
- ✅ Global search functionality
- ✅ File upload dan attachment system
- ✅ Email notifications untuk key events
- ✅ Comment system untuk collaboration

#### UI/UX
- ✅ Responsive Bootstrap 5 design
- ✅ Professional navigation dengan role-specific menus
- ✅ Interactive forms dengan validation
- ✅ Progress bars dan status indicators
- ✅ Modal dialogs untuk quick actions

### 🚀 **Ready for Production**

#### Core Views Implemented
- ✅ Admin: users/create, users/edit, users/show, reports/index, reports/show
- ✅ PM: tasks/index, tasks/create, tasks/show, tasks/edit, reports/create
- ✅ Employee: tasks/index, tasks/show, task-updates/create, projects/index
- ✅ Shared: notifications/index, search/index

#### Architecture
- ✅ MVC pattern dengan proper separation
- ✅ Service layer untuk business logic
- ✅ Repository pattern untuk data access
- ✅ Event-driven notifications
- ✅ RESTful API design

## Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push branch (`git push origin feature/AmazingFeature`)
5. Buka Pull Request

## License

Aplikasi ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lengkap.

## Support

Untuk pertanyaan atau bantuan, silakan buka issue di repository atau hubungi tim development.

---

**Developed with ❤️ using Laravel & Bootstrap**

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
#   s m p  
 