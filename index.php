<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="globals.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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

<body>
    <?php
    define('INCLUDED', true);
    require_once 'config.php';
    ?>
    <?php include 'includes/header.php'; ?>

    <div class="bg-[#f1f1f1] w-full" style="padding-top: 75px;">
        <section class="container mx-auto px-4 py-16 flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 lg:pr-8">
                <h1 class="text-xl lg:text-6xl font-bold text-[#1c352d] mb-4">TM Agro: Solusi Kebutuhan Pertanian Anda.</h1>
                <p class="text-md lg:text-xl text-[#1c352d]">
                    TM Agro merupakan platform e-commerce khusus produk pertanian, tempat petani, penjual, dan pembeli bisa
                    berinteraksi langsung. Tujuannya adalah mempermudah distribusi hasil tani, alat pertanian, dan kebutuhan
                    agrikultur secara digital mulai dari benih, pupuk, pestisida, alat pertanian, hingga produk hasil panen.
                </p>
            </div>
            <div class="lg:w-1/2 mt-8 lg:mt-0">
                <img class="w-full h-auto rounded-[20px] object-cover"
                    src="https://c.animaapp.com/mhy5vcslibrZVs/img/139664-825x495-1.png" />
            </div>
        </section>
        <section id="profil" class="bg-agro-dark py-16 lg:py-24">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl lg:text-4xl font-bold text-agro-light mb-8">Profil - Tentang Kami</h2>
                <p class="text-md lg:text-lg text-agro-light/80 mb-16">
                    Selamat datang di TM Agro, platform digital yang menghadirkan solusi lengkap untuk dunia pertanian modern
                    Indonesia. Kami percaya bahwa kemajuan pertanian dimulai dari kemudahan akses, baik untuk alat, bibit,
                    pupuk,
                    hingga informasi terkini seputar agribisnis.<br /><br />Didirikan dengan semangat membantu petani dan pelaku
                    usaha tani, TM Agro hadir sebagai jembatan antara produsen dan konsumen. Kami menyediakan berbagai kebutuhan
                    pertanian berkualitas tinggi dengan harga yang transparan dan layanan yang cepat.<br /><br />Melalui website
                    ini, kami ingin menciptakan ekosistem pertanian yang lebih efisien, adil, dan berkelanjutan. Tidak hanya
                    menjual
                    produk, kami juga memberikan edukasi pertanian modern agar petani Indonesia semakin mandiri dan berdaya
                    saing.
                </p>
                <h2 class="text-2xl lg:text-4xl font-bold text-agro-light mb-8">Visi &amp; Misi</h2>
                <p class="text-md lg:text-lg text-agro-light/80">
                    Menjadi platform pertanian digital terdepan di Indonesia yang membantu petani dan pelaku agribisnis tumbuh
                    melalui inovasi teknologi dan semangat kebersamaan.<br /><br />
                    Kami berkomitmen untuk:<br />Menyediakan produk pertanian berkualitas tinggi dengan harga
                    terjangkau.<br />Memperkuat
                    hubungan langsung antara petani, penjual, dan konsumen.<br />Mengedukasi masyarakat tentang pertanian modern
                    yang ramah lingkungan.<br />Mendorong pertanian berkelanjutan yang efisien, produktif, dan mandiri
                </p>
            </div>
        </section>
        <section id="kategori" class="py-16 lg:py-24">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl lg:text-4xl font-bold text-agro-dark mb-12 lg:mb-16 text-center">Kategori Produk</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col gap-4">
                        <h3 class="text-xl lg:text-4xl font-semibold text-agro-dark">Benih</h3>
                        <p class="text-md lg:text-lg text-agro-dark/80">
                            Kategori Benih berisi berbagai jenis bibit unggul untuk tanaman pangan, hortikultura, dan perkebunan.
                            Kita
                            menyediakan benih yang telah melalui proses seleksi dan uji kualitas, memastikan daya tumbuh tinggi dan
                            hasil
                            panen maksimal. Mulai dari padi, jagung, cabai, tomat, hingga sayuran organik
                        </p>
                    </div>
                    <img class="w-full h-auto rounded-[20px] object-cover"
                        src="https://c.animaapp.com/mhy5vcslibrZVs/img/rectangle-5.png" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                    <img class="w-full h-auto rounded-[20px] object-cover"
                        src="https://c.animaapp.com/mhy5vcslibrZVs/img/rectangle-6.png" />
                    <div class="flex flex-col gap-4">
                        <h3 class="text-xl lg:text-4xl font-semibold text-agro-dark text-right">Peralatan</h3>
                        <p class="text-md lg:text-lg text-agro-dark/80 text-right">
                            Bagian Peralatan mencakup segala perlengkapan pertanian modern, mulai dari alat tangan sederhana hingga
                            mesin
                            pertanian efisien. TM Agro menyediakan sprayer, suku cadang sprayer, pompa air, polybag, selang, dan
                            berbagai
                            alat bantu lainnya. Semua produk dipilih untuk memudahkan pekerjaan petani, menghemat waktu, dan
                            meningkatkan
                            hasil kerja di lapangan.
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                    <div class="flex flex-col gap-4">
                        <h3 class="text-xl lg:text-4xl font-semibold text-agro-dark">Pestisida</h3>
                        <p class="text-md lg:text-lg text-agro-dark/80">
                            Kategori Pestisida menampilkan produk pengendali hama dan penyakit tanaman, baik kimia maupun organik.
                            Kami
                            menyediakan pestisida cair, bubuk, insektisida, fungisida, dan herbisida dari merek terpercaya yang aman
                            dan
                            efektif. Setiap produk dilengkapi dengan panduan penggunaan agar petani dapat melindungi tanamannya
                            tanpa
                            merusak lingkungan.
                        </p>
                    </div>
                    <img class="w-full h-auto rounded-[20px] object-cover"
                        src="https://c.animaapp.com/mhy5vcslibrZVs/img/rectangle-7.png" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                    <img class="w-full h-auto rounded-[20px] object-cover"
                        src="https://c.animaapp.com/mhy5vcslibrZVs/img/rectangle-8.png" />
                    <div class="flex flex-col gap-4">
                        <h3 class="text-xl lg:text-4xl font-semibold text-agro-dark text-right">Pupuk</h3>
                        <p class="text-md lg:text-lg text-agro-dark/80 text-right">
                            Di kategori Pupuk,Kami menawarkan berbagai pilihan nutrisi tanaman, dari pupuk organik, anorganik,
                            hingga
                            pupuk cair modern. Setiap produk dirancang untuk mempercepat pertumbuhan tanaman, meningkatkan kesuburan
                            tanah, dan memperbaiki hasil panen. Kami hanya menjual pupuk yang telah teruji dan sesuai standar
                            kebutuhan
                            pertanian Indonesia.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-agro-dark py-16 lg:py-24">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl lg:text-4xl font-bold text-agro-light mb-12 lg:mb-16 text-center">Belanja Sekarang</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-8">
                    <?php
                    // Fetch products from database
                    $sql = "SELECT produk_id, nama, deskripsi, harga, image_url FROM products LIMIT 12";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<a href="detail.php?id=' . $row['produk_id'] . '" class="block">';
                            echo '<div class="rounded-[20px] bg-agro-light overflow-hidden w-full">';
                            echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            if (!empty($row['image_url'])) {
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
                            echo '<h3 class="text-agro-dark text-base font-medium">' . htmlspecialchars($row['nama']) . '</h3>';
                            echo '<p class="text-agro-dark text-sm font-normal opacity-70">' . htmlspecialchars($row['deskripsi']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                    } else {
                        // If no products in database, show default items
                        for($i = 0; $i < 12; $i++) {
                            echo '<a href="detail.php" class="block">';
                            echo '<div class="rounded-[20px] bg-agro-light overflow-hidden w-full">';
                            echo '<div class="h-[265px] bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<div class="w-full h-full bg-agro-green rounded-t-[20px] flex items-center justify-center">';
                            echo '<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<path d="M86.25 13.75C86.25 13.75 80.4167 12.5 71.6667 12.5C48.75 12.5 6.66663 21.25 13.3333 86.6667C17.9166 87.0833 22.4999 87.5 26.6666 87.5C101.25 87.5 86.25 13.75 86.25 13.75ZM29.1666 70.8333C29.1666 70.8333 29.1666 29.1667 70.8333 29.1667C70.8333 29.1667 45.8333 37.5 29.1666 70.8333Z" fill="#1C352D" />';
                            echo '</svg>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="px-[18px] py-[15px]">';
                            echo '<h3 class="text-agro-dark text-base font-medium">Produk</h3>';
                            echo '<p class="text-agro-dark text-sm font-normal opacity-70">-</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</a>';
                        }
                    }
                    ?>
                </div>
                <div class="text-center mt-12">
                    <a href="belanja.php" class="text-agro-light text-base hover:underline">Lihat Semua Produk &rarr;</a>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer id="footer" class="bg-white px-4 md:px-8 lg:px-20 py-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
            <iframe width="300" height="200" style="border:0" loading="lazy" allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA4S2gmkurOTu_gVRPP68gxWxPFTQ2mzP4 &q=Toko+Tani+Maju">
            </iframe>
    
            <div class="text-agro-green/70">
                <h4 class="font-bold text-base md:text-lg mb-4 text-agro-green">Hubungi Kami</h4>
                <p class="text-sm md:text-base leading-relaxed">
                    Alamat: Jl. Raya Pertanian No. 45, Tulungagung, Indonesia<br />
                    Telepon: +62 812-3456-7890<br />
                    Email: support@tmagro.id<br />
                    Layanan: Setiap Hari, 06.00-17.00
                </p>
            </div>
    
            <div class="text-agro-green/70">
                <h4 class="font-bold text-base md:text-lg mb-4 text-agro-green">Informasi</h4>
                <ul class="text-sm md:text-base leading-relaxed space-y-2">
                    <li><a href="#" class="hover:text-agro-green transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-agro-green transition-colors">Blog Edukasi</a></li>
                    <li><a href="#" class="hover:text-agro-green transition-colors">Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-agro-green transition-colors">Syarat & Ketentuan</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>

</html>