<?php
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the form data
  $name = $_POST["name"];
  $bankacc = $_POST["bankacc"];
  $amount = $_POST["amount"];
  $email = $_POST["email"];

  // Validate the name field
  if (!preg_match('/^[a-zA-Z ]+$/', $name)) {
    echo 'Error: Name can only contain letters and spaces.';
    exit;
  }

  // Validate the bank account number field
  if (!preg_match('/^[0-9]+$/', $bankacc)) {
    echo 'Error: Bank account number can only contain numbers.';
    exit;
  }

  // Store the form data in session variables
  session_start();
  $_SESSION["name"] = $name;
  $_SESSION["bankacc"] = $bankacc;
  $_SESSION["amount"] = $amount;
  $_SESSION["email"] = $email;

  // Redirect to receipt.php
  header("Location: receipt.php");
  exit;
}

?>

<html>
<head>
  <title>Send Donations</title>
  <link rel="stylesheet" type="text/css" href="donations.css">
</head>
<body>
  <header>
    <div class="app-bar">
    <a href="mainpage.html">
  <div><img src="assets/aid.png" alt="Logo"></div>
</a>
      <nav>
        <button class="nav-btn emergency-planning" onclick="window.location.href='emergency planning.html';">Emergency Planning</button>    
        <button class="nav-btn disaster-management" onclick="window.location.href='disaster management.html';">Disaster Management</button>
        <button class="nav-btn resources" onclick="window.location.href='resources.html';">Resources</button>
        <button class="nav-btn charity-programs" onclick="window.location.href='charity.html';">Charity Programs</button>
        <button class="nav-btnn new-button" onclick="window.location.href='../PrelimWEBSITE-main/donations.php';">Send Donations</button>
      </nav>
    </div>
  </header>
  <div class="container">
    <h2>Your donation can make an impact!</h2>
    <p>Your support has the power to transform lives and bring hope to those in need. Every contribution, whether large or small, plays a crucial role in our mission. By donating today, you are not just giving money; you are investing in a brighter future for individuals and communities. Together, we can create lasting change and uplift those who need it most. Your generosity truly makes a difference!</p>
    <div class="form-image-wrapper">
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="donation-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br><br>
        <span id="name-error" style="color: red;"></span>
        <label for="bankacc">Bank Account Number:</label>
        <input type="text" id="bankacc" name="bankacc"><br><br>
        <span id="bankacc-error" style="color: red;"></span>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        <button style="text-decoration: none; font-family: 'UbuntuReg'; font-size: 22px; color: #F5F7F8; border-style: solid; border-width: 2px; border-color: #343A40; width: 90%; height: 60px; margin-right: 60px; background-color: #B14749; border-radius: 10px; transition: background-color .5s ease-in-out;">Donate Now</button>
      </form>
      <div class="image-and-icons-container">
        <div class="image-container">
          <img src="assets/hungry-children.jpg" alt="Donation Image">
        </div>
        <div class="icon-row">
          <img src="assets/mastercard.png" alt="Icon 1" class="icon">
          <img src="assets/paypal.png" alt="Icon 2" class="icon">
          <img src="assets/apple-pay.png" alt="Icon 3" class="icon">
        </div>
      </div>
    </div>
  </div>
  <script>
   const nameInput = document.getElementById('name');
    const nameError = document.getElementById('name-error');
    const bankaccInput = document.getElementById('bankacc');
    const bankaccError = document.getElementById('bankacc-error');
    const amountInput = document.getElementById('amount');
    const emailInput = document.getElementById('email');
    const form = document.getElementById('donation-form');

    nameInput.addEventListener('input', (e) => {
      const inputValue = e.target.value;
      const regex = /^[a-zA-Z ]+$/; // only allow letters and spaces

      if (!regex.test(inputValue)) {
        nameError.textContent = 'No special characters or numbers allowed';
      } else {
        nameError.textContent = '';
      }
    });

    bankaccInput.addEventListener('input', (e) => {
      const inputValue = e.target.value;
      const regex = /^[0-9]+$/; // only allow numbers

      if (!regex.test(inputValue)) {
        bankaccError.textContent = 'Only numbers are allowed';
      } else {
        bankaccError.textContent = '';
      }
    });

    form.addEventListener('submit', (e) => {
      if (nameInput.value === '' || bankaccInput.value === '' || amountInput.value === '' || emailInput.value === '') {
        e.preventDefault();
        alert('Please fill in all fields');
      }
    });
  </script>
</body>
</html>