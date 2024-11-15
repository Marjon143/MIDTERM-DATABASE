<?php
// Database connection settings
$host = 'localhost';    // Replace with your host
$dbname = 'marjon';     // Replace with your database name
$username = 'root';     // Replace with your database username
$password = '';         // Replace with your database password

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch data from products table with a LEFT JOIN to users table
    $stmt = $pdo->query("
        SELECT p.id, p.name, p.price, p.stock, p.description, p.image, u.name AS user_name, u.email AS user_email
        FROM products p
        LEFT JOIN users u ON p.user_id = u.id
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        .body {
            background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSdgGuOCx1GDUJrjk2l9ZRk334IuCgRQPvj2Q&s'); /* Path to your image */
            background-repeat: no-repeat; /* Prevents repeating the image */
            background-size: cover; /* Scales the image to cover the entire element */
            background-position: center; /* Centers the image within the element */
            height: 300px; /* Example height of the element */
            width: 100%; /* Example width of the element */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        h1 {
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <h1>Products with User Info</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Description</th>
                <th>Image</th>
                <th>User Name</th>
                <th>User Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?></td>
                        <td><?= htmlspecialchars($product['stock']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="product-image">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['user_name']) ? htmlspecialchars($product['user_name']) : 'No User' ?></td>
                        <td><?= htmlspecialchars($product['user_email']) ? htmlspecialchars($product['user_email']) : 'No Email' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <nav class="navbar">
        <ul class="navbar-list">
            <li><a href="dashboard.php" class="navbar-link">Back</a></li>
        </ul>
    </nav>
</body>
</html>
