<?php
// Database configuration
$host = 'localhost'; 
$dbname = 'marjon'; 
$username = 'root'; 
$password = ''; 

// Create a PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Fetch the list of products from the database to display
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <link rel="stylesheet" href="css/product.css">
    <style>
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #888;
        }
        .modal-header {
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        .modal-footer {
            text-align: right;
        }
        .modal-btn {
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
        }
        .confirm-btn {
            background-color: green;
            color: white;
        }
        .cancel-btn {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>

  <!-- Page Title -->
  <header>
    <h1 class="page-title">MARJONGS CORNER</h1>
  </header>

  <!-- Catalog Container -->
  <div class="catalog-container">
    <?php foreach ($products as $product): ?>
      <div class="product-card">
        <!-- Display product image from online URL -->
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p class="price">₱<?= number_format($product['price'], 2) ?></p>
        <p class="details"><?= htmlspecialchars($product['description']) ?></p> <!-- Product Description -->
        <p class="availability">Available: <?= $product['stock'] ?></p>

        <!-- Purchase Section -->
        <form action="payment.php" method="POST" class="purchase-form" id="form-<?= $product['id'] ?>">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <div class="purchase-section">
            <input type="number" min="1" max="10" value="1" name="quantity" class="quantity-input" id="quantity-<?= $product['id'] ?>">
            <button type="button" class="buy-btn" onclick="showModal(<?= $product['id'] ?>)">BUY</button>
          </div>
        </form>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Modal for Confirmation -->
  <div id="confirmation-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Confirm Purchase</h2>
      </div>
      <p id="modal-message">Are you sure you want to purchase this item?</p>
      <div class="modal-footer">
        <button class="modal-btn cancel-btn" onclick="closeModal()">Cancel</button>
        <button class="modal-btn confirm-btn" id="confirm-btn" onclick="confirmPurchase()">Confirm</button>
      </div>
    </div>
  </div>

  <!-- Back Button below the catalog -->
  <div class="back-button-container">
      <a href="javascript:history.back()" class="back-btn">Back</a>
  </div>

  <script>
    let currentForm = null;

    function showModal(productId) {
        // Get the form and quantity details
        currentForm = document.getElementById('form-' + productId);
        const quantity = document.getElementById('quantity-' + productId).value;
        const productName = currentForm.querySelector('h3').innerText;
        
        // Set the message in the modal
        document.getElementById('modal-message').innerText = `Are you sure you want to purchase ${quantity} x ${productName}?`;

        // Show the modal
        document.getElementById('confirmation-modal').style.display = "block";
    }

    function closeModal() {
        // Hide the modal
        document.getElementById('confirmation-modal').style.display = "none";
    }

    function confirmPurchase() {
        // Hide the modal
        document.getElementById('confirmation-modal').style.display = "none";

        // Submit the form after confirmation
        currentForm.submit();
    }
  </script>

</body>
</html>
