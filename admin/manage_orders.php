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

$error = '';
$success = '';

// Handle order updates
if (isset($_POST['update_order'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    
    // Validate inputs
    if (!in_array($status, ['pending', 'paid', 'shipped', 'completed', 'cancelled'])) {
        $error = 'Status pesanan tidak valid';
    } else {
        // Update order
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param('si', $status, $order_id);
        
        if ($stmt->execute()) {
            $success = 'Pesanan berhasil diperbarui';
        } else {
            $error = 'Terjadi kesalahan saat memperbarui pesanan';
        }
        $stmt->close();
    }
}

// Get all orders with user information
$orders = [];
$stmt = $conn->prepare("
    SELECT o.order_id, u.name as user_name, o.total, o.status, o.tanggal_pembelian
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.user_id
    ORDER BY o.tanggal_pembelian DESC
");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Dashboard Admin TM Agro</title>
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
                <h1 class="text-agro-dark text-xl font-semibold">Kelola Pesanan</h1>
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
                <a href="index.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2">
                    <i class="fa-solid fa-chart-line mr-3"></i> Dashboard
                </a>
                <a href="manage_users.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2">
                    <i class="fa-solid fa-users mr-3"></i> Kelola Pengguna
                </a>
                <a href="manage_products.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2">
                    <i class="fa-solid fa-box mr-3"></i> Kelola Produk
                </a>
                <a href="manage_orders.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2 bg-agro-green">
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
                <h1 class="text-2xl font-bold text-agro-dark mb-2">Kelola Pesanan</h1>
                <p class="text-agro-dark/70">Atur dan kelola pesanan di platform TM Agro</p>
            </div>
            
            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <!-- Orders Table -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-agro-dark mb-4">Daftar Pesanan</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-agro-light">
                                <th class="text-left py-3 text-agro-dark">ID</th>
                                <th class="text-left py-3 text-agro-dark">Pengguna</th>
                                <th class="text-left py-3 text-agro-dark">Total</th>
                                <th class="text-left py-3 text-agro-dark">Status</th>
                                <th class="text-left py-3 text-agro-dark">Tanggal</th>
                                <th class="text-left py-3 text-agro-dark">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr class="border-b border-agro-light/30">
                                <td class="py-3 text-agro-dark"><?php echo $order['order_id']; ?></td>
                                <td class="py-3 text-agro-dark"><?php echo htmlspecialchars($order['user_name']); ?></td>
                                <td class="py-3 text-agro-dark">Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></td>
                                <td class="py-3 text-agro-dark">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        <?php echo $order['status'] === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($order['status'] === 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                                ($order['status'] === 'completed' ? 'bg-purple-100 text-purple-800' : 
                                                 ($order['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                  'bg-yellow-100 text-yellow-800'))); ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td class="py-3 text-agro-dark"><?php echo date('d M Y H:i', strtotime($order['tanggal_pembelian'])); ?></td>
                                <td class="py-3 text-agro-dark">
                                    <button onclick="editOrder(<?php echo $order['order_id']; ?>, '<?php echo $order['status']; ?>')" 
                                        class="text-agro-green hover:text-[#3a5d50]">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <?php if (empty($orders)): ?>
                        <p class="text-center py-4 text-agro-dark">Tidak ada pesanan</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Edit Order Modal -->
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 w-11/12 md:w-1/3">
                    <h2 class="text-xl font-bold text-agro-dark mb-4">Edit Status Pesanan</h2>
                    <form method="POST" id="editForm">
                        <input type="hidden" name="order_id" id="edit_order_id">
                        <div class="mb-4">
                            <label class="block text-agro-dark mb-2">Status</label>
                            <select name="status" id="edit_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="shipped">Shipped</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 border border-gray-300 text-agro-dark rounded-lg">
                                Batal
                            </button>
                            <button type="submit" name="update_order" class="bg-agro-green hover:bg-[#3a5d50] text-white py-2 px-4 rounded-lg">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        function editOrder(id, status) {
            document.getElementById('edit_order_id').value = id;
            document.getElementById('edit_status').value = status;
            
            document.getElementById('editModal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        // Close modal if clicked outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>