<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - TM Agro</title>
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
    <?php
    define('INCLUDED', true);
    require_once 'config.php';
    
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    ?>
    
    <?php include 'includes/header.php'; ?>

    <!-- Page Content -->
    <div class="px-4 md:px-8 lg:px-20 py-8">
        <!-- Page Title -->
        <h1 class="text-agro-light text-xl md:text-3xl font-semibold mb-8">Keranjang</h1>

        <!-- Cart Container -->
        <div class="bg-white/95 rounded-[20px] p-6 md:p-12 mb-8">
            <?php
            // Query to get user's cart items
            $sql = "SELECT ci.cart_item_id, ci.kuantity, p.nama, p.harga, p.image_url, p.deskripsi
                    FROM cart_items ci 
                    JOIN products p ON ci.produk_id = p.produk_id 
                    JOIN carts c ON ci.cart_id = c.cart_id 
                    WHERE c.user_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="flex flex-col sm:flex-row gap-6 pb-8 mb-8 border-b border-agro-dark/10">';
                    echo '<div class="w-full sm:w-[250px] h-[250px] flex-shrink-0 bg-agro-light rounded-[20px] flex items-center justify-center">';
                    if (!empty($row['image_url'])) {
                        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-[20px]">';
                    } else {
                        echo '<div class="w-full h-full bg-agro-green rounded-[20px] flex items-center justify-center">';
                        echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                        echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                        echo '</svg>';
                        echo '</div>';
                    }
                    echo '</div>';

                    echo '<div class="flex-1">';
                    echo '<h3 class="text-agro-dark/95 text-xl md:text-2xl font-semibold mb-4">' . htmlspecialchars($row['nama']) . '</h3>';
                    echo '<p class="text-agro-dark/70 text-sm md:text-base leading-relaxed">' . htmlspecialchars($row['deskripsi']) . '</p>';
                    echo '</div>';

                    echo '<div class="flex sm:flex-col justify-between sm:justify-center items-end sm:items-center">';
                    echo '<span class="text-agro-dark/95 text-xl md:text-2xl font-semibold">x' . $row['kuantity'] . '</span>';
                    echo '<button onclick="removeFromCart(' . $row['cart_item_id'] . ')" class="mt-2 text-red-500 hover:text-red-700">';
                    echo '<i class="fa-solid fa-trash"></i>';
                    echo '</button>';
                    echo '</div>';

                    echo '</div>';
                }
                
                echo '<!-- Checkout Button -->';
                echo '<div class="flex justify-end mt-8">';
                echo '<a href="form-pembelian.php"';
                echo 'class="bg-white text-agro-dark border-2 border-agro-dark hover:bg-agro-dark hover:text-white transition-colors rounded-[20px] px-8 py-4 text-base md:text-lg font-medium">';
                echo 'Pesan Sekarang';
                echo '</a>';
                echo '</div>';
            } else {
                echo '<div class="text-center py-12">';
                echo '<h3 class="text-agro-dark text-xl md:text-2xl font-semibold mb-4">Keranjang Kosong</h3>';
                echo '<p class="text-agro-dark/70 mb-6">Tambahkan beberapa produk ke keranjang Anda</p>';
                echo '<a href="belanja.php" class="inline-block bg-agro-green text-white px-6 py-3 rounded-[20px] hover:bg-agro-green/90">Lihat Produk</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script>
        function removeFromCart(cartItemId) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                fetch('remove_from_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cart_item_id: cartItemId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error);
                });
            }
        }
    </script>
</body>

</html>