<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - TM Agro</title>
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

<body class="bg-agro-light">
    <?php
    define('INCLUDED', true);
    require_once 'config.php';
    include 'includes/header.php';
    ?>
    <!-- Product Section -->
    <section class="bg-agro-dark pt-32">
        <div class="max-w-[1900px] mx-auto px-4">
            <?php
            // Get product ID from URL
            $product_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
            
            if ($product_id) {
                // Fetch product from database
                $sql = "SELECT p.produk_id, p.nama, p.deskripsi, p.harga, p.stock, p.image_url, k.name as category_name 
                        FROM products p 
                        LEFT JOIN categories k ON p.kategori_id = k.kategori_id 
                        WHERE p.produk_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 py-12 md:py-16">
                        <!-- Product Image -->
                        <div class="w-4/5 mx-auto aspect-square bg-agro-light rounded-[20px] flex items-center justify-center">
                            <?php if ($product['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['nama']); ?>" class="w-full h-full object-cover rounded-[20px]">
                            <?php else: ?>
                                <div class="w-full h-full bg-agro-green rounded-[20px] flex items-center justify-center">
                                    <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M172.5 27.5C172.5 27.5 160.833 25 143.333 25C97.4999 25 13.3332 42.5 26.6666 173.333C35.8332 174.167 44.9999 175 53.3332 175C202.5 175 172.5 27.5 172.5 27.5ZM58.3332 141.667C58.3332 141.667 58.3332 58.3333 141.667 58.3333C141.667 58.3333 91.6666 75 58.3332 141.667Z" fill="#1C352D" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <div class="flex flex-col justify-between">
                            <div>
                                <h2 class="text-agro-light text-xl lg:text-3xl font-semibold mb-6"><?php echo htmlspecialchars($product['nama']); ?></h2>
                                <p class="text-agro-light/70 text-sm lg:text-base leading-relaxed mb-8">
                                    <?php echo htmlspecialchars($product['deskripsi']); ?>
                                </p>
                                <p class="text-agro-light text-xl lg:text-3xl font-semibold">Rp<?php echo number_format($product['harga'], 0, ',', '.'); ?></p>
                                <p class="text-agro-light text-base lg:text-lg">Stok: <?php echo (int)$product['stock']; ?></p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button onclick="addToCart(<?php echo $product['produk_id']; ?>)"
                                        class="bg-agro-green hover:bg-[#3a5d50] text-white font-bold py-3 px-4 rounded-lg transition-colors w-full sm:w-auto">
                                        Beli Sekarang
                                    </button>
                                    <button onclick="addToCart(<?php echo $product['produk_id']; ?>)"
                                        class="bg-agro-dark border border-agro-light text-agro-light hover:bg-agro-dark/90 font-bold py-3 px-4 rounded-lg transition-colors w-full sm:w-auto">
                                        Masukkan Keranjang
                                    </button>
                                <?php else: ?>
                                    <a href="login.php"
                                        class="bg-agro-green hover:bg-[#3a5d50] text-white font-bold py-3 px-4 rounded-lg transition-colors w-full sm:w-auto">
                                        Beli Sekarang
                                    </a>
                                    <a href="login.php"
                                        class="bg-agro-dark border border-agro-light text-agro-light hover:bg-agro-dark/90 font-bold py-3 px-4 rounded-lg transition-colors w-full sm:w-auto">
                                        Masukkan Keranjang
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo '<div class="text-agro-light text-center py-16"><h2 class="text-2xl">Produk tidak ditemukan</h2></div>';
                }
            } else {
                echo '<div class="text-agro-light text-center py-16"><h2 class="text-2xl">Pilih produk untuk melihat detail</h2></div>';
            }
            ?>
        </div>
    </section>

    <!-- Description Section -->
    <?php if (isset($product)): ?>
    <section class="bg-agro-dark">
        <div class="max-w-[1900px] mx-auto px-4">
            <h2 class="text-agro-light text-xl lg:text-3xl font-semibold mb-8">Deskripsi</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 py-12 md:py-16">
                <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="w-full aspect-square bg-agro-light rounded-[20px] flex items-center justify-center">
                    <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />
                    </svg>
                </div>
                <?php endfor; ?>
            </div>

            <div class="space-y-6 text-agro-light/70 text-sm lg:text-base leading-relaxed py-12 md:py-16">
                <p>
                    <?php echo htmlspecialchars($product['deskripsi'] ?? 'Deskripsi produk belum tersedia.'); ?>
                </p>
            </div>

            <div class="mt-6 text-agro-light/70 text-sm lg:text-base py-12 md:py-16">
                <p>Nama Produk: <?php echo htmlspecialchars($product['nama'] ?? ''); ?></p>
                <p>Kategori: <?php echo htmlspecialchars($product['category_name'] ?? 'Umum'); ?></p>
                <p>Berat: 1 KG</p>
                <p>Stok: <?php echo (int)$product['stock']; ?></p>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script>
        function addToCart(productId) {
            // Simple JavaScript to add to cart
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Produk berhasil ditambahkan ke keranjang!');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error);
            });
        }
    </script>
</body>

</html>