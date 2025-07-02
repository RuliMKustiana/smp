// app/Providers/AuthServiceProvider.php
protected $policies = [
    Project::class => ProjectPolicy::class, // Tambahkan baris ini
];