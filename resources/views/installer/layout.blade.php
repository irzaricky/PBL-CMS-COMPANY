<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Installer</title>

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

        .step-indicator {
            display: flex;
            margin-bottom: 30px;
            justify-content: space-between;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-bottom: 3px solid #e9ecef;
            color: #6c757d;
            font-weight: 500;
        }

        .step.active {
            border-bottom-color: #4338ca;
            color: #4338ca;
        }

        .step.completed {
            border-bottom-color: #10b981;
            color: #10b981;
        }

        .btn-primary {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        .btn-primary:hover {
            background-color: #3730a3;
            border-color: #3730a3;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        .requirement-item,
        .permission-item {
            padding: 10px;
            background-color: #f8f9fa;
            margin-bottom: 8px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .requirement-item i,
        .permission-item i {
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="installer-container">
        <div class="installer-header">
            <h1>{{ config('app.name', 'Laravel') }} Installer</h1>
        </div>

        <div class="installer-body">
            <div class="step-indicator">
                <div
                    class="step {{ request()->routeIs('installer.welcome') ? 'active' : (request()->route()->getName() > 'installer.welcome' ? 'completed' : '') }}">
                    Welcome
                </div>
                <div
                    class="step {{ request()->routeIs('installer.requirements') ? 'active' : (request()->route()->getName() > 'installer.requirements' ? 'completed' : '') }}">
                    Requirements
                </div>
                <div
                    class="step {{ request()->routeIs('installer.permissions') ? 'active' : (request()->route()->getName() > 'installer.permissions' ? 'completed' : '') }}">
                    Permissions
                </div>
                <div
                    class="step {{ request()->routeIs('installer.environment') ? 'active' : (request()->route()->getName() > 'installer.environment' ? 'completed' : '') }}">
                    Environment
                </div>
                <div
                    class="step {{ request()->routeIs('installer.database') ? 'active' : (request()->route()->getName() > 'installer.database' ? 'completed' : '') }}">
                    Database
                </div>
                <div
                    class="step {{ request()->routeIs('installer.admin') ? 'active' : (request()->route()->getName() > 'installer.admin' ? 'completed' : '') }}">
                    Admin User
                </div>
                <div
                    class="step {{ request()->routeIs('installer.company') ? 'active' : (request()->route()->getName() > 'installer.company' ? 'completed' : '') }}">
                    Company Profile
                </div>
                <div class="step {{ request()->routeIs('installer.final') ? 'active' : '' }}">
                    Finish
                </div>
            </div>

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a33530bb41.js" crossorigin="anonymous"></script>
    @yield('scripts')
</body>

</html>