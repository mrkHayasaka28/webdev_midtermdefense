<?php

session_start();

// Check if user is logged in
if (!isset($_GET['name']) || !isset($_GET['birthdate'])) {
    echo "Access denied. Please log in.";
    exit();
}

// Get user details from query parameters
$user_name = htmlspecialchars($_GET['name']);
$user_birthdate = htmlspecialchars($_GET['birthdate']);

// Initialize variables
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_birthdate = $_POST['birthdate'];
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    // Read existing users data
    $users = array();
    if (file_exists('users.txt')) {
        $file = fopen('users.txt', 'r');
        while (($line = fgets($file)) !== false) {
            $userData = explode(':', trim($line));
            if (count($userData) === 4) {
                $users[] = array(
                    'email' => $userData[0],
                    'password' => $userData[1],
                    'name' => $userData[2],
                    'birthdate' => $userData[3]
                );
            }
        }
        fclose($file);
    }

    // Find the user and update their information
    $user_found = false;
    foreach ($users as &$user) {
        if ($user['name'] === $user_name && $user['birthdate'] === $user_birthdate) {
            // Check current password
            if ($current_password === $user['password']) {
                $user['name'] = $new_name;
                $user['birthdate'] = $new_birthdate;

                // Update password if new password is confirmed
                if ($new_password === $confirm_password && !empty($new_password)) {
                    $user['password'] = $new_password; // Update the password
                    $_SESSION['message'] = "Information updated successfully, including password!";
                } else {
                    $_SESSION['message'] = "New password and confirmation do not match.";
                }

                $user_found = true;
            } else {
                $_SESSION['message'] = "Current password is incorrect.";
            }
            break;
        }
    }

    // If user was found, write updated data back to the file
    if ($user_found) {
        $file = fopen('users.txt', 'w');
        foreach ($users as $user) {
            fwrite($file, implode(':', $user) . "\n");
        }
        fclose($file);
        
        // Update user details for the session
        $user_name = $new_name;
        $user_birthdate = $new_birthdate;
    } else {
        $_SESSION['message'] = "User  not found.";
    }
}

// Check if there is a session message to display
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Page</title>
    <link rel="stylesheet" href="membership.css">
</head>
<body>
    <div class="app-bar">
        <a href="mainpage.html">
            <div class="logo"><img src="assets/aid.png" alt="Logo"></div>
        </a>
        <nav>
            <button class="nav-btn" onclick="openModal()">My Account</button>
            <button class="nav-btn logout-button" onclick="window.location.href='../PrelimWEBSITE-main/accadmin.php';">Log Out</button>
        </nav>
    </div>
    
    <header>
        <div class="main-image">
            <img src="assets/main-image.jpeg" alt="Your Image">
            <div class="background-overlay"></div>
            <div class="text-overlay">
                <img src="assets/aid.png" alt="Logo Overlay">
            </div>
        </div>
    </header>

    <div class="sub-section">
        <h2>SUBSCRIPTION PLANS</h2>
        <div class="sub-container">
            <div class="plans-container">
                <div class="plan-box">
                    <h3>₱250</h3>
                    <p>Support our programs with a monthly contribution of ₱250.</p>
                    <button class="join-button" onclick="location.href='payment.php?plan=250&name=<?php echo urlencode($user_name); ?>&birthdate=<?php echo urlencode($user_birthdate); ?>'">JOIN</button>
                </div>
                <div class="plan-box">
                    <h3>₱500</h3>
                    <p>Provide greater support with a monthly contribution of ₱500.</p>
                    <button class="join-button" onclick="location.href='payment.php?plan=500&name=<?php echo urlencode($user_name); ?>&birthdate=<?php echo urlencode($user_birthdate); ?>'">JOIN</button>
                </div>
                <div class="plan-box">
                    <h3>₱1000</h3>
                    <p>Make an impactful contribution with ₱1000 a month.</p>
                    <button class="join-button" onclick="location.href='payment.php?plan=1000&name=<?php echo urlencode($user_name); ?>&birthdate=<?php echo urlencode($user_birthdate); ?>'">JOIN</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing information -->
    <div id="edit-info" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Your Information</h2>

            <form method="POST">
                <!-- Display notification message -->
                <?php if ($message): ?>
                    <p><?php echo $message; ?></p>
                <?php endif; ?>

                <!-- Editable name -->
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $user_name; ?>" required>

                <!-- Editable birthdate -->
                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo $user_birthdate; ?>" required>

                <!-- Change password section -->
                <h3>Change Password</h3>
                <label for="current-password">Current Password:</label>
                <input type="password" id="current-password" name="current-password" required>

                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password" required>

                <label for="confirm-password">Confirm New Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit" class="nav-btn new-button">Update Information</button>
            </form>
        </div>
    </div>

    <div class="contact-section">
        <h2>Contact Us</h2>
        <div class="contact-container">
            <div class="contact-info">
                <p><strong>Phone:</strong> +123 456 7890</p>
                <p><strong>Email:</strong> contact@charity.org</p>
                <p><strong>Address:</strong> 123 Charity Lane, Goodville, GV 12345</p>
            </div>
            <div class="contact-social">
                <p><strong>Follow Us:</strong></p>
                <a href="https://www.facebook.com/charity" class="contact-link">Facebook</a> |
                <a href="https://twitter.com/charity" class="contact-link">Twitter</a> |
                <a href="https://www.instagram.com/charity" class="contact-link">Instagram</a>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('edit-info').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('edit-info').style.display = 'none';
        }

        window.onclick = function(event) {
            var modal = document.getElementById('edit-info');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Show notification when the page reloads
        window.onload = function() {
            var message = "<?php echo addslashes($message); ?>";
            if (message) {
                alert(message); // Show alert with the message
            }
        };
    </script>
</body>
</html>