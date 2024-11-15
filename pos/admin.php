<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

// Handle CRUD operations

// Create (Add Product)
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image = $_POST['image_url'];  // Get the image URL from the form input

    try {
        // Insert new product into the database with the image URL
        $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, description, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $stock, $description, $image]);

        // Redirect to the same page after adding the product
        header("Location: admin.php"); // Redirect to the same page to show new product
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}

// Update Product
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $image = $_POST['image_url'];  // Get the updated image URL from the form input

    try {
        // Update product information in the database with the image URL
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $price, $stock, $description, $image, $id]);

        echo "<script>alert('Product updated successfully!');</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        // Prepare the delete statement
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);

        // Redirect to the same page after deletion
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all products for displaying
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/admin.css">
    <title>Marjong Corner</title>
    <link rel="stylesheet" href="css/product.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <ul class="navbar-list">
        <li><a href="landing.php" class="navbar-link">Home</a></li>
        
    </ul>
</nav>

<!-- Page Title -->
<header>
    <h1 class="page-title">Admin Dashboard</h1>
</header>

<!-- Product Management Form (Add Product) -->
<h2>Add New Product</h2>
<form action="admin.php" method="POST">
    <input type="text" name="name" placeholder="Product Name" required><br>
    <input type="number" name="price" placeholder="Price" required><br>
    <input type="number" name="stock" placeholder="Stock" required><br>
    <textarea name="description" placeholder="Product Description" required></textarea><br>
    <input type="url" name="image_url" placeholder="Image URL" required><br> <!-- URL input for image -->
    <button type="submit" name="create">Add Product</button>
</form>

<hr>

<!-- Product List (Read & Update/Delete) -->
<h2>Manage Existing Products</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['id']) ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>₱<?= number_format($product['price'], 2) ?></td>
                <td><?= htmlspecialchars($product['stock']) ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>
                    <?php if ($product['image']): ?>
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" width="100">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Update and Delete Buttons -->
                    <form action="admin.php" method="POST">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input type="text" name="name" value="<?= $product['name'] ?>" required><br>
                        <input type="number" name="price" value="<?= $product['price'] ?>" required><br>
                        <input type="number" name="stock" value="<?= $product['stock'] ?>" required><br>
                        <textarea name="description" required><?= $product['description'] ?></textarea><br>
                        <input type="url" name="image_url" value="<?= $product['image'] ?>" placeholder="Image URL" required><br> <!-- URL input for image -->
                        <button type="submit" name="update">Update Product</button>
                    </form>
                    <br>
                    <a href="admin.php?delete=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
