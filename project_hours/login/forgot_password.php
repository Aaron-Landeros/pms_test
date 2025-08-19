<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="scss/custom.css">
    <style>
        .custom-rounded-container {
            max-width: 50%; 
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="main-container bg-black d-flex justify-content-center align-items-center vh-100">
        <div class="top-left-logo" onclick="window.location.href='login.php'">
            <img class="img-fluid" src="assets/logo.webp" alt="ENTHEO WMS Logo">
        </div>

        <!-- Adjusted form container width -->
        <div class="container container-form d-flex justify-content-center align-items-center flex-column custom-rounded-container">
            <div class="entheo-ims-logo">
                <img class="img-fluid" src="assets/logo.webp" width="300" alt="ENTHEOSPACE WMS">
            </div>
            <div class="login-form w-100">
                <form id="forgot_password_form">
                    <div class="mb-3 w-100">
                        <label for="email" class="form-label text-login fw-semibold">Enter your email address:</label>
                        <input type="email" name="email" class="form-control w-100 bg-dark-input border-dark-input" id="email" placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 mt-3">Send Reset Link</button>
                </form>
                <div id="message_container" class="text-light text-center mt-3"></div>
            </div>
            <div class="footer mt-3 pt-3">
                <div class="container d-flex justify-content-center align-items-center mt-5">
                    <a target="_blank" href="https://entheospace.co/" class="text-decoration-none">
                        <p class="text-white-50 fw-semibold">Powered By ENTHEOSPACE</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="./js/forgot_password.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>