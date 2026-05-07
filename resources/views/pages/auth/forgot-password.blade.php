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

        .back-btn i {
            font-size: 20px !important;
            color: #000 !important;
        }
    </style>
</head>

<body id="kt_body" class="app-blank">

    <div class="d-flex flex-column flex-root" id="kt_app_root">

        <div class="d-flex flex-column flex-lg-row flex-column-fluid">

            <div class="d-flex flex-lg-row-fluid w-lg-50 order-2 order-lg-1">
                <img src="{{ asset('assets/images/logos/talim-dg.jpg') }}" class="img-fluid w-100 h-100 img-cover"
                    alt="Login Image">
            </div>

            <div
                class="d-flex flex-column justify-content-center flex-lg-row-fluid w-lg-50 p-5 p-lg-20 order-1 order-lg-2 bg-light border">

                <div class="d-flex flex-column w-100 align-items-start justify-content-center">

                    <div class="w-xl-500px">
 
                        <div class="back-btn">
                            <a href="https://lime-crab-899227.hostingersite.com/deccan-gymkhana/public/login"><i
                                    class="bi bi-arrow-left"></i></a>

                        </div>

                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
                            action="{{ route('password.email') }}" method="post">
                            @csrf
                            <a href="index.html" class="d-block" style="width:120px; height:199px;">
                                <img src="{{ asset('assets/images/logos/deccan-logo.png') }}" class="img-fluid h-100"
                                    style="object-fit: contain;" alt="Logo">
                            </a>

                            <div class="mb-11">
                                <h1 class="fw-bold mb-3 text-dark display-4">Forgot Password!</h1>
                                <div class="fw-normal fs-2">
                                    Enter your registered email address. We'll send you a link to reset your password.
                                </div>
                            </div>

                            <div class="fv-row mb-8">
                                <label class="form-label fw-normal text-dark fs-3">Email</label>
                                <input type="text" class="form-control bg-white fs-5 text-dark"
                                    placeholder="Enter Your Email" name="email" />
                            </div>

                            @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <div class="d-grid mb-10">
                                <button type="submit" class="btn btn-warning text-dark fs-4 fw-semibold">
                                    SEND PASSWORD RESET LINK
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