<?php
// Set database connection variables
$dbhost = "localhost";
$dbname = "akshada";
$dbuser = "postgres";
$dbpass = "9658";

// Connect to the database
$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass") or die("Could not connect to database");

// Retrieve login information from form
$email = trim($_POST['Uemail'] ?? "");
$password = $_POST['CreatePass'] ?? "";

// Check if the required fields are not empty
if (!empty($email) && !empty($password)) {
    // Prepare a statement to select the email and hashed password from the database
    $stmt = pg_prepare($conn, "SELECT_USER", "SELECT Password FROM LoginUsers WHERE Email = $1");
    if (!$stmt) {
        die("Error preparing statement: " . pg_last_error($conn));
    }

    // Execute the statement with the user's email
    $result = pg_execute($conn, "SELECT_USER", array($email));
    if (!$result) {
        die("Error executing statement: " . pg_last_error($conn));
    }

    // Check if a row was returned
    if (pg_num_rows($result) > 0) {
        // Fetch the hashed password from the result
        $hashed_password = pg_fetch_result($result, 0, 0);

        // Verify the password with the hashed password
        if (password_verify($password, $hashed_password)) {
            // Redirect the user to the home page or another page if the login is successful
            header("Location: QRhomeindex.html");
            exit;
        } else {
            echo "<script>alert('Invalid email address or password!')</script>";
            echo "<script>window.history.back()</script>";
        }
    } else {
        echo "<script>alert('Invalid email address or password! Please sign up.')</script>";
        echo "<script>window.history.back()</script>";
    }
} else {
    echo "<script>alert('All fields are required!')</script>";
    echo "<script>window.history.back()</script>";
}

// Close database connection
pg_close($conn);
?>
