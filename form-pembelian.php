<?php
define('INCLUDED', true);
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $province = trim($_POST['province']);
    $city = trim($_POST['city']);
    $postcode = trim($_POST['postcode']);
    $address = trim($_POST['address']);

    // Validation
    if (empty($name) || empty($email) || empty($address)) {
        $error = 'Harap lengkapi semua field yang wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } else {
        // Process the order
        $conn->begin_transaction();
        try {
            // Calculate total from cart
            $total_query = "SELECT SUM(ci.kuantity * p.harga) as total 
                           FROM cart_items ci 
                           JOIN products p ON ci.produk_id = p.produk_id 
                           JOIN carts c ON ci.cart_id = c.cart_id 
                           WHERE c.user_id = ?";
            $total_stmt = $conn->prepare($total_query);
            $total_stmt->bind_param('i', $user_id);
            $total_stmt->execute();
            $total_result = $total_stmt->get_result();
            $total_row = $total_result->fetch_assoc();
            $total = $total_row['total'] ?: 0;
            $total_stmt->close();

            // Insert order
            $order_stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
            $order_stmt->bind_param('id', $user_id, $total);
            
            if (!$order_stmt->execute()) {
                throw new Exception("Error creating order");
            }
            
            $order_id = $conn->insert_id;
            $order_stmt->close();

            // Insert shipping information
            $shipping_stmt = $conn->prepare("INSERT INTO shipping (order_id, address, city, province, postal_code) VALUES (?, ?, ?, ?, ?)");
            $shipping_stmt->bind_param('issss', $order_id, $address, $city, $province, $postcode);
            
            if (!$shipping_stmt->execute()) {
                throw new Exception("Error saving shipping info");
            }
            $shipping_stmt->close();

            // Move items from cart to order items
            $cart_items_query = "SELECT ci.kuantity, p.produk_id, p.harga 
                                FROM cart_items ci 
                                JOIN products p ON ci.produk_id = p.produk_id 
                                JOIN carts c ON ci.cart_id = c.cart_id 
                                WHERE c.user_id = ?";
            $cart_items_stmt = $conn->prepare($cart_items_query);
            $cart_items_stmt->bind_param('i', $user_id);
            $cart_items_stmt->execute();
            $cart_items_result = $cart_items_stmt->get_result();

            while ($cart_item = $cart_items_result->fetch_assoc()) {
                $order_item_stmt = $conn->prepare("INSERT INTO order_items (pembelian_id, produk_id, kuantity, harga) VALUES (?, ?, ?, ?)");
                $order_item_stmt->bind_param('iiid', $order_id, $cart_item['produk_id'], $cart_item['kuantity'], $cart_item['harga']);
                
                if (!$order_item_stmt->execute()) {
                    throw new Exception("Error adding order item");
                }
                $order_item_stmt->close();
            }
            $cart_items_stmt->close();

            // Clear user's cart
            $clear_cart_stmt = $conn->prepare("DELETE ci FROM cart_items ci JOIN carts c ON ci.cart_id = c.cart_id WHERE c.user_id = ?");
            $clear_cart_stmt->bind_param('i', $user_id);
            $clear_cart_stmt->execute();
            $clear_cart_stmt->close();

            $conn->commit();
            $success = 'Pesanan berhasil dibuat!';
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage();
        }
    }
}

// Fetch user info
$user_sql = "SELECT name, email, phone FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembelian - TM Agro</title>
    <link rel="stylesheet" href="globals.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        agro: {
                            dark: '#1C352D',
                            green: '#446D60',
                            light: '#F2F2F2',
                            white: '#FFFFFF',
                        },
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-agro-dark min-h-screen">
    <?php include 'includes/header.php'; ?>

    <!-- Page Title -->
    <div class="px-4 md:px-8 lg:px-20 py-8">
        <h1 class="text-agro-light text-xl md:text-3xl font-semibold">Form Pembelian</h1>
    </div>

    <!-- Main Content -->
    <div class="px-4 md:px-8 lg:px-20 py-8">
        <?php if ($error): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                <?php echo htmlspecialchars($success); ?>
                <a href="index.php" class="ml-4 text-agro-green hover:underline">Lanjut Belanja</a>
            </div>
        <?php endif; ?>

        <div class="bg-white/95 rounded-[20px] p-6 md:p-12 mb-8">
            <form method="POST" action="" id="checkout-form">
                <!-- Personal Information Fields -->
                <div class="space-y-6 mb-8">
                    <!-- Nama Lengkap -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Nama Lengkap:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- Email / No.Hp -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Email / No.Hp:</label>
                        <input type="text" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9 hidden" />
                    </div>

                    <!-- Provinsi -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Provinsi:</label>
                        <input type="text" name="province" value=""
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- Kota -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Kota:</label>
                        <input type="text" name="city" value=""
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- Kode Pos -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Kode Pos:</label>
                        <input type="text" name="postcode" value=""
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Alamat Lengkap:</label>
                        <textarea name="address" rows="3"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- Order Details Section -->
                <div class="mt-12">
                    <h2 class="text-agro-dark/95 text-xl md:text-2xl font-semibold mb-6">Detail Pesanan</h2>

                    <div class="space-y-6">
                        <?php
                        // Get cart items to display
                        $cart_sql = "SELECT ci.kuantity, p.nama, p.deskripsi, p.harga, p.image_url 
                                    FROM cart_items ci 
                                    JOIN products p ON ci.produk_id = p.produk_id 
                                    JOIN carts c ON ci.cart_id = c.cart_id 
                                    WHERE c.user_id = ?";
                        $cart_stmt = $conn->prepare($cart_sql);
                        $cart_stmt->bind_param('i', $user_id);
                        $cart_stmt->execute();
                        $cart_result = $cart_stmt->get_result();
                        
                        if ($cart_result->num_rows > 0) {
                            while($cart_row = $cart_result->fetch_assoc()) {
                                echo '<div class="flex flex-col sm:flex-row gap-6 pb-6 border-b border-agro-dark/10">';
                                
                                // Product image
                                echo '<div class="w-full sm:w-[250px] h-[250px] flex-shrink-0 bg-agro-light rounded-[20px] flex items-center justify-center">';
                                if ($cart_row['image_url']) {
                                    echo '<img src="' . htmlspecialchars($cart_row['image_url']) . '" alt="' . htmlspecialchars($cart_row['nama']) . '" class="w-full h-full object-cover rounded-[20px]">';
                                } else {
                                    echo '<div class="w-full h-full bg-agro-green rounded-[20px] flex items-center justify-center">';
                                    echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                                    echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                                    echo '</svg>';
                                    echo '</div>';
                                }
                                echo '</div>';

                                // Product info
                                echo '<div class="flex-1">';
                                echo '<h3 class="text-agro-dark/95 text-xl md:text-2xl font-semibold mb-4">' . htmlspecialchars($cart_row['nama']) . '</h3>';
                                echo '<p class="text-agro-dark/70 text-sm md:text-base leading-relaxed">';
                                echo htmlspecialchars(substr($cart_row['deskripsi'], 0, 100)) . '...';
                                echo '</p>';
                                echo '</div>';

                                // Quantity and price
                                echo '<div class="flex sm:flex-col justify-between sm:justify-center items-end sm:items-center">';
                                echo '<span class="text-agro-dark/95 text-xl md:text-2xl font-semibold">x' . $cart_row['kuantity'] . '</span>';
                                echo '<span class="text-agro-dark/95 text-lg md:text-xl font-semibold mt-2">Rp' . number_format($cart_row['harga'] * $cart_row['kuantity'], 0, ',', '.') . '</span>';
                                echo '</div>';

                                echo '</div>';
                            }
                        } else {
                            echo '<div class="text-center py-8">';
                            echo '<p>Belum ada item dalam keranjang</p>';
                            echo '</div>';
                        }
                        $cart_stmt->close();
                        ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- Form Footer with Note and Button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 px-4">
            <p class="text-agro-light/70 text-sm md:text-base italic">
                *Harap cek ulang semua informasi sebelum memesan
            </p>

            <button type="submit"
                class="bg-white text-agro-dark hover:bg-agro-dark hover:text-white transition-colors rounded-[20px] px-6 py-4 text-base md:text-lg font-medium">
                Konfirmasi Pemesanan
            </button>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>

</html>