# Testing Guide - Sistem Manajemen Proyek

## 🧪 Manual Testing Scenarios

### 1. Authentication & Authorization Testing

#### Login Testing
```
1. Akses http://localhost:8000
2. Klik "Login"
3. Test dengan credentials berikut:
   - admin@sistem.com / password
   - pm@sistem.com / password  
   - employee@sistem.com / password
4. Verify redirect ke dashboard sesuai role
```

#### RBAC Testing
```
1. Login sebagai Employee
2. Coba akses URL admin: /admin/users
3. Verify 403 Forbidden response
4. Login sebagai PM
5. Coba akses URL admin: /admin/reports
6. Verify 403 Forbidden response
```

### 2. Admin Functionality Testing

#### User Management
```
1. Login sebagai Admin
2. Navigate ke "Kelola Pengguna"
3. Test Create User:
   - Klik "Tambah Pengguna"
   - Fill form dengan data valid
   - Submit dan verify user terbuat
4. Test Edit User:
   - Klik "Edit" pada user
   - Modify data
   - Submit dan verify changes
5. Test Toggle Status:
   - Klik toggle status button
   - Verify status berubah
```

#### Report Validation
```
1. Login sebagai PM → buat laporan
2. Login sebagai Admin
3. Navigate ke "Validasi Laporan"
4. Klik "Detail" pada laporan
5. Test approve/reject dengan catatan
6. Verify notification ke PM
```

### 3. Project Manager Testing

#### Project Management
```
1. Login sebagai PM
2. Navigate ke "Proyek"
3. Test Create Project:
   - Klik "Tambah Proyek"
   - Fill form dengan team members
   - Submit dan verify project terbuat
4. Test Kanban View:
   - Klik "Kanban" pada project
   - Verify tasks ditampilkan per column
```

#### Task Management
```
1. Dalam project, test Create Task:
   - Klik "Tambah Tugas"
   - Assign ke employee
   - Set priority dan deadline
   - Submit dan verify task terbuat
2. Test Task Monitoring:
   - Klik "Detail" pada task
   - Verify progress updates muncul
   - Test approve/reject task completion
```

#### Report Creation
```
1. Navigate ke "Laporan"
2. Klik "Buat Laporan"
3. Test templates:
   - Klik tombol template "Progress"
   - Verify form terisi dengan template
4. Submit laporan dan verify tersimpan
```

### 4. Employee Testing

#### Task Management
```
1. Login sebagai Employee
2. Navigate ke "Tugas Saya"
3. Verify hanya tasks assigned muncul
4. Test Status Updates:
   - Klik "Detail" pada task
   - Klik "Mulai Kerjakan"
   - Verify status berubah
```

#### Progress Updates
```
1. Dalam task detail:
   - Klik "Tambah Update"
   - Fill description dan hours worked
   - Upload attachment
   - Submit dan verify update tersimpan
2. Test status change via update:
   - Tambah update dengan status "Completed"
   - Verify task status berubah
```

#### Project View
```
1. Navigate ke "Proyek Saya"
2. Verify hanya projects yang joined muncul
3. Klik "Detail" pada project
4. Verify bisa lihat semua tasks dalam project
```

### 5. Notification Testing

#### Real-time Notifications
```
1. Login sebagai PM, assign task ke Employee
2. Login sebagai Employee (tab baru)
3. Verify notification bell menunjukkan notif baru
4. Klik notification bell
5. Verify dropdown menampilkan notification
6. Klik "Mark as Read" dan verify
```

#### Email Notifications
```
1. Setup mail configuration di .env
2. Assign task ke user
3. Verify email dikirim ke user
4. Approve/reject report
5. Verify email dikirim ke PM
```

### 6. Search Functionality

#### Global Search
```
1. Login dengan role apapun
2. Klik search icon di navigation
3. Test search queries:
   - Search "project name"
   - Search "task title" 
   - Search "user name"
4. Verify results terkelompok per category
5. Klik result dan verify redirect benar
```

### 7. File Upload Testing

#### Attachment Testing
```
1. Test dalam task creation:
   - Upload berbagai format file (pdf, doc, img)
   - Verify file tersimpan di storage/app/public
2. Test dalam task updates:
   - Upload multiple files
   - Verify download link berfungsi
3. Test size limit:
   - Upload file > 10MB
   - Verify error message muncul
```

### 8. UI/UX Testing

#### Responsive Design
```
1. Test di berbagai screen size:
   - Desktop (1920x1080)
   - Tablet (768x1024)
   - Mobile (375x667)
2. Verify navigation responsive
3. Verify tables scroll horizontal di mobile
4. Verify modals center properly
```

#### Interactive Elements
```
1. Test dropdown menus
2. Test modal dialogs
3. Test form validation
4. Test loading states
5. Test hover effects
```

## 🐛 Common Issues & Solutions

### Issue: 403 Forbidden
**Solution**: 
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Check middleware assignment in routes
```

### Issue: File upload tidak berfungsi
**Solution**:
```bash
# Create storage link
php artisan storage:link

# Check permissions
chmod -R 755 storage/
```

### Issue: Notifications tidak muncul
**Solution**:
```bash
# Run migrations
php artisan migrate

# Check notification table exists
```

### Issue: Dashboard statistics kosong
**Solution**:
```bash
# Run seeders
php artisan db:seed

# Check relationships in models
```

## 📊 Performance Testing

### Database Queries
```
1. Enable query logging di AppServiceProvider
2. Monitor N+1 queries
3. Test with large datasets (100+ projects, 1000+ tasks)
4. Verify pagination works correctly
```

### File Upload Performance
```
1. Test concurrent file uploads
2. Test large file uploads
3. Monitor disk space usage
4. Test file deletion cleanup
```

## ✅ Test Checklist

- [ ] Authentication flow (login/logout/register)
- [ ] RBAC enforcement (admin/pm/employee access)
- [ ] User CRUD operations (admin)
- [ ] Project management (PM)
- [ ] Task management (PM & Employee)
- [ ] Report workflow (PM → Admin)
- [ ] Notification system
- [ ] File upload/download
- [ ] Search functionality
- [ ] Responsive design
- [ ] Email notifications
- [ ] Form validations
- [ ] Error handling
- [ ] Security (CSRF, SQL injection prevention)

## 📈 Test Data for Load Testing

### Create Test Data
```bash
# Create 50 users
php artisan tinker
User::factory(50)->create();

# Create 20 projects with tasks
Project::factory(20)->has(Task::factory(10))->create();
```

### Monitoring
```bash
# Monitor app performance
php artisan telescope:install

# Monitor database queries
DB::enableQueryLog();
dd(DB::getQueryLog());
```
