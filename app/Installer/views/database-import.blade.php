@section('title', __('installer.database_title'))
@extends('InstallerEragViews::app-layout')
@section('content')

    <section class="mt-4 installer-content">
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
            <form action="{{ route('saveWizard') }}" method="post" id="database-form">
                @csrf
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('installer.database_configuration') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <x-install-select label="{{ __('installer.database_connection') }}" class="form-control"
                                    required="true" name="environment">
                                    <option value="production" selected>Production</option>
                                    <option value="local">Local</option>
                                </x-install-select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <x-install-select label="{{ __('installer.app_debug') }}" class="form-control"
                                    required="true" name="app_debug">
                                    <option value="true" {{ old('app_debug') == 'true' ? 'selected' : '' }}>
                                        True</option>
                                    <option value="false" {{ old('app_debug', 'false') == 'false' ? 'selected' : '' }}>
                                        False</option>
                                </x-install-select>
                                <x-install-error for="app_debug" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-install-input label="{{ __('installer.app_log_level') }}" name="app_log_level"
                                    type="text" value="debug" readonly>
                                </x-install-input>
                            </div>

                            @php
                                $isHttps = app('request')->isSecure();
                                $protocol = $isHttps ? 'https://' : 'http://';
                                $base_url = $protocol . app('request')->getHttpHost();
                            @endphp

                            <div class="col-md-4 mb-3">
                                <x-install-input label="{{ __('installer.app_url') }}" required="true" name="app_url"
                                    type="url" value="{{ old('app_url', $base_url) }}" />
                                <x-install-error for="app_url" />
                            </div>

                            <div class="col-md-4 mb-3">
                                @component('InstallerEragViews::components.timezone-select', [
                                    'label' => __('installer.app_timezone'),
                                    'required' => true,
                                    'name' => 'app_timezone'
                                ])
                                @endcomponent
                                <x-install-error for="app_timezone" />
                            </div>

                            <div class="col-md-4 mb-3">
                                <x-install-select label="{{ __('installer.app_locale') }}" class="form-control"
                                    required="true" name="app_locale">
                                    <option value="en" {{ old('app_locale') == 'en' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="id" {{ old('app_locale', 'id') == 'id' ? 'selected' : '' }}>Indonesian
                                    </option>
                                </x-install-select>
                                <x-install-error for="app_locale" />
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card mb-4" id="db-config-card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('installer.database_configuration') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <x-install-select label="{{ __('installer.database_connection_type') }}"
                                    class="form-control" required="true" name="database_connection"
                                    id="database_connection">
                                    <option value="mysql" selected>MySQL</option>
                                    <option value="sqlite" {{ old('database_connection') == 'sqlite' ? 'selected' : '' }}>
                                        SQLite</option>
                                </x-install-select>
                            </div>

                            <!-- Database Name field (always shown) -->
                            <div class="col-md-4 mb-3" id="database_name_container">
                                <x-install-input label="{{ __('installer.database_name') }}" required="true"
                                    name="database_name" type="text" value="{{ old('database_name') }}" />
                                <x-install-error for="database_name" />
                                <div class="text-muted small mt-1" id="sqlite_help_text" style="display: none;">
                                    {{ __('installer.sqlite_help_text') }} ({{ __('installer.example') }}:
                                    <code>mydb.sqlite</code>)
                                </div>
                            </div>

                            <!-- MySQL specific fields -->
                            <div class="mysql-only col-md-4 mb-3" id="database_host_container">
                                <x-install-input label="{{ __('installer.database_host') }}" required="false"
                                    name="database_hostname" type="text"
                                    value="{{ old('database_hostname', '127.0.0.1') }}" />
                                <x-install-error for="database_hostname" />
                            </div>

                            <div class="mysql-only col-md-4 mb-3" id="database_port_container">
                                <x-install-input label="{{ __('installer.database_port') }}" required="false"
                                    name="database_port" type="text" value="{{ old('database_port', '3306') }}" />
                                <x-install-error for="database_port" />
                            </div>

                            <div class="mysql-only col-md-4 mb-3" id="database_user_container">
                                <x-install-input label="{{ __('installer.database_username') }}" required="false"
                                    name="database_username" type="text" value="{{ old('database_username') }}" />
                                <x-install-error for="database_username" />
                            </div>

                            <div class="mysql-only col-md-4 mb-3" id="database_password_container">
                                <x-install-input label="{{ __('installer.database_password') }}" name="database_password"
                                    type="text" value="{{ old('database_password') }}" />
                                <x-install-error for="database_password" />
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Email Configuration Card -->
                <div class="card mb-4" id="email-config-card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('installer.email_configuration') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <x-install-input label="{{ __('installer.mail_driver') }}" type="text" value="smtp"
                                    name="mail_mailer" readonly>
                                    </x-install-select>
                                    <x-install-error for="mail_mailer" />
                            </div>

                            <div class="col-md-4 mb-3" id="mail_host_container">
                                <x-install-input label="{{ __('installer.mail_host') }}" required="false" name="mail_host"
                                    type="text" value="{{ old('mail_host', 'smtp.gmail.com') }}" readonly />
                                <x-install-error for="mail_host" />
                            </div>

                            <div class="col-md-4 mb-3" id="mail_port_container">
                                <x-install-input label="{{ __('installer.mail_port') }}" required="false" name="mail_port"
                                    type="number" value="{{ old('mail_port', '587') }}" readonly />
                                <x-install-error for="mail_port" />
                            </div>

                            <div class="col-md-4 mb-3" id="mail_username_container">
                                <x-install-input label="{{ __('installer.mail_username') }}" required="false"
                                    name="mail_username" type="text" value="{{ old('mail_username') }}" />
                                <x-install-error for="mail_username" />
                            </div>

                            <div class="col-md-4 mb-3" id="mail_password_container">
                                <label class="mb-1" for="mail_password">{{ __('installer.mail_password') }}</label>
                                <div class="input-group">
                                    <input type="password" name="mail_password" id="mail_password"
                                        class="form-control @error('mail_password') is-invalid @enderror"
                                        value="{{ old('mail_password') }}">
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="mail_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <x-install-error for="mail_password" />
                            </div>

                            <div class="col-md-4 mb-3" id="mail_encryption_container">
                                <x-install-select label="{{ __('installer.mail_encryption') }}" class="form-control"
                                    required="false" name="mail_encryption">
                                    <option value="">None</option>
                                    <option value="tls" {{ old('mail_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS
                                    </option>
                                    <option value="ssl" {{ old('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </x-install-select>
                                <x-install-error for="mail_encryption" />
                            </div>

                            <div class="col-md-6 mb-3" hidden>
                                <x-install-input label="{{ __('installer.mail_from_address') }}" required="true"
                                    name="mail_from_address" type="email"
                                    value="{{ old('mail_from_address', 'noreply@example.com') }}" readonly />
                                <x-install-error for="mail_from_address" />
                                <div class="text-muted small mt-1">
                                    {{ __('installer.mail_from_name_description') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer footerHome text-end">
                    <div class="d-flex">
                        <a href="{{ route('installs') }}"
                            class="btn btn-primary me-auto ms-3 px-4">{{ __('installer.back') }}</a>
                        <button type="button" id="test_connection_button"
                            class="btn btn-warning me-2">{{ __('installer.test_connection') }}</button>
                        <button type="button" id="test_email_button"
                            class="btn btn-info me-2">{{ __('installer.test_email') }}</button>
                        <button type="submit" id="next_button"
                            class="btn btn-primary px-4">{{ __('installer.next') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        /**
         * Database Import Page JavaScript
         * 
         * Features:
         * 1. Manage form display based on selected database type (MySQL or SQLite)
         * 2. Handle Test Connection button for database connection testing
         * 3. Handle form submission with AJAX for validation and error processing
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
                    testConnectionBtn.innerHTML = '{{ __("installer.testing") }}';

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
                            testConnectionBtn.innerHTML = '{{ __("installer.test_connection") }}';

                            // Create message div
                            const messageDiv = document.createElement('div');
                            messageDiv.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                            messageDiv.innerHTML = '<strong>' + (data.success ? '{{ __("installer.success") }}' : '{{ __("installer.error") }}') + '</strong> ' + data.message;

                            // Add message above Database Configuration card
                            const dbCard = document.getElementById('db-config-card');
                            if (dbCard) {
                                dbCard.parentNode.insertBefore(messageDiv, dbCard);
                            } else {
                                const container = document.querySelector('.container');
                                const firstChild = container && container.firstChild;
                                if (firstChild) container.insertBefore(messageDiv, firstChild);
                                else if (container) container.appendChild(messageDiv);
                            }

                            // Scroll to top to show message
                            window.scrollTo(0, 0);
                        })
                        .catch(error => {
                            // Enable the button again
                            testConnectionBtn.disabled = false;
                            testConnectionBtn.innerHTML = '{{ __("installer.test_connection") }}';

                            // Create error message
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'alert alert-danger';
                            errorDiv.innerHTML = '<strong>{{ __("installer.connection_error") }}</strong> {{ __("installer.could_not_test_database") }}';

                            // Place error above Database Configuration card
                            const dbCardErr = document.getElementById('db-config-card');
                            if (dbCardErr) {
                                dbCardErr.parentNode.insertBefore(errorDiv, dbCardErr);
                            } else {
                                const containerErr = document.querySelector('.container');
                                const firstChildErr = containerErr && containerErr.firstChild;
                                if (firstChildErr) containerErr.insertBefore(errorDiv, firstChildErr);
                                else if (containerErr) containerErr.appendChild(errorDiv);
                            }

                            // Scroll to top to show error
                            window.scrollTo(0, 0);

                            console.error('Error testing connection:', error);
                        });
                });
            }

            // Add test email functionality
            const testEmailBtn = document.getElementById('test_email_button');
            if (testEmailBtn) {
                testEmailBtn.addEventListener('click', function () {
                    // Clear any previous email test messages
                    const previousEmailMessages = document.querySelectorAll('.alert');
                    previousEmailMessages.forEach(msg => {
                        // Only remove messages that are above the email config card
                        const emailCard = document.getElementById('email-config-card');
                        if (emailCard && msg.nextElementSibling === emailCard) {
                            msg.remove();
                        }
                    });

                    // Disable test button
                    testEmailBtn.disabled = true;
                    testEmailBtn.innerHTML = '{{ __("installer.testing") }} Email...';

                    // Collect form data
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    formData.append('mail_mailer', document.querySelector('select[name="mail_mailer"]').value);
                    formData.append('mail_host', document.querySelector('input[name="mail_host"]').value);
                    formData.append('mail_port', document.querySelector('input[name="mail_port"]').value);
                    formData.append('mail_username', document.querySelector('input[name="mail_username"]').value);
                    formData.append('mail_password', document.querySelector('input[name="mail_password"]').value);
                    formData.append('mail_encryption', document.querySelector('select[name="mail_encryption"]').value);
                    formData.append('mail_from_address', document.querySelector('input[name="mail_from_address"]').value);

                    fetch('{{ route("test_email_connection") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Enable the button again
                            testEmailBtn.disabled = false;
                            testEmailBtn.innerHTML = '{{ __("installer.test_email") }}';

                            // Create message div
                            const messageDiv = document.createElement('div');
                            messageDiv.className = data.success ? 'alert alert-success' : 'alert alert-danger';
                            messageDiv.innerHTML = '<strong>' + (data.success ? '{{ __("installer.email_test_success") }}' : '{{ __("installer.email_test_failed") }}') + '</strong> ' + data.message;

                            // Add message above Email Configuration card
                            const emailCard = document.getElementById('email-config-card');
                            if (emailCard) {
                                emailCard.parentNode.insertBefore(messageDiv, emailCard);
                            } else {
                                // Fallback to adding above form
                                const container = document.querySelector('.container');
                                const firstChild = container && container.firstChild;
                                if (firstChild) container.insertBefore(messageDiv, firstChild);
                                else if (container) container.appendChild(messageDiv);
                            }

                            // Scroll to the email card to show message
                            if (emailCard) {
                                emailCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        })
                        .catch(error => {
                            // Enable the button again
                            testEmailBtn.disabled = false;
                            testEmailBtn.innerHTML = '{{ __("installer.test_email") }}';

                            // Create error message
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'alert alert-danger';
                            errorDiv.innerHTML = '<strong>{{ __("installer.email_test_error") }}</strong> {{ __("installer.could_not_test_email") }}';

                            // Place error above Email Configuration card
                            const emailCard = document.getElementById('email-config-card');
                            if (emailCard) {
                                emailCard.parentNode.insertBefore(errorDiv, emailCard);
                                emailCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            } else {
                                // Fallback to adding above form
                                const container = document.querySelector('.container');
                                const firstChild = container && container.firstChild;
                                if (firstChild) container.insertBefore(errorDiv, firstChild);
                                else if (container) container.appendChild(errorDiv);
                            }

                            console.error('Error testing email:', error);
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
                    nextButton.innerHTML = '{{ __("installer.processing") }}';
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
                                    errorDiv.innerHTML = '<strong>{{ __("installer.database_connection_error") }}</strong><ul>';

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
                            nextButton.innerHTML = '{{ __("installer.next") }}';
                        }

                        // Create error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger';
                        errorDiv.innerHTML = '<strong>{{ __("installer.connection_error") }}</strong><p>{{ __("installer.server_communication_error") }}</p>';

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

            // Email Configuration Toggle Logic
            const mailDriverSelect = document.querySelector('select[name="mail_mailer"]');
            const mailRequiredFields = [
                'mail_host_container',
                'mail_port_container',
                'mail_username_container',
                'mail_password_container',
                'mail_encryption_container'
            ];

            function toggleMailFields() {
                const selectedDriver = mailDriverSelect.value;

                if (selectedDriver === 'log' || selectedDriver === 'sendmail') {
                    // Hide SMTP fields for log and sendmail drivers
                    mailRequiredFields.forEach(fieldId => {
                        const container = document.getElementById(fieldId);
                        if (container) {
                            container.style.display = 'none';
                            // Remove required attribute
                            const input = container.querySelector('input, select');
                            if (input && input.hasAttribute('required')) {
                                input.removeAttribute('required');
                            }
                        }
                    });
                } else {
                    // Show SMTP fields for other drivers
                    mailRequiredFields.forEach(fieldId => {
                        const container = document.getElementById(fieldId);
                        if (container) {
                            container.style.display = 'block';
                            // Add required attribute for critical fields
                            const input = container.querySelector('input, select');
                            if (input && (fieldId === 'mail_host_container' || fieldId === 'mail_port_container')) {
                                input.setAttribute('required', 'required');
                            }
                        }
                    });
                }
            }

            // Add event listener to mail driver dropdown
            if (mailDriverSelect) {
                mailDriverSelect.addEventListener('change', toggleMailFields);
                // Run toggle on page load
                toggleMailFields();
            }

            // Function to set app_debug based on environment
            function setAppDebugBasedOnEnvironment() {
                const environmentSelect = document.querySelector('select[name="environment"]');
                const appDebugSelect = document.querySelector('select[name="app_debug"]');

                if (environmentSelect && appDebugSelect) {
                    const environment = environmentSelect.value;

                    // Set app_debug based on environment
                    if (environment === 'production') {
                        appDebugSelect.value = 'false';
                    } else if (environment === 'local') {
                        appDebugSelect.value = 'true';
                    }
                }
            }

            // Add event listener to environment dropdown
            const environmentSelect = document.querySelector('select[name="environment"]');
            if (environmentSelect) {
                environmentSelect.addEventListener('change', setAppDebugBasedOnEnvironment);
                // Run on page load to set initial state
                setAppDebugBasedOnEnvironment();
            }
        });
    </script>
@endsection