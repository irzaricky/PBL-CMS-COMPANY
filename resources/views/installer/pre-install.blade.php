<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Installer - Dependencies</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        .btn-primary {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        .btn-primary:hover {
            background-color: #3730a3;
            border-color: #3730a3;
        }

        .text-danger {
            color: #ef4444 !important;
        }
    </style>
</head>

<body>
    <div class="installer-container">
        <div class="installer-header">
            <h1>Laravel Installer - Dependencies</h1>
        </div>

        <div class="installer-body">
            <div class="text-center mb-4">
                <h2>Missing Dependencies</h2>
                <p>The application requires Composer dependencies to be installed before proceeding.</p>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> The vendor/autoload.php file is missing. This indicates that Composer
                        dependencies have not been installed.
                    </div>

                    <p>There are two ways to resolve this issue:</p>

                    <h5 class="mt-4">Option 1: Install manually (recommended)</h5>
                    <p>Run the following command in your terminal from the root directory of your application:</p>
                    <pre class="bg-light p-3 rounded"><code>composer install</code></pre>

                    <h5 class="mt-4">Option 2: Automatic installation</h5>
                    <p>The installer can attempt to install dependencies for you. This requires that you have Composer
                        installed on your server.</p>

                    <div class="d-grid gap-2 mt-4">
                        <a href="?install_dependencies=1" class="btn btn-primary btn-lg">
                            Install Dependencies
                        </a>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
</body>

</html>