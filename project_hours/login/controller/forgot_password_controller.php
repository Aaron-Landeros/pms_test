<?php
require "../model/forgot_password_queries.php";

// Function to get the base URL dynamically
function base_url() {
    // Construct the base URL by moving up two directories
    $base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME']));
    return $base_url;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (check_if_email_exists($email)) {
        $token = bin2hex(random_bytes(32)); // Generate secure token
        $expiry_time = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expiry

        store_reset_token($email, $token, $expiry_time); // Store token in DB

        // Use dynamic base_url() function and correct path to /view/reset_password.php
        $reset_link = base_url() . "/view/reset_password.php?token=$token";
        
        // Send the reset link
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $reset_link";
        $headers = "From: no-reply@Entheospace.com";
        
        if (mail($email, $subject, $message, $headers)) {
            echo "A password reset link has been sent to your email.";
        } else {
            echo "There was an error sending the email.";
        }
    } else {
        echo "This email is not registered.";
    }
}