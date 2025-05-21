@section('title', 'Database Import')
@extends('InstallerEragViews::app-layout')
@section('content')

    <section class="mt-4">
        <div class="container">
            @include('InstallerEragViews::includes.database-connection-error')

            @if ($errors->any() && !$errors->has('database_connection') && !$errors->has('database_fields') && !$errors->has('save_error'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('saveWizard') }}" method="post" class="card" id="database-form">
                @csrf
                <div class="card-body">
                    <div class="tab">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <x-install-input label="App Name" required="true" name="app_name" type="text"
                                    value="{{ old('app_name') }}" />
                                <x-install-error for="app_name" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-install-select label="App Environment" class="form-control" required="true"
                                    name="environment">
                                    <option value="">--Select--</option>
                                    <option value="local" selected>Local</option>
                                    <option value="development">Development</option>
                                    <option value="qa">Qa</option>
                                    <option value="production">Production</option>
                                    <option value="other">Other</option>
                                </x-install-select>
                            </div>

                            <div class="col-md-4 mt-4">
                                <div class="form-group">
                                    <label for="app_debug" class="mr-8">
                                        App Debug
                                    </label>
                                    <label for="app_debug_true">
                                        <input type="radio" name="app_debug" id="app_debug_true" value="true" {{ old('app_debug', 'true') == 'true' ? 'checked' : '' }}>
                                        True
                                    </label>
                                    <label for="app_debug_false">
                                        <input type="radio" name="app_debug" id="app_debug_false" value="false" {{ old('app_debug') == 'false' ? 'checked' : '' }}>
                                        False
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-install-select label="App Log Level" class="form-control" required="true"
                                    name="app_log_level">
                                    <option value="debug" {{ old('app_log_level', 'debug') == 'debug' ? 'selected' : '' }}>
                                        debug</option>
                                    <option value="info">info</option>
                                    <option value="notice">notice</option>
                                    <option value="warning">warning</option>
                                    <option value="error">error</option>
                                    <option value="critical">critical</option>
                                    <option value="alert">alert</option>
                                    <option value="emergency">emergency</option>
                                </x-install-select>
                            </div>

                            @php
                                $isHttps = app('request')->isSecure();
                                $protocol = $isHttps ? 'https://' : 'http://';
                                $base_url = $protocol . app('request')->getHttpHost();
                            @endphp

                            <div class="col-md-4 mb-3">
                                <x-install-input label="App Url" required="true" name="app_url" type="url"
                                    value="{{ old('app_url', $base_url) }}" />
                                <x-install-error for="app_url" />
                            </div>

                            <div class="col-md-4 mb-3">
                                @component('InstallerEragViews::components.timezone-select', [
                                    'label' => 'Timezone',
                                    'required' => true,
                                    'name' => 'app_timezone'
                                ])
                                @endcomponent
                                <x-install-error for="app_timezone" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-install-select label="App Locale" class="form-control" required="true" name="app_locale">
                                    <option value="en" {{ old('app_locale', 'en') == 'en' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="id" {{ old('app_locale') == 'id' ? 'selected' : '' }}>Indonesian</option>
                                </x-install-select>
                                <x-install-error for="app_locale" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-install-select label="Database Connection" class="form-control" required="true"
                                    name="database_connection" id="database_connection">
                                    <option value="mysql" selected>MySQL</option>
                                    <option value="sqlite" {{ old('database_connection') == 'sqlite' ? 'selected' : '' }}>
                                        SQLite</option>
                                </x-install-select>
                            </div>

                            <!-- Database Name field (always shown) -->
                            <div class="col-md-4 mb-3" id="database_name_container">
                                <x-install-input label="Database Name" required="true" name="database_name" type="text"
                                    value="{{ old('database_name') }}" />
                                <x-install-error for="database_name" />
                                <div class="text-muted small mt-1" id="sqlite_help_text" style="display: none;">
                                    File SQLite akan otomatis dibuat di direktori <code>storage/</code> (contoh:
                                    <code>mydb.sqlite</code>)
                                </div>
                            </div>

                            <!-- MySQL specific fields -->
                            <div class="mysql-only col-md-4 mb-3" id="database_host_container">
                                <x-install-input label="Database Host" required="false" name="database_hostname" type="text"
                                    value="{{ old('database_hostname', '127.0.0.1') }}" />
                                <x-install-error for="database_hostname" />
                            </div>

                            <div class="mysql-only col-md-4 mb-3" id="database_port_container">
                                <x-install-input label="Database Port" required="false" name="database_port" type="text"
                                    value="{{ old('database_port', '3306') }}" />
                                <x-install-error for="database_port" />
                            </div>

                            <div class="mysql-only col-md-4 mb-3" id="database_user_container">
                                <x-install-input label="Database User Name" required="false" name="database_username"
                                    type="text" value="{{ old('database_username') }}" />
                                <x-install-error for="database_username" />
                            </div>

                            <div class="mysql-only col-md-4 mb-3" id="database_password_container">
                                <x-install-input label="Database Password" name="database_password" type="text"
                                    value="{{ old('database_password') }}" />
                                <x-install-error for="database_password" />
                            </div>

                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <div class="d-flex">
                        <button type="button" id="test_connection_button" class="btn btn-secondary me-2">Test
                            Connection</button>
                        <button type="submit" id="next_button" class="btn btn-primary ms-auto">Next</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        /**
         * Database Import Page JavaScript
         * 
         * Fitur:
         * 1. Mengatur tampilan form berdasarkan jenis database yang dipilih (MySQL atau SQLite)
         * 2. Menangani tombol Test Connection untuk menguji koneksi database
         * 3. Menangani submisi form dengan AJAX untuk validasi dan memproses error
         */
        document.addEventListener('DOMContentLoaded', function () {
            // Get form element and important elements
            const form = document.getElementById('database-form');
            if (!form) {
                console.error("Form with ID 'database-form' not found");
                return;
            }

            const dbConnectionSelect = document.getElementById('database_connection');
            const mysqlOnlyFields = document.querySelectorAll('.mysql-only');
            const sqliteHelpText = document.getElementById('sqlite_help_text');

            // Function to toggle form fields based on database selection
            function toggleDatabaseFields() {
                const selectedConnection = dbConnectionSelect.value;

                if (selectedConnection === 'sqlite') {
                    // Hide MySQL fields
                    mysqlOnlyFields.forEach(field => {
                        field.style.display = 'none';
                    });

                    // Remove required attribute from MySQL fields
                    document.querySelectorAll('.mysql-only input').forEach(input => {
                        // Store the original required state if needed for MySQL
                        if (input.hasAttribute('required')) {
                            input.setAttribute('data-was-required', 'true');
                            input.removeAttribute('required');
                        }
                    });

                    // Move database_name field to take up more space
                    const dbNameContainer = document.getElementById('database_name_container');
                    if (dbNameContainer) {
                        dbNameContainer.className = 'col-md-8 mb-3';

                        // Show SQLite help text within the database_name_container
                        if (sqliteHelpText) {
                            sqliteHelpText.style.display = 'block';
                        }
                    }
                } else {
                    // Show MySQL fields
                    mysqlOnlyFields.forEach(field => {
                        field.style.display = 'block';
                    });

                    // Add required attribute to MySQL fields that need it
                    const hostnameInput = document.querySelector('input[name="database_hostname"]');
                    const portInput = document.querySelector('input[name="database_port"]');
                    const usernameInput = document.querySelector('input[name="database_username"]');

                    if (hostnameInput) hostnameInput.setAttribute('required', 'required');
                    if (portInput) portInput.setAttribute('required', 'required');
                    if (usernameInput) usernameInput.setAttribute('required', 'required');

                    // Reset database_name field size
                    const dbNameContainer = document.getElementById('database_name_container');
                    if (dbNameContainer) {
                        dbNameContainer.className = 'col-md-4 mb-3';

                        // Hide SQLite help text
                        if (sqliteHelpText) {
                            sqliteHelpText.style.display = 'none';
                        }
                    }
                }
            }            // Add event listener to database connection dropdown
            dbConnectionSelect.addEventListener('change', toggleDatabaseFields);

            // Run toggle on page load
            toggleDatabaseFields();

            // Set initial required attributes based on selected connection
            document.addEventListener('DOMContentLoaded', function () {
                // Run once to set required attributes
                toggleDatabaseFields();
            });

            // Add test connection functionality
            const testConnectionBtn = document.getElementById('test_connection_button');
            if (testConnectionBtn) {
                testConnectionBtn.addEventListener('click', function () {
                    // Clear any previous messages
                    const previousMessages = document.querySelectorAll('.alert');
                    previousMessages.forEach(msg => msg.remove());

                    // Disable test button
                    testConnectionBtn.disabled = true;
                    testConnectionBtn.innerHTML = 'Testing...';

                    // Collect form data
                    const formData = new FormData(form);

                    fetch('{{ route("test_database_connection") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Enable the button again
                            testConnectionBtn.disabled = false;
                            testConnectionBtn.innerHTML = 'Test Connection';

                            // Create message div
                            const messageDiv = document.createElement('div');
                            messageDiv.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                            messageDiv.innerHTML = '<strong>' + (data.success ? 'Success!' : 'Error!') + '</strong> ' + data.message;

                            // Add message at the top of the form inside the container
                            const container = document.querySelector('.container');
                            if (container) {
                                const firstChild = container.querySelector(':first-child');
                                if (firstChild) {
                                    container.insertBefore(messageDiv, firstChild);
                                } else {
                                    container.appendChild(messageDiv);
                                }
                            }

                            // Scroll to top to show message
                            window.scrollTo(0, 0);
                        })
                        .catch(error => {
                            // Enable the button again
                            testConnectionBtn.disabled = false;
                            testConnectionBtn.innerHTML = 'Test Connection';

                            // Create error message
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'alert alert-danger';
                            errorDiv.innerHTML = '<strong>Connection Error!</strong> Could not test database connection.';

                            // Add error at the top of the form inside the container
                            const container = document.querySelector('.container');
                            if (container) {
                                const firstChild = container.querySelector(':first-child');
                                if (firstChild) {
                                    container.insertBefore(errorDiv, firstChild);
                                } else {
                                    container.appendChild(errorDiv);
                                }
                            }

                            // Scroll to top to show error
                            window.scrollTo(0, 0);

                            console.error('Error testing connection:', error);
                        });
                });
            }

            // Function to clear previous errors
            function clearPreviousErrors() {
                const previousErrors = document.querySelectorAll('.alert.alert-danger');
                previousErrors.forEach(error => error.remove());
            }

            // Function to prepare the form for submission - no longer need to touch required attributes
            function prepareFormForSubmission() {
                // Nothing special needed - the toggleDatabaseFields function handles required attributes
                // Just add default values for hidden MySQL fields when SQLite is selected
                if (dbConnectionSelect.value === 'sqlite') {
                    const hostnameInput = document.querySelector('input[name="database_hostname"]');
                    const portInput = document.querySelector('input[name="database_port"]');
                    const usernameInput = document.querySelector('input[name="database_username"]');
                    const databaseInput = document.querySelector('input[name="database_name"]');

                    // Make sure SQLite databases have .sqlite extension
                    if (databaseInput && databaseInput.value && !databaseInput.value.toLowerCase().endsWith('.sqlite')) {
                        databaseInput.value = databaseInput.value + '.sqlite';
                    }

                    if (hostnameInput && !hostnameInput.value) hostnameInput.value = '127.0.0.1';
                    if (portInput && !portInput.value) portInput.value = '3306';
                    if (usernameInput && !usernameInput.value) usernameInput.value = 'sqlite';
                }
            }

            form.addEventListener('submit', function (e) {
                // Clear any previous error messages
                clearPreviousErrors();

                // Log form submission
                console.log('Form is being submitted');

                // Prepare the form before submission
                prepareFormForSubmission();

                // Get submit button and disable it
                const nextButton = document.getElementById('next_button');
                if (nextButton) {
                    nextButton.disabled = true;
                    nextButton.innerHTML = 'Processing...';
                }

                // Collect all form data
                const formData = new FormData(form);

                // If SQLite is selected, add default values for MySQL fields that are hidden
                if (dbConnectionSelect.value === 'sqlite') {
                    if (!formData.has('database_hostname') || formData.get('database_hostname') === '') {
                        formData.set('database_hostname', '127.0.0.1');
                    }
                    if (!formData.has('database_port') || formData.get('database_port') === '') {
                        formData.set('database_port', '3306');
                    }
                    if (!formData.has('database_username') || formData.get('database_username') === '') {
                        formData.set('database_username', 'sqlite');
                    }
                }

                const formDataObj = {};
                formData.forEach((value, key) => {
                    formDataObj[key] = value;
                });
                console.log('Form data:', formDataObj);

                // Submit via fetch API instead of normal form submission
                e.preventDefault();

                fetch('{{ route('saveWizard') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        // Store the response status for later use
                        const responseStatus = response.status;
                        return response.text().then(text => {
                            return { text, status: responseStatus };
                        });
                    })
                    .then(({ text: data, status }) => {
                        console.log('Response data:', data);
                        // Enable the submit button again
                        document.getElementById('next_button').disabled = false;

                        try {
                            const jsonData = JSON.parse(data);

                            // Handle error responses
                            if (!jsonData.success && status >= 400) {
                                console.error('Error response:', jsonData);

                                // Display error messages
                                if (jsonData.errors) {
                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'alert alert-danger';
                                    errorDiv.innerHTML = '<strong>Database Connection Error!</strong><ul>';

                                    // Process each error
                                    Object.keys(jsonData.errors).forEach(key => {
                                        const errorMessages = jsonData.errors[key];
                                        errorMessages.forEach(message => {
                                            errorDiv.innerHTML += `<li>${message}</li>`;
                                        });
                                    });

                                    errorDiv.innerHTML += '</ul><p>Please make sure:</p><ul>' +
                                        '<li>Your database server is running</li>' +
                                        '<li>The database exists</li>' +
                                        '<li>Your username and password are correct</li>' +
                                        '<li>The user has proper permissions on the database</li></ul>';

                                    // Add error div at the top of the form content
                                    const formCard = form.querySelector('.card-body');
                                    if (formCard) {
                                        formCard.insertAdjacentElement('afterbegin', errorDiv);
                                    } else {
                                        form.insertAdjacentElement('afterbegin', errorDiv);
                                    }

                                    // Scroll to top to show error
                                    window.scrollTo(0, 0);
                                    return;
                                }
                            }

                            // Handle success redirect
                            if (jsonData.redirect) {
                                window.location.href = jsonData.redirect;
                            } else if (jsonData.success) {
                                window.location.href = '{{ route('profil_perusahaan') }}';
                            }
                        } catch (e) {
                            console.log('Not a JSON response', e);

                            // If response is not JSON, check if it contains a redirect URL
                            if (data.includes('window.location') || data.includes('redirect')) {
                                // Try to extract URL
                                const match = data.match(/window\.location\.href\s*=\s*['"]([^'"]+)['"]/);
                                if (match) {
                                    window.location.href = match[1];
                                } else if (status < 400) { // Only redirect on success status
                                    window.location.href = '{{ route('profil_perusahaan') }}';
                                }
                            } else {
                                // Probably HTML response, replace current page
                                document.open();
                                document.write(data);
                                document.close();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Re-enable submit button
                        const nextButton = document.getElementById('next_button');
                        if (nextButton) {
                            nextButton.disabled = false;
                            nextButton.innerHTML = 'Next';
                        }

                        // Create error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger';
                        errorDiv.innerHTML = '<strong>Connection Error!</strong><p>An error occurred while communicating with the server. Please check your connection and try again.</p>';

                        // Add error message at top of form content
                        const formCard = form.querySelector('.card-body');
                        if (formCard) {
                            formCard.insertAdjacentElement('afterbegin', errorDiv);
                        } else {
                            form.insertAdjacentElement('afterbegin', errorDiv);
                        }

                        window.scrollTo(0, 0);
                    });
            });
        });
    </script>
@endsection