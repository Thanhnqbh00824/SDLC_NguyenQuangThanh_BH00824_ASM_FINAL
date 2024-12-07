<?php
include('dbconnect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch all orders for the logged-in user
$query = "SELECT o.id, o.created_at, o.total, o.status FROM orders o WHERE o.user_id = :user_id ORDER BY o.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->execute([':user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" href="buy.css">
    <style>
        /* Định dạng nút Back */
        .back-btn {
            color: white;
            background-color: green;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            position: absolute;
            left: 20px;
            top: 20px;
        }

        .back-btn:hover {
            background-color: blue;
        }

        /* CSS cho "View Details" */
        a.view-details {
            display: inline-block; /* Hiển thị dưới dạng nút */
    background-color: green; /* Màu nền xanh lá cây */
    color: white; /* Màu chữ trắng */
    padding: 10px 15px; /* Khoảng cách xung quanh chữ */
    border-radius: 5px; /* Bo góc nút */
    text-decoration: none; /* Không gạch chân */
    font-weight: bold; /* Chữ đậm */
    text-align: center; /* Canh giữa chữ trong nút */
    transition: background-color 0.3s ease; /* Hiệu ứng hover */
        }

        a.view-details:hover {
            background-color: blue; /* Màu nền xanh dương khi hover */
    color: white; /* Đảm bảo màu chữ luôn là trắng */
    text-decoration: none; /* Không gạch chân khi hover */
        }
    </style>
</head>

<body>
    <header>
        <h1>Your Transaction History</h1>
        <!-- Nút Back -->
        <a href="buy.php" class="back-btn">Back</a>
    </header>

    <main>
        <section>
            <h2>Previous Orders</h2>
            <?php if (count($orders) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['id']); ?></td>
                                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                <td>$<?php echo number_format($order['total'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="view-details">View
                                        Details</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no past orders.</p>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>