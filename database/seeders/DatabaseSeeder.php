<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions
        $permissions = [
            'manage users',
            'manage settings',
            'manage categories',
            'publish articles',
            'create drafts',
            'moderate comments',
            'view analytics',
            'upload media',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $commAdminRole = Role::firstOrCreate(['name' => 'comm_admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        // 3. Assign permissions per Section 4 matrix
        // super_admin: all permissions
        $superAdminRole->syncPermissions(Permission::all());

        // comm_admin: all except manage users
        $commAdminRole->syncPermissions([
            'manage settings',
            'manage categories',
            'publish articles',
            'create drafts',
            'moderate comments',
            'view analytics',
            'upload media',
        ]);

        // editor: publish, drafts, moderate, analytics, upload
        $editorRole->syncPermissions([
            'publish articles',
            'create drafts',
            'moderate comments',
            'view analytics',
            'upload media',
        ]);

        // viewer: no permissions

        // 4. Create super_admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@muni.ac.ug'],
            [
                'username' => 'admin',
                'full_name' => 'Super Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'avatar' => null,
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole($superAdminRole);
        }

        // 5. Default Categories
        $defaultCategories = [
            ['name' => 'News', 'slug' => 'news', 'description' => 'Latest news from Muni University', 'sort_order' => 1],
            ['name' => 'Announcements', 'slug' => 'announcements', 'description' => 'Official announcements', 'sort_order' => 2],
            ['name' => 'Events', 'slug' => 'events', 'description' => 'University events', 'sort_order' => 3],
            ['name' => 'Research', 'slug' => 'research', 'description' => 'Research and innovation', 'sort_order' => 4],
            ['name' => 'Student Stories', 'slug' => 'student-stories', 'description' => 'Stories from students', 'sort_order' => 5],
            ['name' => 'Staff Stories', 'slug' => 'staff-stories', 'description' => 'Stories from staff', 'sort_order' => 6],
        ];

        foreach ($defaultCategories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // 6. Default Settings
        $defaultSettings = [
            ['key' => 'site_name', 'value' => 'Muni University News & Media Portal'],
            ['key' => 'site_tagline', 'value' => 'Transforming Lives'],
            ['key' => 'site_email', 'value' => 'info@muni.ac.ug'],
            ['key' => 'site_domain', 'value' => 'news.muni.ac.ug'],
            ['key' => 'site_logo', 'value' => '/assets/images/muni-logo.png'],
            ['key' => 'primary_color', 'value' => '#8B0000'],
            ['key' => 'meta_title', 'value' => 'Muni University News & Media Portal'],
            ['key' => 'meta_description', 'value' => 'Transforming Lives - Official News Portal of Muni University'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
