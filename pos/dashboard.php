<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marjon";  // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the total number of users
$sql_users = "SELECT COUNT(*) AS user_count FROM users"; // Replace 'users' with your actual table name
$result_users = $conn->query($sql_users);

$user_count = 0;
if ($result_users->num_rows > 0) {
    // Fetch the result
    $row = $result_users->fetch_assoc();
    $user_count = $row['user_count'];
}

// Query to get the total number of products (all products)
$sql_products = "SELECT COUNT(*) AS total_products FROM products"; // Count all products
$result_products = $conn->query($sql_products);

$total_products = 0;
if ($result_products->num_rows > 0) {
    // Fetch the result
    $row = $result_products->fetch_assoc();
    $total_products = $row['total_products'];
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sale</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

    <!-- for header part -->
    <header>
        <div class="logosec">
            <div class="logo">Marjong Shop</div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182541/Untitled-design-(30).png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>

        <div class="searchbar">
            <input type="text" placeholder="Search">
            <div class="searchbtn">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180758/Untitled-design-(28).png" class="icn srchicn" alt="search-icon">
            </div>
        </div>

        <div class="message">
            <div class="circle"></div>
            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183322/8.png" class="icn" alt="">
            <div class="dp">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180014/profile-removebg-preview.png" class="dpicn" alt="dp">
            </div>
            
            <!-- Join button -->
            <div class="dropdown">
        <button class="join-btn">Join</button>
        <div class="dropdown-content">
            <a href="cust.php">RIGHT JOIN</a>
            <a href="prod.php">LEFT JOIN</a>
            <a href="outer.php">OUTER JOIN</a>
        </div>
    </div>
        </div>
    </header>

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="nav-upper-options">
                    <div class="nav-option option1">
                        <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210182148/Untitled-design-(29).png" class="nav-img" alt="dashboard">
                        <h3> Dashboard</h3>
                    </div>

                    <div class="nav-option option3">
                        <a href="landing.php">
                            <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183320/5.png" class="nav-img" alt="home">
                            <h3> Home</h3>
                        </a>
                    </div>

                    <div class="nav-option logout">
                        <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210183321/7.png" class="nav-img" alt="logout" onclick="showLogoutModal()">
                        <h3><a href="javascript:void(0);" onclick="showLogoutModal()">Logout</a></h3>
                    </div>

                </div>
            </nav>
        </div>

        <div class="main">

            <div class="searchbar2">
                <input type="text" placeholder="Search">
                <div class="searchbtn">
                    <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210180758/Untitled-design-(28).png" class="icn srchicn" alt="search-button">
                </div>
            </div>

            <div class="box-container">

                <!-- Available Products Box -->
                <div class="box box1">
                    <div class="text">
                        <h2 class="topic-heading"><?php echo number_format($total_products); ?></h2>
                        <h2 class="topic">Total Products</h2>
                    </div>
                    <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210184645/Untitled-design-(31).png" alt="Views">
                </div>

                <!-- Display User Count -->
                <div class="box box3">
                    <div class="text">
                        <h2 class="topic-heading"><?php echo number_format($user_count); ?></h2>
                        <h2 class="topic">Users</h2>
                    </div>
                    <img src="https://media.geeksforgeeks.org/wp-content/uploads/20221210185029/13.png" alt="published">
                </div>
            </div>

            <div class="report-container">
                <div class="report-header">
                    <h1 class="recent-Articles">Today's Report</h1>
                    <button class="view">View All</button>
                </div>

                <div class="report-body">
                    <div class="report-topic-heading">
                        <h3 class="t-op">Products</h3>
                        <h3 class="t-op">Price</h3>
                        <h3 class="t-op">Status</h3>
                    </div>
                    <!-- You can add dynamic PHP content here, like fetching product data from a database -->
                    <?php
                    // Example of dynamically fetching report data
                    /*
                    $products = getTodaysReport(); // Fetch the data (for example, from a database)
                    foreach ($products as $product) {
                        echo "<div class='report-item'>";
                        echo "<h3>" . $product['name'] . "</h3>";
                        echo "<h3>" . $product['price'] . "</h3>";
                        echo "<h3>" . $product['status'] . "</h3>";
                        echo "</div>";
                    }
                    */
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for logout confirmation -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeLogoutModal()">&times;</span>
            <h2>Are you sure you want to log out?</h2>
            <div class="modal-actions">
                <button onclick="logout()">Yes, Logout</button>
                <button onclick="closeLogoutModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script src="./index.js"></script>

    <script>
        // Function to show the logout confirmation modal
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }

        // Function to close the logout confirmation modal
        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        // Function to handle logout, redirecting to index.php
        function logout() {
            window.location.href = 'index.php'; // Redirect to the index page
        }

        // Close modal if the user clicks outside the modal content
        window.onclick = function(event) {
            if (event.target === document.getElementById('logoutModal')) {
                closeLogoutModal();
            }
        }
    </script>

    <!-- Style for the modal and join button -->
    <style>
        /* Modal styles */
        
    </style>

</body>

</html>
