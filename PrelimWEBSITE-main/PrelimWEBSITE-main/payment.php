<?php
session_start(); // Start the session to store form data

class Receipt {
    private $name;
    private $bankacc;
    private $amount;
    private $email;

    public function __construct($name, $bankacc, $amount, $email) {
        $this->name = $name;
        $this->bankacc = $bankacc;
        $this->amount = $amount;
        $this->email = $email;
    }

    public function generateReceipt() {
        
        $referenceNo = rand(10000000000, 99999999999);

        
        $receiptHtml = "
            <h2>Official Receipt</h2>
            <div class='receipt-info'>
                <p><strong><span style='color: #B14749;'>Name:</span> $this->name</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Bank Account Number:</span> $this->bankacc</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Amount (PHP):</span> $this->amount</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Email:</span> $this->email</strong></p>
                <hr>
                <p><strong><span style='color: #B14749;'>Reference No.:</span> $referenceNo</strong></p>
            </div>
        ";

        return $receiptHtml; 
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $amount = htmlspecialchars($_POST['amount']);
    $payment_method = htmlspecialchars($_POST['payment_method']);
    $contact_info = htmlspecialchars($_POST['contact_info']); 

    
    if (!preg_match("/^[a-zA-Z]+$/", $first_name) || !preg_match("/^[a-zA-Z]+$/", $last_name)) {
        echo "<h2>Error: First and last names should contain letters only (no special characters or numbers).</h2>";
        exit();
    }

    
    if (!preg_match("/^[0-9]+$/", str_replace('-', '', $contact_info))) {
        echo "<h2>Error: Contact information must be numeric (no letters or special characters allowed).</h2>";
        exit();
    }

    
    $bankacc = "1234567890"; 

    
    $_SESSION['full_name'] = "$first_name $last_name";
    $_SESSION['bankacc'] = $bankacc;
    $_SESSION['amount'] = $amount;
    $_SESSION['email'] = $email;

    
    $receipt = new Receipt($_SESSION['full_name'], $_SESSION['bankacc'], $_SESSION['amount'], $_SESSION['email']);
    
    
    header("Location: receipt-display.php");
    exit(); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link rel="stylesheet" href="payment.css"> 
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
</head>
<body>
    <div class="wrapper">
        <h2>Payment Form</h2>
        <form action="" method="post">
            
            <h4>Account Information</h4>
            <div class="input_group">
                <div class="input_box">
                    <input type="text" name="first_name" placeholder="First Name" required class="name">
                    <i class="fa fa-user icon"></i>
                </div>
                <div class="input_box">
                    <input type="text" name="last_name" placeholder="Last Name" required class="name">
                    <i class="fa fa-user icon"></i>
                </div>
                <div class="input_box">
                    <input type="email" name="email" placeholder="Email Address" required class="name">
                    <i class="fa fa-envelope icon"></i>
                </div>
            </div>

            
            <div class="input_group">
                <div class="input_box">
                    <h4>Payment Method</h4>
                    <input type="radio" name="payment_method" value="Credit Card" class="radio" id="bc1" checked onchange="toggleContactInfo()">
                    <label for="bc1"><span><i class="fa fa-cc-visa"></i> Credit Card</span></label>
                    <input type="radio" name="payment_method" value="GCash" class="radio" id="gcash" onchange="toggleContactInfo()">
                    <label for="gcash"><span><i class="fa fa-mobile"></i> GCash</span></label>
                    <input type="radio" name="payment_method" value="PayMaya" class="radio" id="paymaya" onchange="toggleContactInfo()">
                    <label for="paymaya"><span><i class="fa fa-mobile"></i> PayMaya</span></label>
                </div>
            </div>

            <div class="input_group" id="creditCardInfo" style="display: block;">
                <div class="input_box">
                    <input type="text" name="contact_info" placeholder="Card Number 1111-2222-3333-4444" class="name" required>
                    <i class="fa fa-credit-card icon"></i>
                </div>
            </div>

            <div class="input_group" id="mobileContactInfo" style="display: none;">
                <div class="input_box">
                    <input type="tel" name="contact_info" placeholder="Mobile Number" class="name" required>
                    <i class="fa fa-mobile icon"></i>
                </div>
            </div>

            <div class="input_group">
                <div class="input_box">
                    <input type="number" name="amount" placeholder="Enter Amount (250, 500, 1000, or custom)" required class="name">
                    <i class="fa fa-money icon"></i>
                </div>
            </div>
            

            <div class="input_group">
                <div class="input_box">
                    <button type="submit">PAY NOW</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleContactInfo() {
            const creditCardInfo = document.getElementById('creditCardInfo');
            const mobileContactInfo = document.getElementById('mobileContactInfo');
            
            if (document.getElementById('bc1').checked) {
                creditCardInfo.style.display = 'block';
                mobileContactInfo.style.display = 'none';
            } else {
                creditCardInfo.style.display = 'none';
                mobileContactInfo.style.display = 'block';
            }
        }
    </script>
</body>
</html>
