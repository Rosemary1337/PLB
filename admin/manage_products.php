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

// Handle product updates
if (isset($_POST['update_product'])) {
    $product_id = (int)$_POST['product_id'];
    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image_url = trim($_POST['image_url']);
    
    // Validate inputs
    if (empty($name) || $category_id <= 0 || $price < 0 || $stock < 0) {
        $error = 'Semua field harus diisi dengan benar';
    } else {
        // Update product
        $stmt = $conn->prepare("UPDATE products SET nama = ?, kategori_id = ?, deskripsi = ?, harga = ?, stock = ?, image_url = ? WHERE produk_id = ?");
        $stmt->bind_param('sisddsi', $name, $category_id, $description, $price, $stock, $image_url, $product_id);
        
        if ($stmt->execute()) {
            $success = 'Produk berhasil diperbarui';
        } else {
            $error = 'Terjadi kesalahan saat memperbarui produk';
        }
        $stmt->close();
    }
}

// Handle product deletion
if (isset($_POST['delete_product'])) {
    $product_id = (int)$_POST['product_id'];
    
    // Delete product (will cascade to related records)
    $stmt = $conn->prepare("DELETE FROM products WHERE produk_id = ?");
    $stmt->bind_param('i', $product_id);
    
    if ($stmt->execute()) {
        $success = 'Produk berhasil dihapus';
    } else {
        $error = 'Terjadi kesalahan saat menghapus produk';
    }
    $stmt->close();
}

// Handle product addition
if (isset($_POST['add_product'])) {
    $name = trim($_POST['new_name']);
    $category_id = (int)$_POST['new_category_id'];
    $description = trim($_POST['new_description']);
    $price = (float)$_POST['new_price'];
    $stock = (int)$_POST['new_stock'];
    $image_url = trim($_POST['new_image_url']);
    
    // Validate inputs
    if (empty($name) || $category_id <= 0 || $price < 0 || $stock < 0) {
        $error = 'Semua field harus diisi dengan benar';
    } else {
        // Insert new product
        $insert_stmt = $conn->prepare("INSERT INTO products (nama, kategori_id, deskripsi, harga, stock, image_url, penjual_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $seller_id = $_SESSION['admin_id']; // Admin is adding the product
        $insert_stmt->bind_param('sisddsi', $name, $category_id, $description, $price, $stock, $image_url, $seller_id);
        
        if ($insert_stmt->execute()) {
            $success = 'Produk baru berhasil ditambahkan';
        } else {
            $error = 'Terjadi kesalahan saat menambahkan produk';
        }
        $insert_stmt->close();
    }
}

// Get all products with category names
$products = [];
$stmt = $conn->prepare("
    SELECT p.produk_id, p.nama, p.deskripsi, p.harga, p.stock, p.image_url, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.kategori_id = c.kategori_id
    ORDER BY p.dibuat_taggal DESC
");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();

// Get all categories for dropdown
$categories = [];
$stmt = $conn->prepare("SELECT kategori_id, name FROM categories");
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Dashboard Admin TM Agro</title>
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
                <h1 class="text-agro-dark text-xl font-semibold">Kelola Produk</h1>
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
                <a href="manage_products.php" class="block py-3 px-4 text-agro-light hover:bg-agro-green rounded-lg mb-2 bg-agro-green">
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
                <h1 class="text-2xl font-bold text-agro-dark mb-2">Kelola Produk</h1>
                <p class="text-agro-dark/70">Atur dan kelola produk di platform TM Agro</p>
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
            
            <!-- Add Product Form -->
            <div class="bg-white rounded-xl shadow p-6 mb-8">
                <h2 class="text-xl font-bold text-agro-dark mb-4">Tambah Produk Baru</h2>
                <form method="POST" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-agro-dark mb-2">Nama Produk</label>
                            <input type="text" name="new_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">Kategori</label>
                            <select name="new_category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['kategori_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">Harga (Rp)</label>
                            <input type="number" name="new_price" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-agro-dark mb-2">Stok</label>
                            <input type="number" name="new_stock" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-agro-dark mb-2">Deskripsi</label>
                            <textarea name="new_description" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-agro-dark mb-2">URL Gambar</label>
                            <input type="url" name="new_image_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                    <button type="submit" name="add_product" class="bg-agro-green hover:bg-[#3a5d50] text-white font-bold py-2 px-4 rounded-lg">
                        Tambah Produk
                    </button>
                </form>
            </div>
            
            <!-- Products Table -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-agro-dark mb-4">Daftar Produk</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-agro-light">
                                <th class="text-left py-3 text-agro-dark">ID</th>
                                <th class="text-left py-3 text-agro-dark">Nama</th>
                                <th class="text-left py-3 text-agro-dark">Kategori</th>
                                <th class="text-left py-3 text-agro-dark">Harga</th>
                                <th class="text-left py-3 text-agro-dark">Stok</th>
                                <th class="text-left py-3 text-agro-dark">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr class="border-b border-agro-light/30">
                                <td class="py-3 text-agro-dark"><?php echo $product['produk_id']; ?></td>
                                <td class="py-3 text-agro-dark"><?php echo htmlspecialchars($product['nama']); ?></td>
                                <td class="py-3 text-agro-dark"><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td class="py-3 text-agro-dark">Rp <?php echo number_format($product['harga'], 0, ',', '.'); ?></td>
                                <td class="py-3 text-agro-dark"><?php echo $product['stock']; ?></td>
                                <td class="py-3 text-agro-dark">
                                    <button onclick="editProduct(<?php echo $product['produk_id']; ?>, '<?php echo addslashes(htmlspecialchars($product['nama'])); ?>', <?php echo $product['kategori_id']; ?>, '<?php echo addslashes(htmlspecialchars($product['deskripsi'])); ?>', <?php echo $product['harga']; ?>, <?php echo $product['stock']; ?>, '<?php echo addslashes(htmlspecialchars($product['image_url'])); ?>')" 
                                        class="text-agro-green hover:text-[#3a5d50] mr-3">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <form method="POST" action="" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        <input type="hidden" name="product_id" value="<?php echo $product['produk_id']; ?>">
                                        <button type="submit" name="delete_product" class="text-red-500 hover:text-red-700">
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
            
            <!-- Edit Product Modal -->
            <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 w-11/12 md:w-1/2">
                    <h2 class="text-xl font-bold text-agro-dark mb-4">Edit Produk</h2>
                    <form method="POST" id="editForm">
                        <input type="hidden" name="product_id" id="edit_product_id">
                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div>
                                <label class="block text-agro-dark mb-2">Nama Produk</label>
                                <input type="text" name="name" id="edit_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-agro-dark mb-2">Kategori</label>
                                <select name="category_id" id="edit_category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['kategori_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-agro-dark mb-2">Harga (Rp)</label>
                                <input type="number" name="price" id="edit_price" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div>
                                <label class="block text-agro-dark mb-2">Stok</label>
                                <input type="number" name="stock" id="edit_stock" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-agro-dark mb-2">Deskripsi</label>
                                <textarea name="description" id="edit_description" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-agro-dark mb-2">URL Gambar</label>
                                <input type="url" name="image_url" id="edit_image_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeModal()" class="mr-2 px-4 py-2 border border-gray-300 text-agro-dark rounded-lg">
                                Batal
                            </button>
                            <button type="submit" name="update_product" class="bg-agro-green hover:bg-[#3a5d50] text-white py-2 px-4 rounded-lg">
                                Perbarui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        function editProduct(id, name, category_id, description, price, stock, image_url) {
            document.getElementById('edit_product_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category_id').value = category_id;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('edit_image_url').value = image_url;
            
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