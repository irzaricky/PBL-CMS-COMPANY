<!doctype html>
<html lang="{{ str_replace('_', '-', $lang ?? app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $primaryColor = config('install.colors.primary');
        $secondaryColor = config('install.colors.secondary');
        $boxRgba = config('install.colors.boxRgba');
    @endphp
    <style>
        :root {
            --primary-color:
                {{ $primaryColor }}
            ;
            --secondary-color:
                {{ $secondaryColor }}
            ;
        }
    </style>
    <link href="{{ asset('install/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('install/style.css') }}" rel="stylesheet">
    <link href="{{ asset('install/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('install/progress-steps.css') }}" rel="stylesheet">
</head>

<body>
    @include('InstallerEragViews::step')
    @yield('content')

    <script src="{{ asset('install/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password visibility toggle
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    // Toggle the type attribute
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });
        });
    </script>
</body>

</html>