<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $customerService = Role::firstOrCreate(['name' => 'customer_service']);
        $contentModerator = Role::firstOrCreate(['name' => 'content_moderator']);
        $director = Role::firstOrCreate(['name' => 'director']);

        // Create permissions by category
        // User Management
        $viewUsers = Permission::firstOrCreate(['name' => 'view users']);
        $createUsers = Permission::firstOrCreate(['name' => 'create users']);
        $editUsers = Permission::firstOrCreate(['name' => 'edit users']);
        $deleteUsers = Permission::firstOrCreate(['name' => 'delete users']);
        $manageRoles = Permission::firstOrCreate(['name' => 'manage roles']);

        // Content Management
        $viewContent = Permission::firstOrCreate(['name' => 'view content']);
        $createContent = Permission::firstOrCreate(['name' => 'create content']);
        $editContent = Permission::firstOrCreate(['name' => 'edit content']);
        $deleteContent = Permission::firstOrCreate(['name' => 'delete content']);
        $publishContent = Permission::firstOrCreate(['name' => 'publish content']);
        $manageCategories = Permission::firstOrCreate(['name' => 'manage categories']);

        // Feedback Management
        $viewFeedback = Permission::firstOrCreate(['name' => 'view feedback']);
        $respondFeedback = Permission::firstOrCreate(['name' => 'respond feedback']);
        $deleteFeedback = Permission::firstOrCreate(['name' => 'delete feedback']);

        // Company Profile Management
        $viewCompanyProfile = Permission::firstOrCreate(['name' => 'view company profile']);
        $editCompanyProfile = Permission::firstOrCreate(['name' => 'edit company profile']);
        $manageOrganizationStructure = Permission::firstOrCreate(['name' => 'manage organization structure']);
        $manageMitra = Permission::firstOrCreate(['name' => 'manage mitra']);

        // Analytics
        $viewAnalytics = Permission::firstOrCreate(['name' => 'view analytics']);

        // Assign permissions to roles
        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());

        // Customer Service permissions
        $customerService->givePermissionTo([
            'view feedback',
            'respond feedback',
            'view content',
            'view users'
        ]);

        // Content Moderator permissions
        $contentModerator->givePermissionTo([
            'view content',
            'create content',
            'edit content',
            'publish content',
            'manage categories',
            'view feedback'
        ]);

        // Director permissions
        $director->givePermissionTo([
            'view company profile',
            'edit company profile',
            'manage organization structure',
            'manage mitra',
            'view analytics',
            'view users',
            'view content'
        ]);
    }
}