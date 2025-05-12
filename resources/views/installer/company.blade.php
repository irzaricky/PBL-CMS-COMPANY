@extends('installer.layout')

@section('content')
    <div class="text-center mb-4">
        <h2>Company Profile Setup</h2>
        <p>Final step! Let's set up your company information that will be displayed on your site</p>
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
            @endif

            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                This information will be displayed throughout your website. You can change these details later from the
                admin panel.
            </div>

            <form method="POST" action="{{ route('installer.company.save') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="nama_perusahaan" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan"
                        value="{{ old('nama_perusahaan') }}" required>
                </div>

                <div class="mb-3">
                    <label for="logo_perusahaan" class="form-label">Company Logo</label>
                    <input type="file" class="form-control" id="logo_perusahaan" name="logo_perusahaan">
                    <div class="form-text">Upload your company logo (optional)</div>
                    <div class="mt-2" id="logoPreview" style="display: none;">
                        <img id="previewImage" src="#" alt="Logo Preview" style="max-height: 100px;" class="img-thumbnail">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="alamat_perusahaan" class="form-label">Company Address</label>
                    <input type="text" class="form-control" id="alamat_perusahaan" name="alamat_perusahaan"
                        value="{{ old('alamat_perusahaan') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email_perusahaan" class="form-label">Company Email</label>
                    <input type="email" class="form-control" id="email_perusahaan" name="email_perusahaan"
                        value="{{ old('email_perusahaan') }}" required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('installer.admin') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Previous
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Complete Setup <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Preview uploaded logo
        document.getElementById('logo_perusahaan').onchange = function (e) {
            const preview = document.getElementById('previewImage');
            const logoPreview = document.getElementById('logoPreview');

            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();

                reader.onload = function (event) {
                    preview.src = event.target.result;
                    logoPreview.style.display = 'block';
                }

                reader.readAsDataURL(e.target.files[0]);
            }
        };
    </script>
@endsection