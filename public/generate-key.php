<?php

/**
 * Key Generator for Laravel
 * 
 * This script can be run directly to generate an application key
 * for Laravel when encountering the "No application encryption key has been specified" error.
 */

// Define the base path
$basePath = dirname(__DIR__);
$autoloadPath = $basePath . '/vendor/autoload.php';

// Check if dependencies are installed
if (!file_exists($autoloadPath)) {
    echo "<!DOCTYPE html>
    <html lang=\"en\">
    <head>
        <meta charset=\"utf-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <title>Application Key Generator</title>
        <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css\">
    </head>
    <body class=\"bg-light\">
        <div class=\"container py-5\">
            <div class=\"card shadow\">
                <div class=\"card-header bg-danger text-white\">
                    <h4>Dependencies Not Installed</h4>
                </div>
                <div class=\"card-body\">
                    <p>The vendor directory is missing. Please install dependencies first:</p>
                    <pre class=\"bg-light p-3\">composer install</pre>
                    <a href=\"./\" class=\"btn btn-primary mt-3\">Return to Installer</a>
                </div>
            </div>
        </div>
    </body>
    </html>";
    exit;
}

// Check for .env file
$envPath = $basePath . '/.env';
if (!file_exists($envPath)) {
    // Try to create it from .env.example
    if (file_exists($basePath . '/.env.example')) {
        copy($basePath . '/.env.example', $envPath);
        $envCreated = true;
    } else {
        // Create a minimal .env file
        $defaultEnv = "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n\n";
        $defaultEnv .= "DB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=laravel\nDB_USERNAME=root\nDB_PASSWORD=\n\n";
        file_put_contents($envPath, $defaultEnv);
        $envCreated = true;
    }
}

// Now that we have an .env file, read it
$env = file_get_contents($envPath);

// Check if APP_KEY is empty
$keyGenerated = false;
if (preg_match('/APP_KEY=(.*)/', $env, $matches)) {
    if (empty(trim($matches[1]))) {
        // Generate a new key
        $key = 'base64:' . base64_encode(random_bytes(32));

        // Replace the APP_KEY line in the .env file
        $env = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $env);
        file_put_contents($envPath, $env);
        $keyGenerated = true;
    }
} else {
    // If APP_KEY line doesn't exist, add it
    $key = 'base64:' . base64_encode(random_bytes(32));
    $env .= "\nAPP_KEY=" . $key;
    file_put_contents($envPath, $env);
    $keyGenerated = true;
}

// Display the result
echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <title>Application Key Generator</title>
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css\">
</head>
<body class=\"bg-light\">
    <div class=\"container py-5\">
        <div class=\"card shadow\">
            <div class=\"card-header bg-success text-white\">
                <h4>Key Generation Status</h4>
            </div>
            <div class=\"card-body\">";

if (isset($envCreated) && $envCreated) {
    echo "<div class=\"alert alert-info\">
              <strong>Info:</strong> Created .env file successfully.
          </div>";
}

if ($keyGenerated) {
    echo "<div class=\"alert alert-success\">
              <strong>Success:</strong> Application key has been generated successfully!
          </div>";
} else {
    echo "<div class=\"alert alert-warning\">
              <strong>Info:</strong> Application key is already set. No action taken.
          </div>";
}

echo "      <p class=\"mt-3\">You can now continue with the installation:</p>
            <a href=\"./\" class=\"btn btn-primary\">Continue Installation</a>
            </div>
        </div>
    </div>
</body>
</html>";
echo "from your project's root directory after dependencies are installed.\n";
echo "------------------------------------------------\n";
