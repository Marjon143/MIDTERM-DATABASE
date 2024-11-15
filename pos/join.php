<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Page with Navbar</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS for styling */
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody td {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a href="#" class="nav-link" onclick="loadData('innerJoin')">INNER JOIN</a></li>
            <li class="nav-item"><a href="#" class="nav-link" onclick="loadData('customers')">CUSTOMERS</a></li>
            <li class="nav-item"><a href="#" class="nav-link" onclick="loadData('products')">PRODUCTS</a></li>
            <li class="nav-item"><a href="#" class="nav-link" onclick="loadData('outerJoin')">OUTER JOIN</a></li>
        </ul>
    </nav>

    <!-- Table to display data -->
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped" id="data-table">
            <thead id="table-head"></thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>
</div>

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    function loadData(type) {
        $.ajax({
            url: 'join.php',
            type: 'POST',
            data: { type: type },
            success: function(response) {
                // Load the entire table (head and body) structure into data-table
                $('#data-table').html(response);
            }
        });
    }
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "marjon";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Determine the query based on the requested type
    $type = $_POST['type'];
    $sql = "";

    switch ($type) {
        case 'innerJoin':
            $sql = "SELECT * FROM users INNER JOIN products ON users.product_id = products.id";
            break;
        case 'customers':
            $sql = "SELECT * FROM users"; // Fetch data from the 'users' table
            break;
        case 'products':
            $sql = "SELECT * FROM products"; // Fetch data from the 'products' table
            break;
        case 'outerJoin':
            $sql = "SELECT * FROM users LEFT JOIN products ON users.product_id = products.id";
            break;
        default:
            echo "<thead><tr><th>Error: Invalid Request</th></tr></thead><tbody></tbody>";
            $conn->close();
            exit;
    }

    if ($sql) {
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Build the complete table structure
            echo "<thead><tr>";
            while ($fieldinfo = $result->fetch_field()) {
                echo "<th>" . htmlspecialchars($fieldinfo->name) . "</th>";
            }
            echo "</tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
        } else {
            echo "<thead><tr><th>No Data Available</th></tr></thead><tbody></tbody>";
        }
    }

    $conn->close();
    exit; // End script execution for AJAX request
}
?>

</body>
</html>
