<?php

/**
 * File Bahasa Inggris untuk Proses Instalasi CMS.
 * Dikelompokkan berdasarkan halaman, komponen, dan jenis pesan.
 */

return [
    //======================================================================
    // 1. Teks Umum & Navigasi
    //======================================================================

    'install_title' => 'CMS Company Installation',
    'language' => 'Language',
    'select_language' => 'Select Language',
    'english' => 'English',
    'indonesian' => 'Indonesian',

    // Tombol Aksi
    'back' => 'Back',
    'next' => 'Next',
    'continue' => 'Continue',
    'save' => 'Save',
    'finish' => 'Finish',
    'skip' => 'Skip',
    'install' => 'Install',
    'test_connection' => 'Test Connection',
    'test_email' => 'Test Email',
    'create_account' => 'Create Account',
    'finalize_installation' => 'Finalize Installation',

    // Status & Label Umum
    'enable' => 'Enable',
    'disable' => 'Disable',
    'version' => 'Version',
    'required' => 'Required',
    'supported' => 'Supported',
    'not_supported' => 'Not Supported',
    'writable' => 'Writable',
    'not_writable' => 'Not Writable',


    //======================================================================
    // 2. Langkah-langkah Instalasi (Steps)
    //======================================================================

    'step_welcome' => 'Welcome',
    'step_requirements' => 'Requirements',
    'step_database' => 'Database',
    'step_company' => 'Company',
    'step_admin' => 'Admin',
    'step_roles' => 'Roles',
    'step_features' => 'Features',
    'step_finish' => 'Finish',


    //======================================================================
    // 3. Halaman Instalasi
    //======================================================================

    // Halaman: Welcome
    'welcome_title' => 'Welcome to CMS Company Installation',
    'welcome_subtitle' => 'Thank you for choosing our CMS solution. This installer will guide you through the setup process.',
    'installation_process' => 'Installation Process',
    'get_started' => 'Get Started',
    'installation_time' => 'Installation Time',
    'installation_time_description' => 'This process usually takes 5-10 minutes to complete.',
    'preparation_note' => 'Make sure you have prepared your database credentials and company information.',

    // Halaman: Requirements
    'requirements_title' => 'System Requirements & Permissions',
    'requirements_subtitle' => 'Please ensure your server meets all requirements and has proper permissions.',
    'server_requirements' => 'Server Requirements',
    'software_type' => 'Software',
    'php_extensions' => 'Extensions',
    'folder_permissions' => 'Folder Permissions',
    'folder' => 'Folder',

    // Halaman: Database & Email
    'database_title' => 'Database & Email Configuration',
    'database_configuration' => 'Database Configuration',
    'database_connection' => 'Database Connection',
    'database_connection_type' => 'Database Connection Type',
    'database_host' => 'Database Host',
    'database_port' => 'Database Port',
    'database_name' => 'Database Name',
    'database_username' => 'Database Username',
    'database_password' => 'Database Password',
    'database_user' => 'Database User',
    'sqlite_help_text' => 'SQLite file will be automatically created in the storage/ directory',
    'email_configuration' => 'Email Configuration',
    'mail_driver' => 'Mail Driver',
    'mail_host' => 'Mail Host',
    'mail_port' => 'Mail Port',
    'mail_username' => 'Mail Username',
    'mail_password' => 'Mail Password',
    'mail_encryption' => 'Mail Encryption',
    'mail_from_address' => 'Mail From Address',
    'mail_from_name' => 'Mail From Name',
    'mail_from_name_description' => 'Mail sender name will be automatically set to your company name',
    'app_debug' => 'App Debug',
    'app_log_level' => 'Application Log Level',
    'app_url' => 'Application URL',
    'app_timezone' => 'Timezone',
    'app_locale' => 'Application Language',
    'example' => 'example',

    // Halaman: Company Profile
    'company_title' => 'Company Profile Configuration',
    'company_name' => 'Company Name',
    'company_email' => 'Company Email',
    'company_address' => 'Company Address',
    'company_description' => 'Company Description',
    'company_location_link' => 'Company Location Link',
    'company_logo' => 'Company Logo',
    'logo_requirements' => 'Logo requirements: PNG, JPG, or JPEG format, maximum 2MB',

    // Halaman: Super Admin
    'super_admin_title' => 'Super Admin Configuration',
    'super_admin_configuration' => 'Super Admin Account Configuration',
    'full_name' => 'Full Name',
    'email' => 'Email',
    'password' => 'Password',
    'password_confirmation' => 'Confirm Password',
    'include_dummy_data' => 'Include Dummy Data',
    'dummy_data_description' => 'Check this option if you want to populate the database with sample data for testing or demo purposes. Dummy data includes articles, products, galleries, and other content.',

    // Halaman: User Roles
    'roles_title' => 'User Roles & Permissions',
    'user_roles_list' => 'User List with Roles',
    'name' => 'Name',
    'role' => 'Role',
    'no_role' => 'No Role',
    'super_admin_created' => 'Super Admin account created successfully! You can login using email:',
    'dummy_password_info' => 'Default password for sample accounts is',
    'continue_to_features' => 'Continue to Feature Configuration',

    // Halaman: Features
    'features_title' => 'Feature Configuration',
    'features_subtitle' => 'Select features to be enabled and displayed on the website frontend.',
    'feature_status' => 'Status',

    // Halaman: Finish
    'finish_title' => 'Installation Complete!',
    'finish_subtitle' => 'Congratulations! Your CMS Company has been successfully installed.',
    'next_steps' => 'Next Steps',
    'next_steps_description' => 'Click the button below to complete the installation and start using your CMS.',
    'support' => 'Get Support',
    'database_configured' => 'Database Configured',
    'database_ready' => 'Database connection established and ready',
    'admin_created' => 'Admin Account Created',
    'admin_ready' => 'Super admin account is set up',
    'system_configured' => 'System Configured',
    'system_ready' => 'All system features configured',

    // Deskripsi Fitur pada Halaman Welcome (dipisah agar lebih rapi)
    'features' => [
        'requirements' => [
            'title' => 'System Requirements',
            'description' => 'Check server requirements and file permissions'
        ],
        'database' => [
            'title' => 'Database Configuration',
            'description' => 'Configure your database connection and email settings'
        ],
        'company' => [
            'title' => 'Company Profile',
            'description' => 'Set up your company information and branding'
        ],
        'admin' => [
            'title' => 'Admin Account',
            'description' => 'Create your super admin account'
        ],
        'roles' => [
            'title' => 'User Roles',
            'description' => 'Review and configure user roles and permissions'
        ],
        'features' => [
            'title' => 'Feature Configuration',
            'description' => 'Enable or disable system features'
        ],
        'complete' => [
            'title' => 'Installation Complete',
            'description' => 'Finalize your installation and start using the system'
        ]
    ],


    //======================================================================
    // 4. Pesan Sistem (Alerts, Messages, Feedback)
    //======================================================================

    // Pesan Status Umum
    'success' => 'Success!',
    'error' => 'Error!',
    'warning' => 'Warning!',
    'info' => 'Information',
    'loading' => 'Loading...',
    'please_wait' => 'Please wait...',
    'testing' => 'Testing...',
    'processing' => 'Processing...',

    // Pesan Feedback Aksi Pengguna
    'company_profile_saved' => 'Company profile successfully saved.',
    'company_profile_save_error' => 'Failed to save company profile',
    'feature_toggles_saved' => 'Feature toggles successfully saved.',
    'feature_toggles_save_error' => 'Failed to save feature toggles',

    // Pesan Koneksi & AJAX
    'connection_error' => 'Connection Error!',
    'could_not_test_database' => 'Could not test database connection.',
    'email_test_success' => 'Email Test Success!',
    'email_test_failed' => 'Email Test Failed!',
    'email_test_error' => 'Email Test Error!',
    'could_not_test_email' => 'Could not test email configuration.',
    'server_communication_error' => 'An error occurred while communicating with the server. Please check your connection and try again.',
    'database_connection_error' => 'Database Connection Error!',
    'please_make_sure' => 'Please make sure',
    'database_server_running' => 'Your database server is running',
    'database_exists' => 'The database exists',
    'credentials_correct' => 'Your username and password are correct',
    'user_has_permissions' => 'The user has proper permissions on the database',

    // Pesan Khusus (Logika Super Admin)
    'super_admin_exists' => 'Super Admin with email :email already exists and has super admin access. You can continue.',
    'user_exists_role_assigned' => 'User with email :email already exists. Super Admin role has been assigned to that account.',
    'super_admin_created_msg' => 'Super Admin successfully created.',
    'failed_assign_role' => 'Failed to assign Super Admin role',

];