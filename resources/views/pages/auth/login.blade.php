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

        <div class="d-flex flex-column flex-md-row flex-column-fluid">

            <div class="d-flex flex-lg-row-fluid w-md-50 order-2 order-md-1">
                <img src="{{ asset('assets/images/logos/talim-dg.jpg') }}" class="img-fluid w-100 h-100 img-cover"
                    alt="Login Image">
            </div>

            <div
                class="d-flex flex-column justify-content-center flex-md-row-fluid w-md-50 p-5 ps-md-20 order-1 order-md-2 bg-light border">

                <div class="d-flex flex-column w-100 align-items-start justify-content-center">

                    <div class="w-xl-500px">

                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
                            data-kt-redirect-url="" action="{{ route('login') }}" method="post">
                            @csrf
                            <a href="index.html" class="d-block" style="width:240px; height:199px;">
                                <img src="{{ asset('assets/images/logos/deccan-logo.png') }}" class="img-fluid h-100"
                                    style="object-fit: contain;" alt="Logo">
                            </a>

                            <div class="mb-11">
                                <h1 class="fw-bold mb-3 text-dark display-4">Welcome Back!</h1>
                                <div class="fw-normal fs-2">
                                    Please log in to continue.
                                </div>
                            </div>

                            <div class="fv-row mb-8">
                                <label class="form-label fw-normal text-dark fs-3">Email</label>
                                <input type="text" class="form-control bg-white fs-5 text-dark"
                                    placeholder="Enter Your Email" name="email" />
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="fv-row mb-3 position-relative">
                                <label class="form-label fw-normal text-dark fs-3">Password</label>

                                <input type="password" class="form-control bg-white fs-4 text-dark"
                                    placeholder="Enter Password" name="password" id="password-field" autocomplete="off" />
                                @error('password')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror

                                <span id="togglePassword"
                                    class="btn btn-sm btn-icon position-absolute top-70 end-0 translate-middle-y me-3"
                                    style="cursor:pointer;">
                                    <i class="fa-solid fa-eye fs-4"></i>
                                </span>



                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-8">
                                <div class="form-check">
                                    <input class="form-check-input bg-white" type="checkbox" id="rememberMe">
                                    <label class="form-check-label text-dark fs-6" for="rememberMe">Remember me</label>
                                </div>
                                <a href="{{ route('password.request') }}" class="link-primary fs-6">Forgot Password?</a>
                            </div>

                            <div class="d-grid mb-10">
                                <button type="submit" class="btn btn-warning text-dark fs-4 fw-semibold">
                                    LOGIN
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password-field');

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