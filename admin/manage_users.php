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

// Handle user updates
if (isset($_POST['update_user'])) {
    $user_id = (int)$_POST['user_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    // Validate inputs
    if (empty($name) || empty($email)) {
        $error = 'Nama dan email harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } else {
        // Update user
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, phone = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param('sssssi', $name, $email, $role, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Pengguna berhasil diperbarui';
        } else {
            $error = 'Terjadi kesalahan saat memperbarui pengguna';
        }
        $stmt->close();
    }
}

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['user_id'];
    
    // Delete user (will cascade to related records)
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    
    if ($stmt->execute()) {
        $success = 'Pengguna berhasil dihapus';
    } else {
        $error = 'Terjadi kesalahan saat menghapus pengguna';
    }
    $stmt->close();
}

// Handle user addition
if (isset($_POST['add_user'])) {
    $name = trim($_POST['new_name']);
    $email = trim($_POST['new_email']);
    $password = $_POST['new_password'];
    $role = $_POST['new_role'];
    $phone = trim($_POST['new_phone']);
    $address = trim($_POST['new_address']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Nama, email, dan password harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email sudah terdaftar';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param('ssssss', $name, $email, $hashed_password, $phone, $address, $role);
            
            if ($insert_stmt->execute()) {
                $success = 'Pengguna baru berhasil ditambahkan';
            } else {
                $error = 'Terjadi kesalahan saat menambahkan pengguna';
            }
            $insert_stmt->close();
        }
        $stmt->close();
    }
}

// Get all users
$users = [];
$stmt = $conn->prepare("
    SELECT user_id, name, email, role, phone, address, created_at 
    FROM users 
    ORDER BY created_at DESC
");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Dashboard Admin TM Agro</title>
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
                <h1 class="text-agro-dark text-xl font-semibold">Kelola Pengguna</h1>
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
                <a href="manage_users.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2 bg-agro-green">
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
                <h1 class="text-2xl font-bold text-agro-dark mb-2">Kelola Pengguna</h1>
                <p class="text-agro-dark/70">Atur dan kelola akun pengguna di platform TM Agro</p>
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
            
            <!-- Add User Form -->
            <div class="bg-white rounded-xl shadow p-6 mb-8">
                <h2 class="text-xl font-bold text-agro-dark mb-4">Tambah Pengguna Baru</h2>
                <form method="POST" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-agro-dark mb-2">Nama Lengkap</label>
                            <input type="text" name="new_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">Email</label>
                            <input type="email" name="new_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">Password</label>
                            <input type="password" name="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">Role</label>
                            <select name="new_role" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                <option value="pembeli">Pembeli</option>
                                <option value="petani">Petani</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">No. HP</label>
                            <input type="text" name="new_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">Alamat</label>
                            <input type="text" name="new_address" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                    <button type="submit" name="add_user" class="bg-agro-green hover:bg-[#3a5d50] text-white font-bold py-2 px-4 rounded-lg">
                        Tambah Pengguna
                    </button>
                </form>
            </div>
            
            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-agro-dark mb-4">Daftar Pengguna</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-agro-light">
                                <th class="text-left py-3 text-agro-dark">ID</th>
                                <th class="text-left py-3 text-agro-dark">Nama</th>
                                <th class="text-left py-3 text-agro-dark">Email</th>
                                <th class="text-left py-3 text-agro-dark">Role</th>
                                <th class="text-left py-3 text-agro-dark">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="border-b border-agro-light/30">
                                <td class="py-3 text-agro-dark"><?php echo $user['user_id']; ?></td>
                                <td class="py-3 text-agro-dark"><?php echo htmlspecialchars($user['name']); ?></td>
                                <td class="py-3 text-agro-dark"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="py-3 text-agro-dark">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        <?php echo $user['role'] === 'admin' ? 'bg-red-100 text-red-800' : 
                                               ($user['role'] === 'petani' ? 'bg-blue-100 text-blue-800' : 
                                                'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td class="py-3 text-agro-dark">
                                    <button onclick="editUser(<?php echo $user['user_id']; ?>, '<?php echo addslashes(htmlspecialchars($user['name'])); ?>', '<?php echo addslashes(htmlspecialchars($user['email'])); ?>', '<?php echo $user['role']; ?>', '<?php echo addslashes(htmlspecialchars($user['phone'])); ?>', '<?php echo addslashes(htmlspecialchars($user['address'])); ?>')" 
                                        class="text-agro-green hover:text-[#3a5d50] mr-3">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <form method="POST" action="" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                        <button type="submit" name="delete_user" class="text-red-500 hover:text-red-700">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Edit User Modal -->
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 w-11/12 md:w-1/2">
                    <h2 class="text-xl font-bold text-agro-dark mb-4">Edit Pengguna</h2>
                    <form method="POST" id="editForm">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label class="block text-agro-dark mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="edit_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-agro-dark mb-2">Email</label>
                                <input type="email" name="email" id="edit_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-agro-dark mb-2">Role</label>
                                <select name="role" id="edit_role" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="pembeli">Pembeli</option>
                                    <option value="petani">Petani</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-agro-dark mb-2">No. HP</label>
                                <input type="text" name="phone" id="edit_phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-agro-dark mb-2">Alamat</label>
                                <input type="text" name="address" id="edit_address" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 border border-gray-300 text-agro-dark rounded-lg">
                                Batal
                            </button>
                            <button type="submit" name="update_user" class="bg-agro-green hover:bg-[#3a5d50] text-white py-2 px-4 rounded-lg">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        function editUser(id, name, email, role, phone, address) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_address').value = address;
            
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