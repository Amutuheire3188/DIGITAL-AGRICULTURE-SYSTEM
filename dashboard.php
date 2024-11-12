<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Welcome to your Dashboard</h2>

    <?php if ($role == 'chief_farmer'): ?>
        <a href="record_product.php" class="btn btn-success mb-3">Record New Product</a>

    <?php else: ?>
        <h3>Your Products</h3>
        <table class="table">
            <tr>
                <th>Product Name</th>
                <th>Amount (kg)</th>
            </tr>
            <?php
            $stmt = $conn->prepare("SELECT * FROM products WHERE farmer_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['product_name']}</td><td>{$row['amount_kgs']}</td></tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
