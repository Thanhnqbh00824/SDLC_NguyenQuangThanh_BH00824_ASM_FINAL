<?php
include('dbconnect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get order ID from URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    header('Location: history.php');
    exit;
}

// Fetch the order and its details
$query = "SELECT o.id, o.created_at, o.total, o.status, p.name AS product_name, od.amount, od.price
          FROM order_detail od
          JOIN orders o ON od.order_id = o.id
          JOIN products p ON od.product_id = p.id
          WHERE o.id = :order_id";
$stmt = $conn->prepare($query);
$stmt->execute([':order_id' => $order_id]);
$order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch order information
if ($order_details) {
    $order = $order_details[0];  // Get the order info from the first row
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="buy.css">
    <style>
    /* Nút Back ở góc trái của header */
    .back-btn {
        color: white; /* Màu chữ trắng */
        background-color: green; /* Nền màu xanh lá */
        padding: 10px 20px; /* Khoảng cách xung quanh chữ */
        border-radius: 5px; /* Bo góc */
        text-decoration: none; /* Xóa gạch chân */
        font-weight: bold; /* Chữ đậm */
        position: absolute; /* Định vị nút Back */
        left: 20px; /* Cách lề trái 20px */
        top: 20px; /* Cách lề trên 20px */
    }

    .back-btn:hover {
        background-color: blue; /* Màu nền khi hover */
    }

    header {
        position: relative; /* Định vị để chứa nút Back */
    }

    header h1 {
        text-align: center; /* Canh giữa tiêu đề */
    }
</style>
</head>
<body>
    <header>
        <a href="history.php" class="back-btn">Back</a> <!-- Nút Back -->
        <h1>Order Details - Order ID: <?php echo htmlspecialchars($order['id']); ?></h1>
    </header>

    <main>
        <section>
            <h2>Order Information</h2>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>
        </section>

        <section>
            <h2>Products in this Order</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_details as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($detail['amount']); ?></td>
                            <td>$<?php echo number_format($detail['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
