<?php
// Include the database connection file
include 'config.php';

// Initialize message variables
$signup_message = '';
$login_message = '';

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    // Get form input values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password == $confirm_password) {
        // Hash the password before saving
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $signup_message = "<p>Registration successful! You can now log in.</p>";
        } else {
            $signup_message = "<p>Error: " . $stmt->error . "</p>";
        }
    } else {
        $signup_message = "<p>Passwords do not match!</p>";
    }
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Get form input values
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, now check password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // If login is successful, start the session and redirect to the landing page
            session_start();
            $_SESSION['user_id'] = $user['id'];  // Store the user ID in session
            $_SESSION['user_name'] = $user['name'];  // Store the user's name in session
            
            // Redirect to landing page
            header("Location: landing.php");
            exit; // Always call exit after header redirection
        } else {
            $login_message = "<p>Incorrect password!</p>";
        }
    } else {
        $login_message = "<p>No user found with that email!</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Login and Registration Form in HTML & CSS | CodingLab</title>
    <link rel="stylesheet" href="css/index.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container">
    <!-- Checkbox to toggle between login and signup -->
    <input type="checkbox" id="flip">

    <div class="cover">
        <div class="front">
            <img src="images/frontImg.jpg" alt="">
            <div class="text">
                <span class="text-1">Every new friend is a <br> new adventure</span>
                <span class="text-2">Let's get connected</span>
            </div>
        </div>
        <div class="back">
            <div class="text">
                <span class="text-1">Complete miles of journey <br> with one step</span>
                <span class="text-2">Let's get started</span>
            </div>
        </div>
    </div>

    <div class="forms">
        <div class="form-content">
            <!-- Login Form -->
            <div class="login-form">
                <div class="title">Login</div>
                <form action="index.php" method="POST">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="button input-box">
                            <input type="submit" name="login" value="Login">
                        </div>
                        <div class="text"><?php echo $login_message; ?></div>
                        <div class="text sign-up-text">Don't have an account? <label for="flip">Sign up now</label></div>
                    </div>
                </form>
            </div>

            <!-- Signup Form -->
            <div class="signup-form">
                <div class="title">Signup</div>
                <form action="index.php" method="POST">
                    <div class="input-boxes">
                        <div class="input-box">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="input-box">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                        </div>
                        <div class="button input-box">
                            <input type="submit" name="signup" value="Submit">
                        </div>
                        <div class="text"><?php echo $signup_message; ?></div>
                        <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
