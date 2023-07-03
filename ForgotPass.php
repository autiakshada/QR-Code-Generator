<?php
// Set database connection variables
$dbhost = "localhost";
$dbname = "akshada";
$dbuser = "postgres";
$dbpass = "9658";

// Connect to the database
$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass") or die("Could not connect to database");

// Retrieve user information from form
$email = trim($_POST['Uemail'] ?? "");

// Check if the email field is not empty
if (!empty($email)) {
    // Check if the email address exists in the database
    $result = pg_query_params($conn, "SELECT COUNT(*) FROM LoginUsers WHERE Email = $1", array($email));
    if ($result && pg_fetch_result($result, 0, 0) > 0) {
        // Generate an OTP and store it in the database
        $otp = rand(100000, 999999);
        $result = pg_query_params($conn, "UPDATE LoginUsers SET OTP = $1 WHERE Email = $2", array($otp, $email));
        if ($result) {
            // Send the OTP to the user's email address
            $to = $email;
            $subject = "Forgot Password OTP";
            $message = "Your OTP for resetting password is: $otp";
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'MIME-Version: 1.0',
                'From: noreply@example.com',
                'X-Mailer: PHP/' . phpversion(),
                'SMTPSecure' => 'tls',
                'SMTPAuth' => true,
                'Host' => 'smtp.gmail.com',
                'Port' => 587,
                'Username' => 'codechameleon03@gmail.com',
                'Password' => 'RgVwAa-03'
            );
            $headers = implode("\r\n", $headers);
            if (mail($to, $subject, $message, $headers)) {
                // Redirect the user to the reset password page
                header("Location: ResetPass.html?email=$email");
                exit;
            } else {
                echo "Error: Failed to send email!";
            }
        } else {
            echo "Error: Failed to generate OTP!";
        }
    } else {
        echo "Error: Invalid email address!";
    }
} else {
    echo "Error: Email address is required!";
}

// Close database connection
pg_close($conn);
?>
