<?php
// File: add_to_cart.php
define('INCLUDED', true);
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'];
$quantity = $data['quantity'] ?? 1;

$user_id = $_SESSION['user_id'];

// Start transaction
$conn->begin_transaction();

try {
    // Get or create user cart
    $cart_stmt = $conn->prepare("SELECT cart_id FROM carts WHERE user_id = ? LIMIT 1");
    $cart_stmt->bind_param('i', $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    
    if ($cart_result->num_rows > 0) {
        $cart = $cart_result->fetch_assoc();
        $cart_id = $cart['cart_id'];
    } else {
        // Create new cart
        $create_cart_stmt = $conn->prepare("INSERT INTO carts (user_id) VALUES (?)");
        $create_cart_stmt->bind_param('i', $user_id);
        $create_cart_stmt->execute();
        $cart_id = $conn->insert_id;
        $create_cart_stmt->close();
    }
    $cart_stmt->close();
    
    // Check if product is already in cart
    $check_stmt = $conn->prepare("SELECT cart_item_id, kuantity FROM cart_items WHERE cart_id = ? AND produk_id = ?");
    $check_stmt->bind_param('ii', $cart_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Update quantity if already in cart
        $existing_item = $check_result->fetch_assoc();
        $new_quantity = $existing_item['kuantity'] + $quantity;
        $update_stmt = $conn->prepare("UPDATE cart_items SET kuantity = ? WHERE cart_item_id = ?");
        $update_stmt->bind_param('ii', $new_quantity, $existing_item['cart_item_id']);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Add new item to cart
        $insert_stmt = $conn->prepare("INSERT INTO cart_items (cart_id, produk_id, kuantity) VALUES (?, ?, ?)");
        $insert_stmt->bind_param('iii', $cart_id, $product_id, $quantity);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $check_stmt->close();
    
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Product added to cart']);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error adding product to cart']);
}
?>