<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'super_administrator' => 'Super Administrator',
            'manager' => 'Manager',
            'accountant' => 'Accountant',
            'loan_officer' => 'Loan Officer',
            'member_service_officer' => 'Member Service Officer',
            'content_editor' => 'Content Editor',
        ];

        foreach ($roles as $slug => $name) {
            Role::query()->updateOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $permissions = [
            'admin.access' => 'Access administration',
            'contact_inquiries.manage' => 'Review contact inquiries',
            'members.view' => 'View member records',
            'members.update' => 'Update member records',
            'applications.view' => 'View membership applications',
            'applications.review' => 'Review membership applications',
            'staff.manage' => 'Manage staff roles and permissions',
            'savings.view' => 'View savings accounts',
            'savings.transactions.post' => 'Post savings transactions',
            'loans.view' => 'View loan records',
            'loans.review' => 'Review loan applications',
            'loans.approve' => 'Approve loans',
            'loans.disburse' => 'Disburse loans',
            'repayments.post' => 'Record loan repayments',
            'notices.manage' => 'Manage and publish notices',
            'website.manage' => 'Manage website content',
            'reports.view' => 'View reports',
            'reports.export' => 'Export reports',
        ];

        foreach ($permissions as $slug => $name) {
            Permission::query()->updateOrCreate(['slug' => $slug], ['name' => $name]);
        }
    }
}
