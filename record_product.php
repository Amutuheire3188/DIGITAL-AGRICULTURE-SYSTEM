<?php
session_start();
include 'config.php';

if ($_SESSION['role'] != 'chief_farmer') {
    header("Location: dashboard.php");
    exit;
}

// Get the list of farmers for the select dropdown
$stmt = $conn->prepare("SELECT id, username FROM users WHERE role = 'farmer'");
$stmt->execute();
$farmers = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $amount_kgs = $_POST['amount_kgs'];
    $farmer_id = $_POST['farmer_id']; // Farmer ID is now selected from the dropdown

    // Insert the product record into the database
    $stmt = $conn->prepare("INSERT INTO products (farmer_id, product_name, amount_kgs) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $farmer_id, $product_name, $amount_kgs);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Product recorded successfully!";
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Record New Product</h2>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <form action="record_product.php" method="POST">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" required>
        </div>
        <div class="form-group">
            <label for="amount_kgs">Amount (kg)</label>
            <input type="number" class="form-control" id="amount_kgs" name="amount_kgs" required>
        </div>
        <div class="form-group">
            <label for="farmer_id">Select Farmer</label>
            <select class="form-control" id="farmer_id" name="farmer_id" required>
                <option value="">Select a Farmer</option>
                <?php while ($farmer = $farmers->fetch_assoc()): ?>
                    <option value="<?php echo $farmer['id']; ?>"><?php echo $farmer['username']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Record Product</button>
    </form>
</div>
</body>
</html>
