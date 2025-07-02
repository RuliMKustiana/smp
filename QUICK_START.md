# Quick Start Testing Guide

## 🚀 Start the Application

### Step 1: Start Laravel Server
Open PowerShell in the project directory and run:
```powershell
cd "c:\Users\Rifal\Documents\wpu-course\sistem_manajemen_proyek"
php artisan serve
```

The server will start on: **http://localhost:8000**

### Step 2: Access the Application
Open your browser and navigate to: **http://localhost:8000**

## 🔑 Test Users

| Role | Email | Password | Dashboard Features |
|------|-------|----------|-------------------|
| **Admin** | admin@sistem.com | password | User management, Report validation, System overview |
| **Project Manager** | pm@sistem.com | password | Project & Task management, Team coordination, Reports |
| **Employee** | employee@sistem.com | password | Personal tasks, Progress updates, File uploads |

## 🧪 Quick Testing Scenarios

### 1. Admin Testing (5 minutes)
1. Login as admin@sistem.com
2. Go to "Pengguna" → Create new user
3. Go to "Laporan" → Validate reports
4. Check dashboard statistics

### 2. Project Manager Testing (10 minutes)
1. Login as pm@sistem.com
2. Go to "Proyek" → Create new project
3. Go to "Tugas" → Create task and assign to employee
4. Check Kanban board view
5. Generate project report

### 3. Employee Testing (10 minutes)
1. Login as employee@sistem.com
2. View "Tugas Saya" dashboard
3. Update task status
4. Upload file to task
5. Add task progress update

## ✅ Key Features to Validate

### UI/UX Testing
- ✅ Responsive design on mobile/tablet/desktop
- ✅ Bootstrap components working (modals, dropdowns)
- ✅ Navigation between role-specific sections
- ✅ Real-time notifications

### Security Testing
- ✅ Role-based access control
- ✅ Unauthorized access prevention
- ✅ File upload restrictions
- ✅ CSRF protection

### Functionality Testing
- ✅ CRUD operations for all entities
- ✅ File upload/download
- ✅ Search and filtering
- ✅ Status updates and workflows
- ✅ Dashboard statistics

## 🐛 Common Issues & Solutions

### Server Won't Start
```powershell
# Check if port 8000 is in use
netstat -an | findstr :8000

# Use different port if needed
php artisan serve --port=8001
```

### Database Issues
```powershell
# Re-run migrations and seeders
php artisan migrate:fresh --seed
```

### Asset Issues
```powershell
# Rebuild assets
npm run build
```

## 📝 Testing Checklist

- [ ] Server starts successfully
- [ ] Login page loads properly
- [ ] All three roles can login
- [ ] Role-specific dashboards display
- [ ] Navigation menus work
- [ ] CRUD operations function
- [ ] File uploads work
- [ ] Notifications display
- [ ] Responsive design verified

## 🎯 Next Steps After Testing

1. **If issues found:** Document in GitHub issues
2. **If testing successful:** Ready for UAT
3. **For production:** Follow DEPLOYMENT.md guide

---

**Happy Testing! 🚀**
