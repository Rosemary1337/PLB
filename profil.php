<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - TM Agro</title>
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
    $error = '';
    $success = '';
    
    // Handle profile update
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $address = trim($_POST['address']);
        
        // Update user info
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?");
        $stmt->bind_param('ssssi', $name, $email, $phone, $address, $user_id);
        
        if ($stmt->execute()) {
            $success = 'Profil berhasil diperbarui!';
            
            // Update session with new name
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
        } else {
            $error = 'Terjadi kesalahan saat memperbarui profil.';
        }
        $stmt->close();
    }
    
    // Fetch current user data
    $stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    ?>
    
    <?php include 'includes/header.php'; ?>

    <!-- Page Content -->
    <div class="px-4 md:px-8 lg:px-20 py-8">
        <!-- Page Title -->
        <h1 class="text-agro-light text-xl md:text-3xl font-semibold mb-8">Profil Pengguna</h1>

        <!-- Profile Container -->
        <div class="bg-white/95 rounded-[20px] p-6 md:p-12 mb-8">
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
            
            <form method="POST" action="">
                <!-- Profile Information Fields -->
                <div class="space-y-6 mb-8">
                    <!-- Foto Profil -->
                    <div class="flex flex-col items-center mb-8">
                        <div class="w-32 h-32 rounded-full bg-agro-light flex items-center justify-center mb-4">
                            <svg class="w-16 h-16 text-agro-dark" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </div>
                        <button type="button" class="text-agro-dark text-sm underline">Ganti Foto</button>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Nama Lengkap:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- Email -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- No. Hp -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">No. Hp:</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- Alamat -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                        <label class="text-agro-dark/95 text-base md:text-lg lg:col-span-3">Alamat:</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"
                            class="bg-[#E8E8E8] rounded-[20px] px-6 py-3 text-agro-dark/95 text-sm md:text-base border-none outline-none focus:ring-2 focus:ring-agro-dark/20 lg:col-span-9" />
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                        <button
                            class="bg-white text-agro-dark border-2 border-agro-dark hover:bg-agro-dark hover:text-white transition-colors rounded-[20px] px-8 py-4 text-base md:text-lg font-medium">
                            Update Profil
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>