# Tilawa - File Tree Structure

```
tilawa/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── TenantSwitchController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── TeacherController.php
│   │   │   │   ├── ClassController.php
│   │   │   │   ├── StudentController.php
│   │   │   │   ├── FoundationSkillController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   ├── OnboardingController.php
│   │   │   │   └── ReportController.php
│   │   │   ├── Teacher/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── StudentController.php
│   │   │   │   └── SessionController.php
│   │   │   ├── Public/
│   │   │   │   └── ParentController.php
│   │   │   ├── Saas/
│   │   │   │   └── TenantController.php
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   ├── SetTenant.php
│   │   │   └── RoleMiddleware.php
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Tenant.php
│   │   │   ├── TenantUser.php
│   │   │   ├── StudyClass.php
│   │   │   ├── Student.php
│   │   │   ├── Session.php
│   │   │   ├── Surah.php
│   │   │   ├── FoundationSkill.php
│   │   │   ├── StudentFoundationSkillMastery.php
│   │   │   ├── TenantSetting.php
│   │   │   ├── Setting.php
│   │   │   └── Skill.php
│   │   └── Services/
│   │       └── ReportService.php
├── bootstrap/
│   └── app.php
├── database/
│   ├── migrations/
│   │   └── 0000_01_01_000000_create_tables.php
│   └── seeders/
│       ├── AdminSeeder.php
│       ├── DatabaseSeeder.php
│       └── SurahSeeder.php
├── docker/
│   ├── apache/
│   │   └── 000-default.conf
│   └── php/
│       └── Dockerfile
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   └── login.blade.php
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── admin/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── teachers/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── classes/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── students/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── foundation_skills/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── create.blade.php
│   │   │   │   └── edit.blade.php
│   │   │   ├── settings/
│   │   │   │   └── edit.blade.php
│   │   │   ├── onboarding/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── teachers.blade.php
│   │   │   │   ├── classes.blade.php
│   │   │   │   └── students.blade.php
│   │   │   └── reports/
│   │   │       ├── index.blade.php
│   │   │       └── teacher.blade.php
│   │   ├── teacher/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── students/
│   │   │   │   └── index.blade.php
│   │   │   └── sessions/
│   │   │       ├── index.blade.php
│   │   │       ├── create.blade.php
│   │   │       └── edit.blade.php
│   │   ├── public/
│   │   │   └── student.blade.php
│   │   └── saas/
│   │       └── tenants/
│   │           ├── index.blade.php
│   │           ├── create.blade.php
│   │           └── edit.blade.php
├── routes/
│   └── web.php
├── storage/
│   └── backups/
├── .env.docker
├── docker-compose.yml
├── backup.sh
├── DEPLOYMENT.md
└── README.md
```

## Key Components

### Models
- **Tenant**: Multi-tenant organization
- **User**: Global users (SaaS admin + tenant members)
- **TenantUser**: Per-tenant roles and teacher group permissions
- **StudyClass**: Halaqat classes with groups and teacher assignment
- **Student**: Students with parent contact and token
- **Surah**: Quran surahs (seeded)
- **Session**: Memorization sessions with attendance and ayah ranges
- **FoundationSkill**: Foundation skills list per tenant
- **StudentFoundationSkillMastery**: Skill mastery per student
- **TenantSetting**: Score thresholds JSON

### Controllers
- **Auth**: Login/logout
- **SaaS**: Tenants CRUD
- **Admin**: Dashboard, teachers, classes, students, skills, settings, onboarding, reports
- **Teacher**: Dashboard, students, sessions
- **Public**: Parent access via token

### Reports
- Top students by memorized ayahs (date range)
- Inactive students by last session date
- Teacher performance metrics

### Backup
- `backup.sh` uses `mysqldump` and keeps 14 days of backups
