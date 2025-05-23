@section('title', 'Pilih Data Dummy')
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('saveSeeders') }}" method="post" class="card">
                @csrf
                <div class="card-body">
                    <h4 class="mb-4">Pilih Data Dummy</h4>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Pilih data dummy yang ingin dimasukkan ke dalam database. Biarkan tidak terpilih jika Anda ingin
                        menambahkan data secara manual.
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="select-all" checked>
                                    <label class="form-check-label fw-bold" for="select-all">Pilih/Batalkan Semua</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach ($seeders as $seeder => $label)
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input seeder-checkbox" type="checkbox"
                                        name="seeders[{{ $seeder }}]" id="seeder-{{ $seeder }}" value="1" checked>
                                    <label class="form-check-label" for="seeder-{{ $seeder }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer text-end">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('database_import') }}" class="btn btn-primary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Selanjutnya</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the select all checkbox
            const selectAllCheckbox = document.getElementById('select-all');
            // Get all seeder checkboxes
            const seederCheckboxes = document.querySelectorAll('.seeder-checkbox');

            // Add event listener to select all checkbox
            selectAllCheckbox.addEventListener('change', function () {
                seederCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Add event listener to individual checkboxes
            seederCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const allChecked = Array.from(seederCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(seederCheckboxes).some(cb => cb.checked);

                    // Update the select all checkbox based on individual checkbox states
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = !allChecked && someChecked;
                });
            });
        });
    </script>
@endsection