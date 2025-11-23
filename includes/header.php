<?php
if (!defined('INCLUDED')) {
    exit('Direct access not permitted');
}
?>

<header class="bg-white h-[75px] fixed top-0 left-0 right-0 z-50 shadow-sm">
    <div class="max-w-[1900px] mx-auto px-4 h-full flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="index.php" class="w-[140px] h-[40px] flex items-center justify-center">
                <img src="images/logo.png" alt="TM Agro Logo" class="w-full h-full object-contain">
            </a>

            <nav class="hidden lg:flex items-center gap-8">
                <a href="index.php"
                    class="text-agro-dark text-base font-normal hover:font-semibold transition-all">Beranda</a>
                <a href="index.php#profil"
                    class="text-agro-dark text-base font-normal hover:font-semibold transition-all">Profil</a>
                <a href="index.php#kategori"
                    class="text-agro-dark text-base font-normal hover:font-semibold transition-all">Kategori</a>
                <a href="belanja.php"
                    class="text-agro-dark text-base font-normal hover:font-semibold transition-all">Belanja</a>
                <a href="index.php#footer" class="text-agro-dark text-base font-normal hover:font-semibold transition-all">Call
                    Center</a>
            </nav>
        </div>

        <div class="flex items-center gap-4">
            <div class="relative w-[584px] hidden xl:block">
                <div class="bg-[#F5F5F5] rounded-[30px] h-[50px] px-4 flex items-center gap-3">
                    <svg class="w-6 h-6 text-agro-dark opacity-50" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" placeholder="Cari Kebutuhan Anda"
                        class="bg-transparent flex-1 text-base text-agro-dark placeholder:text-agro-dark placeholder:opacity-50 outline-none" />
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="keranjang.php"
                    class="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors text-agro-dark text-base">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profil.php"
                        class="w-[50px] h-[50px] flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors text-agro-dark text-base">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <a href="logout.php" 
                        class="w-[50px] h-[50px] flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors text-agro-dark text-base">
                        <i class="fa-solid fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="login.php"
                        class="w-[50px] h-[50px] flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors text-agro-dark text-base">
                        <i class="fa-solid fa-user"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>