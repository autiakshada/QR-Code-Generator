<?php
// Set database connection variables
$dbhost = "localhost";
$dbname = "akshada";
$dbuser = "postgres";
$dbpass = "9658";

// Connect to the database
$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass") or die("Could not connect to database");

// Retrieve registration information from form
$uname = trim($_POST['Uname'] ?? "");
$email = trim($_POST['Uemail'] ?? "");
$password = $_POST['CreatePass'] ?? "";
$confirm_password = $_POST['ConfirmPass'] ?? "";

// Check if the required fields are not empty
if (!empty($uname) && !empty($email) && !empty($password) && !empty($confirm_password)) {
    // Check if the passwords match and meet the criteria
    if ($password !== $confirm_password) {
        echo "<script>alert('Error: Passwords do not match!')</script>";
        echo "<script>window.history.back()</script>";
        exit;
    } elseif (strlen($password) < 8 || !preg_match("/[a-z]/", $password) || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        echo "<script>alert('Error: Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, and one number!')</script>";
        echo "<script>window.history.back()</script>";
        exit;
    } else {
        // Check if the username meets the criteria
        if (!preg_match("/^[a-zA-Z0-9_]{4,20}$/", $uname)) {
            echo "<script>alert('Error: Username must contain only letters, numbers, and underscores, and be 4 to 20 characters long!')</script>";
            echo "<script>window.history.back()</script>";
            exit;
        // Check if the email address is valid and not already registered in the database
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Error: Invalid email address!')</script>";
            echo "<script>window.history.back()</script>";
            exit;
        } else {
            $result = pg_query_params($conn, "SELECT COUNT(*) FROM LoginUsers WHERE email = $1", array($email));
            if ($result && pg_fetch_result($result, 0, 0) > 0) {
                echo "<script>alert('Error: Email address already registered!')</script>";
                echo "<script>window.history.back()</script>";
                exit;
            } else {
                // Insert registration information into the database
                $hash=password_hash($password,PASSWORD_DEFAULT);
                $result = pg_query_params($conn, "INSERT INTO LoginUsers (email, username, password) VALUES ($1, $2, $3)", array($email, $uname, $hash));

                if ($result) {
                    echo "<script>alert('Registration successful!')</script>";
                    echo "<audio autoplay>
                            <source src='notification-sound-127856.mp3' type='audio/mpeg'>
                          </audio>";
                    echo "<script>window.location.href = 'homeindex.html';</script>";
                    exit;
                } else {
                    echo "Error: " . pg_last_error($conn);
                }
            }
        }
    }
} else {
    echo "<script>alert('Error: All fields are required!')</script>";
    echo "<script>window.history.back()</script>";
    exit;
}

// Close database connection
pg_close($conn);
?>
