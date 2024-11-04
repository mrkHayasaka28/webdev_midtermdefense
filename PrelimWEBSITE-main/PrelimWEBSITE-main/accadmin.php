<?php
// Start the session to use session variables if needed
session_start();

// Check if user is trying to login
if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    // Default admin credentials
    $admin_email = 'admin123@gmail.com';
    $admin_password = 'admin1234';
    $admin_name = 'Admin User';
    $admin_birthdate = '06-28-2004';

    // Check if the login credentials match the admin account
    if ($email === $admin_email && $password === $admin_password) {
        // Store email in session
        $_SESSION['email'] = $admin_email; // Store the email in session
        // Redirect to admin dashboard
        header("Location: adminacc.php");
        exit(); // Make sure to exit after redirect
    }

    // Check if user has registered before
    $file = 'users.txt';
    $fp = fopen($file, 'r');
    $registered = false;
    
    while (($line = fgets($fp)) !== false) {
        list($stored_email, $stored_password, $stored_name, $stored_birthdate) = explode(':', trim($line));
        if ($email == $stored_email && $password == $stored_password) {
            $registered = true;
            // Store email in session
            $_SESSION['email'] = $stored_email; // Store the email in session

            // Redirect to membership.php for regular users
            header("Location: membership.php?name=" . urlencode($stored_name) . "&birthdate=" . urlencode($stored_birthdate));
            exit(); // Make sure to exit after redirect
        }
    }
    fclose($fp);

    if (!$registered) {
        echo "<script>alert('Account not detected. Please register first.');</script>";
    }
}

// Register user
if (isset($_POST['register'])) {
    $email = $_POST['register_email'];
    $password = $_POST['register_password'];
    $re_password = $_POST['register_re_password'];
    $name = $_POST['register_name'];
    $birthdate = $_POST['register_birthdate'];

    // Validate name to allow only letters and spaces
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        echo "Registration failed: Name can only contain letters and spaces.";
    } elseif ($password !== $re_password) {
        echo "Registration failed: Passwords do not match.";
    } else {
        // Store user credentials in a text file
        $file = 'users.txt';
        $fp = fopen($file, 'a');
        fwrite($fp, "$email:$password:$name:$birthdate\n");
        fclose($fp);

        echo "User  registered successfully!";
    }
}

// Show login form by default
$show_login = true;

// Toggle between login and register forms
if (isset($_GET['register'])) {
    $show_login = false;
} elseif (isset($_GET['login'])) {
    $show_login = true;
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <link rel="stylesheet" href="accadmin.css">
</head>
<body>
    <div class="container">
        <?php if ($show_login) { ?>
            <div class="login-form">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="assets/aid.png" alt="Logo" style="width: 250px; height: 200px;">
                </div>
                <h2>Login</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="login_email">Email:</label>
                    <input type="email" id="login_email" name="login_email" required><br><br>
                    <label for="login_password">Password:</label>
                    <input type="password" id="login_password" name="login_password" required><br><br>
                    <input type="submit" name="login" value="Login">
                </form>
                <p>Don't have an account? <a href="<?php echo $_SERVER['PHP_SELF']; ?>?register">Register</a></p>
            </div>
        <?php } else { ?>
            <div class="register-form">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="assets/aid.png" alt="Logo" style="width: 250px; height: 200px;">
                </div>
                <h2>Register</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="register_email">Email:</label>
                    <input type="email" id="register_email" name="register_email" required><br><br>
                    <label for="register_password">Password:</label>
                    <input type="password" id="register_password" name="register_password" required>
                    <span id="password-error" style="color: red;"></span>
                    <label for="register_re_password">Re-enter Password:</label>
                    <input type="password" id="register_re_password" name="register_re_password" required><br><br>
                    <span id="re-password-error" style="color: red;"></span>
                    <label for="register_name">Name:</label>
                    <input type="text" id="register_name" name="register_name" required><br><br>
                    <span id="name-error" style="color: red;"></span>
                    <label for="register_birthdate">Birthdate:</label>
                    <input type="date" id="register_birthdate" name="register_birthdate" required><br><br>
                    <input type="submit" name="register" value="Register" required><br><br>
                </form>
                <p>Already have an account? <a href="<?php echo $_SERVER['PHP_SELF']; ?>?login">Login</a></p>
            </div>
        <?php } ?>
    </div>
    <script>
        const nameInput = document.getElementById('register_name');
        const nameError = document.getElementById('name-error');
        const passwordInput = document.getElementById('register_password');
        const rePasswordInput = document.getElementById('register_re_password');
        const passwordError = document.getElementById('password-error');
        const rePasswordError = document.getElementById('re-password-error');
        const emailInput = document.getElementById('register_email');
        const birthdateInput = document.getElementById('register_birthdate');
        const form = document.querySelector('form');

        // Validate name input
        nameInput.addEventListener('input', (e) => {
            const inputValue = e.target.value;
            const regex = /^[a-zA-Z\s]+$/; // only allow letters and spaces

            if (!regex.test(inputValue)) {
                nameError.textContent = 'No special characters or numbers allowed';
            } else {
                nameError.textContent = '';
            }
        });

        // Validate form on submission
        form.addEventListener('submit', (e) => {
            // Check if all fields are filled
            if (nameInput.value === '' || passwordInput.value === '' || rePasswordInput.value === '' || emailInput.value === '' || birthdateInput.value === '') {
                e.preventDefault();
                alert('Please fill in all fields');
                return;
            }

            // Check if passwords match
            if (passwordInput.value !== rePasswordInput.value) {
                e.preventDefault();
                alert('Passwords do not match');
            } else {
                passwordError.textContent = '';
                rePasswordError.textContent = '';
            }
        });
    </script>
</body>
</html>