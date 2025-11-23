<?php
// File: remove_from_cart.php
define('INCLUDED', true);
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$cart_item_id = $data['cart_item_id'];

$user_id = $_SESSION['user_id'];

// Make sure the item belongs to the user
$stmt = $conn->prepare("DELETE ci FROM cart_items ci JOIN carts c ON ci.cart_id = c.cart_id WHERE ci.cart_item_id = ? AND c.user_id = ?");
$stmt->bind_param('ii', $cart_item_id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error removing item from cart']);
}

$stmt->close();
?>