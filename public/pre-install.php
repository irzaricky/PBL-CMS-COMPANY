<?php

/**
 * Pre-installation checker for Laravel
 * This file should be included in index.php to handle installations
 * where the vendor folder doesn't exist yet.
 */

// Define the base path
$basePath = dirname(__DIR__);

// Check if composer.json exists (this is a Laravel project)
if (!file_exists($basePath . '/composer.json')) {
    die('Error: This does not appear to be a valid Laravel installation. composer.json is missing.');
}

// Check if the vendor directory exists
if (!file_exists($basePath . '/vendor')) {
    // Directory doesn't exist at all
    if (!mkdir($basePath . '/vendor', 0755, true) && !is_dir($basePath . '/vendor')) {
        die('Error: Unable to create vendor directory. Please check permissions.');
    }
}

// Check if the autoload file exists
if (!file_exists($basePath . '/vendor/autoload.php')) {
    // HTML for a simple installer page
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Installation</title>

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

        <style>
            body {
                background-color: #f8f9fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                padding: 20px;
            }

            .installer-container {
                max-width: 800px;
                margin: 50px auto;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .installer-header {
                background: #4338ca;
                color: white;
                padding: 20px;
                text-align: center;
            }

            .installer-body {
                padding: 30px;
            }

            pre {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 5px;
                overflow-x: auto;
            }

            .btn-primary {
                background-color: #4338ca;
                border-color: #4338ca;
            }
        </style>
    </head>

    <body>
        <div class="installer-container">
            <div class="installer-header">
                <h1>Laravel Installation</h1>
            </div>

            <div class="installer-body">
                <div class="alert alert-warning">
                    <strong>Missing Dependencies:</strong> The vendor/autoload.php file is missing.
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Installation Instructions</h5>
                        <p>It appears that Composer dependencies have not been installed. Please run the following commands
                            from the root directory of your application:</p>

                        <h6 class="mt-4">Step 1: Install Composer (if not installed)</h6>
                        <p>Visit <a href="https://getcomposer.org/download/"
                                target="_blank">https://getcomposer.org/download/</a> for installation instructions.</p>
                        <h6 class="mt-4">Step 2: Install dependencies</h6>
                        <pre><code>cd <?php echo htmlspecialchars($basePath); ?>

            # For development
            composer install

            # For production
            composer install --no-dev --optimize-autoloader</code></pre>
                        <h6 class="mt-4">Step 3: Generate application key and create .env file</h6>
                        <p>After installing dependencies, create a .env file by copying .env.example:</p>
                        <pre><code>cp .env.example .env</code></pre>
                        <p>Then generate an application key:</p>
                        <pre><code>php artisan key:generate --force</code></pre>
                        <p>Alternatively, you can access <a href="generate-key.php">generate-key.php</a> after installing
                            dependencies to create a key automatically.</p>

                        <h6 class="mt-4">Step 4: After completing the steps above</h6>
                        <p>Once dependencies are installed and the key is generated, refresh this page to continue with the
                            installation.</p>
                    </div>
                </div>

                <div class="alert alert-info">
                    <strong>Need help?</strong> If you're having trouble installing dependencies, consult the <a
                        href="https://laravel.com/docs/installation" target="_blank">Laravel documentation</a>.
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
    exit;
}
