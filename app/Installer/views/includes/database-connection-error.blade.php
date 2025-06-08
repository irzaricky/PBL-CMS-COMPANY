@if(session('db_connection_error') || session('database_error') || $errors->has('database_connection') || $errors->has('database_fields') || $errors->has('save_error'))
    <div class="alert alert-danger">
        <strong>Database Connection Error!</strong>
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
        <p>Please make sure:</p>
        <ul>
            <li>Your database server is running</li>
            <li>The database exists</li>
            <li>Your username and password are correct</li>
            <li>The user has proper permissions on the database</li>
        </ul>
    </div>
@endif