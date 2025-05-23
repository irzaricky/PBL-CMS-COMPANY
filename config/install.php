<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    */

    'colors' => [
        'primary' => '#EB4432',
        'secondary' => '#f85c58',
    ],

    'install_title' => 'CMS Company Installation',

    //
    'core' => [
        'minPhpVersion' => '8.2.0',
    ],
    'requirements' => [
        'php' => [
            'openssl',
            'pdo',
            'mbstring',
            'gd',
            'tokenizer',
            'JSON',
            'cURL',
            'fileinfo',
            'xml',
            'ctype',
            'bcmath',
            'dom',
            'filter',
            'session',
            'zip',
            'intl',
        ],
        'apache' => [
            'mod_rewrite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        'storage/framework/' => '775',
        'storage/logs/' => '775',
        'bootstrap/cache/' => '775',
    ],

    // environment

    'environment' => [
        'form' => [
            'rules' => [
                'environment' => 'required|string|max:50',
                'environment_custom' => 'required_if:environment,other|max:50',
                'app_debug' => 'required|string',
                'app_log_level' => 'required|string|max:50',
                'app_url' => 'required|url',
                'app_locale' => 'required|string|in:en,id',
                'database_connection' => 'required|in:mysql,sqlite',
                'database_hostname' => 'nullable|string|max:50',
                'database_port' => 'nullable|numeric',
                'database_name' => 'required|string|max:50',
                'database_username' => 'nullable|string|max:50',
                'database_password' => 'nullable|string|max:50',
            ],
        ],
    ],

    'env' => 'BROADCAST_DRIVER=log' . "\n" .
        'CACHE_DRIVER=file' . "\n" .
        'FILESYSTEM_DISK=local' . "\n" .
        'QUEUE_CONNECTION=sync' . "\n" .
        'SESSION_DRIVER=file' . "\n" .
        'SESSION_LIFETIME=120' . "\n\n" .
        'MEMCACHED_HOST=127.0.0.1' . "\n\n" .
        'REDIS_HOST=127.0.0.1' . "\n" .
        'REDIS_PASSWORD=null' . "\n" .
        'REDIS_PORT=6379' . "\n\n" .
        'MAIL_MAILER=smtp' . "\n" .
        'MAIL_HOST=mailpit' . "\n" .
        'MAIL_PORT=1025' . "\n" .
        'MAIL_USERNAME=null' . "\n" .
        'MAIL_PASSWORD=null' . "\n" .
        'MAIL_ENCRYPTION=null' . "\n" .
        'MAIL_FROM_ADDRESS="hello@example.com"' . "\n" .
        'MAIL_FROM_NAME="${APP_NAME}"' . "\n\n" .
        'AWS_ACCESS_KEY_ID=' . "\n" .
        'AWS_SECRET_ACCESS_KEY=' . "\n" .
        'AWS_DEFAULT_REGION=us-east-1' . "\n" .
        'AWS_BUCKET=' . "\n" .
        'AWS_USE_PATH_STYLE_ENDPOINT=false' . "\n\n" .
        'PUSHER_APP_ID=' . "\n" .
        'PUSHER_APP_KEY=' . "\n" .
        'PUSHER_APP_SECRET=' . "\n" .
        'PUSHER_HOST=' . "\n" .
        'PUSHER_PORT=443' . "\n" .
        'PUSHER_SCHEME=https' . "\n" .
        'PUSHER_APP_CLUSTER=mt1' . "\n\n" .
        'VITE_APP_NAME="${APP_NAME}"' . "\n" .
        'VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"' . "\n" .
        'VITE_PUSHER_HOST="${PUSHER_HOST}"' . "\n" .
        'VITE_PUSHER_PORT="${PUSHER_PORT}"' . "\n" .
        'VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"' . "\n" .
        'VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"',

    // profil perusahaan

    'profil_perusahaan' => [
        'nama_perusahaan' => 'required|string|max:100',
        'logo_perusahaan' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'deskripsi_perusahaan' => 'nullable|string',
        'alamat_perusahaan' => 'required|string|max:200',
        'link_alamat_perusahaan' => 'nullable|string|max:255',
        'email_perusahaan' => 'required|email|max:50',
    ],

    // feature toggles 
    'feature_toggles' => [
        'features' => 'nullable|array',
        'features.*' => 'boolean',
    ],

    // super admin
    'super_admin' => [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:8|confirmed',
    ],
];
