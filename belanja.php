<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belanja</title>
    <link rel="stylesheet" href="globals.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        * {
            font-family: 'Plus Jakarta Sans', -apple-system, Roboto, Helvetica, sans-serif;
        }

        :root {
            --color-dark: #1C352D;
            --color-green: #446D60;
            --color-light: #F2F2F2;
            --color-white: #FFFFFF;
        }

        .bg-agro-dark {
            background-color: var(--color-dark);
        }

        .bg-agro-green {
            background-color: var(--color-green);
        }

        .bg-agro-light {
            background-color: var(--color-light);
        }

        .text-agro-dark {
            color: var(--color-dark);
        }

        .text-agro-green {
            color: var(--color-green);
        }

        .text-agro-light {
            color: var(--color-light);
        }
    </style>
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

    <!-- Benih Section -->
    <!-- Benih Section -->
    <section class="py-16 lg:py-20">
        <div class="max-w-[1900px] mx-auto px-4">
            <h2 class="text-agro-dark text-xl lg:text-3xl font-semibold mb-12 lg:mb-16">
                Benih
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8">
                <?php
                // Ambil produk dari database untuk kategori Benih
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 1 OR kategori_id IS NULL ORDER BY dibuat_taggal DESC LIMIT 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8 mt-8">
                <?php
                // Show next 6 products for Benih
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 1 OR kategori_id IS NULL ORDER BY dibuat_taggal DESC LIMIT 6 OFFSET 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
        </div>
    </section>

    <!-- Peralatan Section -->
    <section class="py-16 lg:py-20">
        <div class="max-w-[1900px] mx-auto px-4">
            <h2 class="text-agro-dark text-xl lg:text-3xl font-semibold mb-12 lg:mb-16">
                Peralatan
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8">
                <?php
                // Fetch products from database for Peralatan category
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 2 ORDER BY dibuat_taggal DESC LIMIT 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8 mt-8">
                <?php
                // Show next 6 products for Peralatan
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 2 ORDER BY dibuat_taggal DESC LIMIT 6 OFFSET 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
        </div>
    </section>

    <!-- Pestisida Section -->
    <section class="py-16 lg:py-20">
        <div class="max-w-[1900px] mx-auto px-4">
            <h2 class="text-agro-dark text-xl lg:text-3xl font-semibold mb-12 lg:mb-16">
                Pestisida
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8">
                <?php
                // Fetch products from database for Pestisida category
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 3 ORDER BY dibuat_taggal DESC LIMIT 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8 mt-8">
                <?php
                // Show next 6 products for Pestisida
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 3 ORDER BY dibuat_taggal DESC LIMIT 6 OFFSET 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
        </div>
    </section>

    <!-- Pupuk Section -->
    <section class="py-16 lg:py-20">
        <div class="max-w-[1900px] mx-auto px-4">
            <h2 class="text-agro-dark text-xl lg:text-3xl font-semibold mb-12 lg:mb-16">
                Pupuk
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8">
                <?php
                // Fetch products from database for Pupuk category
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 4 ORDER BY dibuat_taggal DESC LIMIT 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 lg:gap-8 mt-8">
                <?php
                // Show next 6 products for Pupuk
                $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products WHERE kategori_id = 4 ORDER BY dibuat_taggal DESC LIMIT 6 OFFSET 6";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                        echo '<div class="rounded-[20px] bg-agro-dark overflow-hidden w-full">';
                        echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                        if ($row['image_url']) {
                            echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['nama']) . '" class="w-full h-full object-cover rounded-t-[20px]">';
                        } else {
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '<div class="px-[18px] py-[15px]">';
                        echo '<h3 class="text-agro-light text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                        echo '<p class="text-agro-light text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                }
                // Note: If no products in database, no items will be shown (empty state)
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>