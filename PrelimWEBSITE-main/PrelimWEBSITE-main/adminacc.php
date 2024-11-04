<?php
session_start();
// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin123@gmail.com') {
    header("Location: accadmin.php"); // Redirect to login page if not admin
    exit();
}

// Read user data from users.txt
$users = array(); // Use array() syntax
if (file_exists('users.txt')) {
    $file = fopen('users.txt', 'r');
    while (($line = fgets($file)) !== false) {
        $userData = explode(':', trim($line));
        if (count($userData) === 4) {
            $users[] = array( // Use array() syntax
                'email' => $userData[0],
                'password' => $userData[1],
                'name' => $userData[2],
                'birthdate' => $userData[3]
            );
        }
    }
    fclose($file);
}

// Handle user deletion
if (isset($_GET['email'])) {
    $emailToDelete = htmlspecialchars($_GET['email']);
    $filePath = 'users.txt';
    
    // Read the contents of the file
    $users = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Prepare to write back the users without the one to delete
    $updatedUsers = array();

    foreach ($users as $user) {
        // Assuming each line in users.txt is formatted as "email:password:name:birthdate"
        list($email, $password, $name, $birthdate) = explode(':', $user);
        if ($email !== $emailToDelete) {
            $updatedUsers[] = $user; // Keep the user if it's not the one to delete
        }
    }

    // Write the updated users back to the file
    file_put_contents($filePath, implode(PHP_EOL, $updatedUsers));

    // Redirect back or show a success message
    header('Location: ' . $_SERVER['PHP_SELF'] . '?message=Account deleted successfully');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminacc.css"> <!-- Link to separate CSS file -->
    <script>
        function confirmDelete(email) {
            const confirmation = confirm(`Are you sure you want to delete the account with email: ${email}?`);
            if (confirmation) {
                // If confirmed, redirect to the delete script with the user's email
                window.location.href = `<?php echo $_SERVER['PHP_SELF']; ?>?email=` + encodeURIComponent(email);
            }
        }
    </script>
</head>
<body>
    <div class="app-bar">
        <a href="mainpage.html">
            <div><img src="assets/aid.png" alt="Logo"></div>
        </a>
        <nav>
            <button class="nav-btn new-button" onclick="window.location.href='../PrelimWEBSITE-main/accadmin.php';">Logout</button>
        </nav>
    </div>

    
    <div class="container">
        <h1>Welcome, Admin User!</h1>
        <h2>Registered Accounts:</h2>
        <div>
        <?php foreach ($users as $user): ?>
            <div class="accountbutton">
                <a href="#" onclick="alert('Email: <?php echo htmlspecialchars($user['email']); ?>\nName: <?php echo htmlspecialchars($user['name']); ?>\nBirthdate: <?php echo htmlspecialchars($user['birthdate']); ?>'); return false;" class="accContainer-button">
                    Email: <?php echo htmlspecialchars($user['email']); ?>, 
                    Name: <?php echo htmlspecialchars($user['name']); ?>, 
                    Birthdate: <?php echo htmlspecialchars($user['birthdate']); ?>
                </a>
                <button onclick="confirmDelete('<?php echo htmlspecialchars($user['email']); ?>');">Delete Account</button>
            </div>
        <?php endforeach; ?>
        </div>
        <?php if (isset($_GET['message'])): ?>
            <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>