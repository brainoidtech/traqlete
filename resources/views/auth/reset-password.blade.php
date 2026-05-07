<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />


    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.css') }}" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <style>
        * {
            font-family: 'Poppins', sans-serif !important;
        }

        .img-cover {
            object-fit: cover;
        }

        .fa-eye:before {
            content: "\f06e";
            font-family: 'FontAwesome';
        }

        .fa-eye-slash:before {
            content: "\f070";
            font-family: 'FontAwesome';
        }

        .top-70 {
            top: 70% !important;
        }
    </style>
</head>

<body id="kt_body" class="app-blank">
 

    <div class="d-flex flex-column flex-root" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10 bg-light min-vh-100">
        
        <div class="card w-md-550px w-100 shadow-sm border-0">
            <div class="card-body p-5 p-lg-5">

                <form class="form w-100" novalidate="novalidate" action="{{ route('password.update') }}" method="post">
                    @csrf
                    
                    <div class="mb-10">
                        <a href="index.html" class="d-inline-block">
                            <img src="{{ asset('assets/images/logos/deccan-logo.png') }}" class="img-fluid "
                                style="width: 250px;" alt="Logo">
                        </a>
                    </div>

                    <div class="text-start mb-10">
                        <h1 class="fw-bolder mb-3 text-dark display-6">Reset Password</h1>
                        <div class="text-muted fw-normal fs-4">
                            Enter your new password below.
                        </div>
                    </div>

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="fv-row mb-8">
                        <label class="form-label fw-semibold text-dark fs-4">Email</label>
                        <input type="email" class="form-control form-control-lg bg-white fs-6 text-dark"
                            placeholder="Enter Your Email" name="email" id="email" value="{{ $email ?? old('email') }}"
                            readonly />
                    </div>

                    <div class="fv-row mb-8 position-relative">
                        <label class="form-label fw-semibold text-dark fs-4">New Password</label>
                        <input type="password" class="form-control form-control-lg bg-white fs-6 text-dark"
                            placeholder="Enter New password" name="password" id="password" />
                        
                        <span id="togglePassword"
                            class="btn btn-sm btn-icon position-absolute top-50 end-0 translate-middle-y me-3 mt-4"
                            style="cursor:pointer;">
                            <i class="fa-solid fa-eye fs-4"></i>
                        </span>
                    </div>

                    <div class="fv-row mb-10">
                        <label class="form-label fw-semibold text-dark fs-4">Confirm Password</label>
                        <input type="password" class="form-control form-control-lg bg-white fs-6 text-dark"
                            placeholder="Re-enter Password" name="password_confirmation" id="password_confirmation" />
                    </div>

                    @if($errors->any())
                    <div class="mb-5">
                        @foreach($errors->all() as $error)
                        <p class="text-danger mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <div class="d-grid mb-5">
                        <button type="submit" class="btn btn-warning text-dark fs-5 fw-bold py-4 text-uppercase">
                            Reset Password
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;

            const icon = this.querySelector('i');

            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    </script>

</body>

</html>