@if(session('db_connection_error') || session('database_error') || $errors->has('database_connection') || $errors->has('database_fields') || $errors->has('save_error'))
    <div class="alert alert-danger">
        <strong>{{ __('installer.database_connection_error') }}</strong>
        <ul>
            @if(session('db_connection_error'))
                <li>{{ session('db_connection_error') }}</li>
            @endif
            @if(session('database_error'))
                <li>{{ session('database_error') }}</li>
            @endif
            @if($errors->has('database_connection'))
                <li>{{ $errors->first('database_connection') }}</li>
            @endif
            @if($errors->has('database_fields'))
                <li>{{ $errors->first('database_fields') }}</li>
            @endif
            @if($errors->has('save_error'))
                <li>{{ $errors->first('save_error') }}</li>
            @endif
        </ul>
        <p>{{ __('installer.please_make_sure') }}:</p>
        <ul>
            <li>{{ __('installer.database_server_running') }}</li>
            <li>{{ __('installer.database_exists') }}</li>
            <li>{{ __('installer.credentials_correct') }}</li>
            <li>{{ __('installer.user_has_permissions') }}</li>
        </ul>
    </div>
@endif