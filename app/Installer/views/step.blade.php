<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-10 col-md-10 col-lg-10 col-xl-9 text-center p-0 mt-3 mb-2">
            <div class="cardstep px-0 pt-4 pb-0 mt-3 mb-3">
                <h2 id="heading">{{ config('install.install_title') }}</h2>
                <form id="msform" class="px-3">
                    <div class="d-flex justify-content-center align-items-center position-relative mb-5">
                        <div class="col text-center position-relative">
                            <div
                                class="circle-icon {{ Route::currentRouteName() == 'installs' ? 'active' : '' }} {{ Route::currentRouteName() == 'database_import' ? 'active' : '' }} {{ Route::currentRouteName() == 'profil_perusahaan' ? 'active' : '' }}{{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-asterisk"></i>
                            </div>
                            <div class="step-label">Requirements & Permissions</div>
                        </div>
                        <hr
                            class="connector-line {{ Route::currentRouteName() == 'database_import' ? 'active' : '' }} {{ Route::currentRouteName() == 'profil_perusahaan' ? 'active' : '' }}{{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}" />
                        <div class="col text-center position-relative">
                            <div
                                class="circle-icon {{ Route::currentRouteName() == 'database_import' ? 'active' : '' }} {{ Route::currentRouteName() == 'select_seeders' ? 'active' : '' }} {{ Route::currentRouteName() == 'profil_perusahaan' ? 'active' : '' }}{{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-database"></i>
                            </div>
                            <div class="step-label">Database Import</div>
                        </div>
                        <hr
                            class="connector-line {{ Route::currentRouteName() == 'select_seeders' ? 'active' : '' }} {{ Route::currentRouteName() == 'profil_perusahaan' ? 'active' : '' }}{{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}" />
                        <div class="col text-center position-relative">
                            <div
                                class="circle-icon {{ Route::currentRouteName() == 'select_seeders' ? 'active' : '' }} {{ Route::currentRouteName() == 'profil_perusahaan' ? 'active' : '' }}{{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-code"></i>
                            </div>
                            <div class="step-label">Data Dummy</div>
                        </div>
                        <hr
                            class="connector-line {{ Route::currentRouteName() == 'profil_perusahaan' ? 'active' : '' }}{{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}" />
                        <div class="col text-center position-relative">
                            <div
                                class="circle-icon {{ Route::currentRouteName() == 'profil_perusahaan' ? 'active' : '' }}{{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="step-label">Profil Perusahaan</div>
                        </div>
                        <hr
                            class="connector-line {{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}" />
                        <div class="col text-center position-relative">
                            <div
                                class="circle-icon {{ Route::currentRouteName() == 'super_admin_config' ? 'active' : '' }}{{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="step-label">Super Admin</div>
                        </div>
                        <hr
                            class="connector-line {{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}" />
                        <div class="col text-center position-relative">
                            <div
                                class="circle-icon {{ Route::currentRouteName() == 'user_roles_list' ? 'active' : '' }}{{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="step-label">User Roles</div>
                        </div>
                        <hr
                            class="connector-line {{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}" />
                        <div class="col text-center position-relative">
                            <div
                                class="circle-icon {{ Route::currentRouteName() == 'feature_toggles' ? 'active' : '' }}{{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-toggle-on"></i>
                            </div>
                            <div class="step-label">Feature Toggles</div>
                        </div>
                        <hr class="connector-line {{ Route::currentRouteName() == 'finish' ? 'active' : '' }}" />
                        <div class="col text-center position-relative">
                            <div class="circle-icon {{ Route::currentRouteName() == 'finish' ? 'active' : '' }}">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="step-label">Finish</div>
                        </div>
                    </div>
                    @php
                        $totalSteps = 8;
                        $currentStep = array_search(Route::currentRouteName(), [
                            'installs',
                            'database_import',
                            'select_seeders',
                            'profil_perusahaan',
                            'super_admin_config',
                            'user_roles_list',
                            'feature_toggles',
                            'finish'
                        ]) + 1;
                        $progressPercentage = ($currentStep / $totalSteps) * 100;
                    @endphp
                    <div class="progress" role="progressbar" aria-label="Default striped example"
                        aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>