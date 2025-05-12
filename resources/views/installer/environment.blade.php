@extends('installer.layout')

@section('content') <div class="text-center mb-4">
        <h2>Database Configuration</h2>
        <p>Connect your website to a database for storing content.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Database Setup:</strong> The installer will attempt to create the database if it doesn't exist. Make
                sure the database user has sufficient privileges.
            </div>

            <div class="alert alert-warning mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important:</strong> Double-check your database details carefully. Incorrect information can cause
                installation errors.
            </div>

            <form method="POST" action="{{ route('installer.environment.save') }}">
                @csrf

                <h5 class="card-title mb-4">Website Settings</h5>
                <div class="mb-3">
                    <label for="app_name" class="form-label">Website Name</label>
                    <input type="text" class="form-control" id="app_name" name="app_name"
                        value="{{ old('app_name', 'Company CMS Website') }}" required>
                    <div class="form-text">This can be changed later in your company profile.</div>
                </div>

                <div class="mb-3">
                    <label for="app_url" class="form-label">Website URL</label>
                    <input type="url" class="form-control" id="app_url" name="app_url"
                        value="{{ old('app_url', url('/')) }}" required>
                    <div class="form-text">The URL where your website will be accessible.</div>
                </div>

                <h5 class="card-title mb-4 mt-5">Database Connection</h5>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    The database information below is required to continue.
                </div>
                <div class="mb-3">
                    <label for="db_connection" class="form-label">Database Connection</label>
                    <select class="form-select" id="db_connection" name="db_connection" required>
                        <option value="mysql" {{ old('db_connection') == 'mysql' ? 'selected' : '' }}>MySQL</option>
                        <option value="pgsql" {{ old('db_connection') == 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
                        <option value="sqlite" {{ old('db_connection') == 'sqlite' ? 'selected' : '' }}>SQLite</option>
                        <option value="sqlsrv" {{ old('db_connection') == 'sqlsrv' ? 'selected' : '' }}>SQL Server</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="db_host" class="form-label">Database Host</label>
                    <input type="text" class="form-control" id="db_host" name="db_host"
                        value="{{ old('db_host', '127.0.0.1') }}" required>
                </div>

                <div class="mb-3">
                    <label for="db_port" class="form-label">Database Port</label>
                    <input type="number" class="form-control" id="db_port" name="db_port"
                        value="{{ old('db_port', '3306') }}" required>
                </div>

                <div class="mb-3">
                    <label for="db_database" class="form-label">Database Name</label>
                    <input type="text" class="form-control" id="db_database" name="db_database"
                        value="{{ old('db_database') }}" required>
                    <div class="form-text">Make sure this database already exists.</div>
                </div>

                <div class="mb-3">
                    <label for="db_username" class="form-label">Database Username</label>
                    <input type="text" class="form-control" id="db_username" name="db_username"
                        value="{{ old('db_username') }}" required>
                </div>

                <div class="mb-3">
                    <label for="db_password" class="form-label">Database Password</label>
                    <input type="password" class="form-control" id="db_password" name="db_password"
                        value="{{ old('db_password') }}">
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('installer.permissions') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Previous
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Next <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection