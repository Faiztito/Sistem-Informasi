<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth.php");
    exit();
}

// Determine which page to show
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Simulasi data statistik (ganti dengan query database sesuai kebutuhan)
$total_products = 500;
$total_categories = 3;
$total_sold = 125;
$total_revenue = 70.00;

// Sample product data
$products = [
    ['category' => 'Boju', 'description' => 'All sort of Accessories.'],
    ['category' => 'Celana', 'description' => 'All sort of Decorations.'],
    ['category' => 'Kemeja', 'description' => 'All sort of Gowns.']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UThrift - Admin <?php echo ucfirst($page); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f6f3;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: #8B5A2B;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }
        .sidebar h2 {
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
        }
        .sidebar a {
            text-decoration: none;
            color: white;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sidebar a:hover {
            color: #fff;
            text-decoration: underline;
        }
        .content {
            flex: 1;
            padding: 20px;
            margin-left: 240px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f0ebe0;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .header h3 {
            margin: 0;
            color: #333;
        }
        .header img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .stat-card h4 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #666;
        }
        .stat-card p {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        
        /* Products Page Styles */
        .products-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .products-container h3 {
            margin-top: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
            font-weight: 600;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-btn {
            padding: 5px 10px;
            background-color: #b8a894;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 5px;
        }
        .action-btn:hover {
            background-color: #a89984;
        }
        .new-product {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .new-product h4 {
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>UThrift</h2>
            <ul>
                 <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="produk.php">Products</a></li>
                <li><a href="transaksi.php">Transactions</a></li>
                <li><a href="../logout.php">Log Out</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Header -->
            <div class="header">
                <h3>Halo, <?php echo htmlspecialchars($_SESSION['admin']['username'] ?? 'Admin'); ?>!</h3>
                <div>
                    <img src="asset/avatar.png" alt="Profile Picture">
                </div>
            </div>

            <?php if ($page == 'dashboard'): ?>
                <!-- Dashboard Content -->
                <div class="stats-container">
                    <div class="stat-card">
                        <h4>Total Products</h4>
                        <p><?php echo $total_products; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Total Categories</h4>
                        <p><?php echo $total_categories; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Item Sold</h4>
                        <p><?php echo $total_sold; ?></p>
                    </div>
                    <div class="stat-card">
                        <h4>Total Revenue</h4>
                        <p>$<?php echo number_format($total_revenue, 2); ?></p>
                    </div>
                </div>
            <?php elseif ($page == 'products'): ?>
                <!-- Products Content -->
                <div class="products-container">
                    <h3>Products List</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td>
                                    <button class="action-btn">Edit</button>
                                    <button class="action-btn">Hopus</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="new-product">
                        <h4>New Product</h4>
                        <!-- Add form for new product here -->
                    </div>
                </div>
            <?php elseif ($page == 'transactions'): ?>
                <!-- Transactions Content -->
                <div class="products-container">
                    <h3>Transactions</h3>
                    <p>Transaction content goes here...</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>