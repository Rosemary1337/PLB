<?php
define('INCLUDED', true);
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user has admin role
if ($_SESSION['admin_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Get dashboard statistics
$users_count = 0;
$products_count = 0;
$orders_count = 0;
$revenue = 0;

// Count users
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $users_count = $row['count'];
}
$stmt->close();

// Count products
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $products_count = $row['count'];
}
$stmt->close();

// Count orders
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM orders");
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $orders_count = $row['count'];
}
$stmt->close();

// Calculate revenue
$stmt = $conn->prepare("SELECT SUM(total) as revenue FROM orders WHERE status = 'paid'");
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $revenue = $row['revenue'] ? $row['revenue'] : 0;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - TM Agro</title>
    <link rel="stylesheet" href="../globals.css" />
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

<body class="bg-agro-light min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white h-[75px] fixed top-0 left-0 right-0 z-50 shadow-sm">
        <div class="max-w-[1900px] mx-auto px-4 h-full flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="../index.php" class="w-[140px] h-[40px] flex items-center justify-center">
                    <img src="../images/logo.png" alt="TM Agro Logo" class="w-full h-full object-contain">
                </a>
                <h1 class="text-agro-dark text-xl font-semibold">Dashboard Admin</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-agro-dark">
                    Selamat datang, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
                </div>
                <a href="../logout.php" 
                    class="w-[50px] h-[50px] flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors text-agro-dark text-base">
                    <i class="fa-solid fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex pt-[75px]">
        <!-- Sidebar -->
        <aside class="w-64 bg-agro-dark min-h-screen p-4">
            <nav class="mt-8">
                <a href="index.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2 bg-agro-green">
                    <i class="fa-solid fa-chart-line mr-3"></i> Dashboard
                </a>
                <a href="manage_users.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2">
                    <i class="fa-solid fa-users mr-3"></i> Kelola Pengguna
                </a>
                <a href="manage_products.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2">
                    <i class="fa-solid fa-box mr-3"></i> Kelola Produk
                </a>
                <a href="manage_orders.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2">
                    <i class="fa-solid fa-shopping-cart mr-3"></i> Kelola Pesanan
                </a>
                <a href="manage_payments.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2">
                    <i class="fa-solid fa-credit-card mr-3"></i> Kelola Pembayaran
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-agro-dark mb-2">Dashboard Admin</h1>
                <p class="text-agro-dark/70">Selamat datang di panel administrasi TM Agro</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Users Card -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-agro-green/10 mr-4">
                            <i class="fa-solid fa-users text-agro-green text-xl"></i>
                        </div>
                        <div>
                            <p class="text-agro-dark/70">Pengguna</p>
                            <p class="text-2xl font-bold text-agro-dark"><?php echo $users_count; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-agro-green/10 mr-4">
                            <i class="fa-solid fa-box text-agro-green text-xl"></i>
                        </div>
                        <div>
                            <p class="text-agro-dark/70">Produk</p>
                            <p class="text-2xl font-bold text-agro-dark"><?php echo $products_count; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Orders Card -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-agro-green/10 mr-4">
                            <i class="fa-solid fa-shopping-cart text-agro-green text-xl"></i>
                        </div>
                        <div>
                            <p class="text-agro-dark/70">Pesanan</p>
                            <p class="text-2xl font-bold text-agro-dark"><?php echo $orders_count; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-agro-green/10 mr-4">
                            <i class="fa-solid fa-money-bill-wave text-agro-green text-xl"></i>
                        </div>
                        <div>
                            <p class="text-agro-dark/70">Pendapatan</p>
                            <p class="text-2xl font-bold text-agro-dark">Rp <?php echo number_format($revenue, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white rounded-xl shadow p-6 mb-8">
                <h2 class="text-xl font-bold text-agro-dark mb-4">Pesanan Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-agro-light">
                                <th class="text-left py-3 text-agro-dark">ID</th>
                                <th class="text-left py-3 text-agro-dark">Pengguna</th>
                                <th class="text-left py-3 text-agro-dark">Total</th>
                                <th class="text-left py-3 text-agro-dark">Status</th>
                                <th class="text-left py-3 text-agro-dark">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("
                                SELECT o.order_id, u.name, o.total, o.status, o.tanggal_pembelian 
                                FROM orders o 
                                LEFT JOIN users u ON o.user_id = u.user_id 
                                ORDER BY o.tanggal_pembelian DESC 
                                LIMIT 5
                            ");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr class="border-b border-agro-light/30">';
                                    echo '<td class="py-3 text-agro-dark">' . $row['order_id'] . '</td>';
                                    echo '<td class="py-3 text-agro-dark">' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td class="py-3 text-agro-dark">Rp ' . number_format($row['total'], 0, ',', '.') . '</td>';
                                    echo '<td class="py-3 text-agro-dark"><span class="px-2 py-1 rounded-full text-xs ' . 
                                         ($row['status'] === 'paid' ? 'bg-green-100 text-green-800' : 
                                          ($row['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-gray-100 text-gray-800')) . '">' . 
                                         ucfirst($row['status']) . '</span></td>';
                                    echo '<td class="py-3 text-agro-dark">' . date('d M Y', strtotime($row['tanggal_pembelian'])) . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="py-3 text-center text-agro-dark">Tidak ada pesanan</td></tr>';
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-agro-dark mb-4">Pengguna Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-agro-light">
                                <th class="text-left py-3 text-agro-dark">ID</th>
                                <th class="text-left py-3 text-agro-dark">Nama</th>
                                <th class="text-left py-3 text-agro-dark">Email</th>
                                <th class="text-left py-3 text-agro-dark">Role</th>
                                <th class="text-left py-3 text-agro-dark">Tanggal Registrasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT user_id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr class="border-b border-agro-light/30">';
                                    echo '<td class="py-3 text-agro-dark">' . $row['user_id'] . '</td>';
                                    echo '<td class="py-3 text-agro-dark">' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td class="py-3 text-agro-dark">' . htmlspecialchars($row['email']) . '</td>';
                                    echo '<td class="py-3 text-agro-dark"><span class="px-2 py-1 rounded-full text-xs ' . 
                                         ($row['role'] === 'admin' ? 'bg-red-100 text-red-800' : 
                                          ($row['role'] === 'petani' ? 'bg-blue-100 text-blue-800' : 
                                           'bg-gray-100 text-gray-800')) . '">' . 
                                         ucfirst($row['role']) . '</span></td>';
                                    echo '<td class="py-3 text-agro-dark">' . date('d M Y', strtotime($row['created_at'])) . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5" class="py-3 text-center text-agro-dark">Tidak ada pengguna</td></tr>';
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>

</html>