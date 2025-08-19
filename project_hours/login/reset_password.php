<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../scss/reset_password.css">
</head>
<body>
    <div class="main-container bg-black d-flex justify-content-center align-items-center vh-100">

        <div class="container container-form d-flex justify-content-center align-items-center flex-column custom-rounded-container2">
            <div class="entheo-ims-logo">
                <img class="img-fluid" src="../assets/entheo_wms_logo.svg" width="300" alt="ENTHEOSPACE WMS">
            </div>
            <div class="login-form w-75">
                <?php
                require "../model/forgot_password_queries.php";

                // Function to get the base URL dynamically
                function base_url() {
                    // Construct the base URL by moving up two directories
                    $base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME']));
                    return $base_url;
                }

                $base_url = base_url();
                $token = filter_input(INPUT_GET, 'token');

                if (check_if_token_valid($token)) {
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $new_password = filter_input(INPUT_POST, 'new_password');
                        
                        // Hash the new password
                        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                        // Update the password in the database and clear the token
                        update_password($token, $hashed_password);

                        // Success message with hyperlink
                        echo "<p>Your password has been reset successfully.</p>";
                        echo '<p><a href="' . $base_url . '/login.php">Click here to be redirected to the login page</a></p>';
                    } else {
                        // Display the password reset form
                        echo '<form method="POST">
                                <div class="mb-3 w-100">
                                    <label for="new_password" class="form-label text-login fw-semibold">New Password:</label>
                                    <input type="password" name="new_password" class="form-control w-100 bg-dark-input border-dark-input" id="new_password" required>
                                </div>
                                <div class="mb-3 w-100 d-flex align-items-center">
                                    <input type="checkbox" id="show_password" class="me-2">
                                    <label for="show_password" class="form-label text-login">Show Password</label>
                                </div>
                                <button type="submit" class="btn btn-danger w-100 mt-3">Reset Password</button>
                              </form>';
                    }
                } else {
                    echo "<p>Invalid or expired token.</p>";
                }
                ?>
            </div>
            <div class="footer mt-3 pt-3">
                <div class="container d-flex justify-content-center align-items-center mt-2">
                    <a target="_blank" href="https://entheospace.co/" class="text-decoration-none">
                        <p class="text-white-50 fw-semibold">Powered By ENTHEOSPACE</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/reset_password.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>