<?php
// Database connection settings
$host = 'localhost'; // or your database server
$username = 'root'; // your database username
$password = ''; // your database password
$database = 'marjon'; // your database name

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $payment_type = $_POST['payment_type'];
    $payment_number = $_POST['payment_number'];
    $expiry_date = $_POST['expiry_date'];
    $address = $_POST['address'];
    $amount = $_POST['amount'];

    // Prepare SQL query to insert payment data
    $sql = "INSERT INTO payments (payment_type, payment_number, expiry_date, address, amount)
            VALUES ('$payment_type', '$payment_number', '$expiry_date', '$address', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo "Payment information saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/payment.css">
    <title>Payment Page</title>
    <style>
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4); /* Black with opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function updatePaymentNumberPlaceholder() {
            const paymentType = document.getElementById("payment_type").value;
            const paymentNumber = document.getElementById("payment_number");
            
            if (paymentType === "gcash") {
                paymentNumber.placeholder = "Enter GCash Number";
            } else if (paymentType === "paymaya") {
                paymentNumber.placeholder = "Enter PayMaya Number";
            } else if (paymentType === "bank") {
                paymentNumber.placeholder = "Enter Bank Account Number";
            } else {
                paymentNumber.placeholder = "Enter Payment Number";
            }
        }

        // Show the modal when the "Pay Now" button is clicked
        function showModal(event) {
            event.preventDefault(); // Prevent the form from submitting immediately
            document.getElementById("confirmModal").style.display = "block";
        }

        // Hide the modal when the "Cancel" button is clicked
        function closeModal() {
            document.getElementById("confirmModal").style.display = "none";
        }

        // Submit the form when "Confirm" is clicked
        function confirmPayment() {
            document.getElementById("paymentForm").submit(); // Submit the form
        }
    </script>
</head>
<body>

<div class="payment-form">
    <h2>Payment Information</h2>
    <form id="paymentForm" action="" method="POST">
        <div class="form-group">
            <label for="payment_type">Type of Payment</label>
            <select id="payment_type" name="payment_type" onchange="updatePaymentNumberPlaceholder()" required>
                <option value="">Select Payment Type</option>
                <option value="gcash">GCash</option>
                <option value="paymaya">PayMaya</option>
                <option value="bank">Bank</option>
            </select>
        </div>
        <div class="form-group">
            <label for="payment_number">Payment Number</label>
            <input type="text" id="payment_number" name="payment_number" placeholder="Enter Payment Number" required>
        </div>
        <div class="form-group">
            <label for="expiry_date">Expiry Date</label>
            <input type="month" id="expiry_date" name="expiry_date" required>
        </div>
        <div class="form-group">
            <label for="address">Billing Address</label>
            <input type="text" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount ($)</label>
            <input type="number" id="amount" name="amount" min="1" step="0.01" required>
        </div>
        <button type="button" class="submit-button" onclick="showModal(event)">Pay Now</button>
    </form>
</div>

<!-- Modal -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Confirm Payment</h2>
        <p>Are you sure you want to submit the payment information?</p>
        <button onclick="confirmPayment()">Confirm</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

</body>
</html>
