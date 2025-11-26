<?php
define('INCLUDED', true);
require_once 'config.php';

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Track registration attempts for spam protection
if (!isset($_SESSION['reg_attempts'])) {
    $_SESSION['reg_attempts'] = 0;
    $_SESSION['last_reg_attempt'] = 0;
}

// Check if too many registration attempts
$time_threshold = 300; // 5 minutes
if (($_SESSION['reg_attempts'] >= 3) && (time() - $_SESSION['last_reg_attempt'] < $time_threshold)) {
    $error = 'Terlalu banyak percobaan registrasi. Silakan coba lagi nanti.';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $address = trim($_POST['address']) ?: "Alamat belum diisi";
    
    // Rate limiting for registration attempts
    if ((time() - $_SESSION['last_reg_attempt']) > $time_threshold) {
        $_SESSION['reg_attempts'] = 0; // Reset after threshold
    }
    
    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi';
        $_SESSION['reg_attempts']++;
        $_SESSION['last_reg_attempt'] = time();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
        $_SESSION['reg_attempts']++;
        $_SESSION['last_reg_attempt'] = time();
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok';
        $_SESSION['reg_attempts']++;
        $_SESSION['last_reg_attempt'] = time();
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter';
        $_SESSION['reg_attempts']++;
        $_SESSION['last_reg_attempt'] = time();
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = 'Password harus mengandung huruf besar, huruf kecil, dan angka';
        $_SESSION['reg_attempts']++;
        $_SESSION['last_reg_attempt'] = time();
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Email sudah terdaftar';
            $_SESSION['reg_attempts']++;
            $_SESSION['last_reg_attempt'] = time();
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insert_stmt = $conn->prepare("INSERT INTO users (name, email, password, address) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param('ssss', $name, $email, $hashed_password, $address);
            
            if ($insert_stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
                
                // Reset count on success
                $_SESSION['reg_attempts'] = 0;
                
                // Clear form data
                $name = '';
                $email = '';
            } else {
                $error = 'Terjadi kesalahan saat registrasi. Silakan coba lagi.';
                $_SESSION['reg_attempts']++;
                $_SESSION['last_reg_attempt'] = time();
            }
            
            $insert_stmt->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Toko Tani Maju</title>
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

<body class="bg-agro-dark min-h-screen flex flex-col">
    <!-- Header with Navigation -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center pt-[120px] pb-16">
        <div class="max-w-[1900px] mx-auto px-4 w-full">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 p-8 flex items-center justify-center bg-white">
                        <img src="images/petani.png" alt="Petani" class="max-w-full h-auto">
                    </div>
                    <div class="md:w-1/2 p-8">
                        <h2 class="text-3xl font-bold text-agro-dark text-center mb-8">Daftar Akun</h2>
                        
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
                            <div class="mb-6">
                                <label for="fullname" class="block text-agro-dark mb-2">Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    id="fullname" 
                                    name="fullname"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green focus:border-transparent" 
                                    placeholder="masukkan nama lengkap Anda"
                                    value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                                >
                            </div>
                            
                            <div class="mb-6">
                                <label for="email" class="block text-agro-dark mb-2">Email</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green focus:border-transparent" 
                                    placeholder="masukkan email Anda"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                >
                            </div>
                            
                            <div class="mb-6">
                                <label for="password" class="block text-agro-dark mb-2">Kata Sandi</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green focus:border-transparent" 
                                    placeholder="masukkan kata sandi Anda"
                                >
                            </div>
                            
                            <div class="mb-6">
                                <label for="confirm-password" class="block text-agro-dark mb-2">Konfirmasi Kata Sandi</label>
                                <input 
                                    type="password" 
                                    id="confirm-password" 
                                    name="confirm-password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green focus:border-transparent" 
                                    placeholder="konfirmasi kata sandi Anda"
                                >
                            </div>
                            
                            <div class="mb-6">
                                <label for="address" class="block text-agro-dark mb-2">Alamat</label>
                                <input 
                                    type="text" 
                                    id="address" 
                                    name="address"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-agro-green focus:border-transparent" 
                                    placeholder="masukkan alamat Anda"
                                    value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>"
                                >
                            </div>
                            
                            <button 
                                type="submit" 
                                class="w-full bg-agro-green hover:bg-[#3a5d50] text-white font-bold py-3 px-4 rounded-lg transition-colors"
                            >
                                Daftar
                            </button>
                        </form>
                        
                        <div class="mt-6 text-center">
                            <p class="text-agro-dark">
                                Sudah punya akun? 
                                <a href="login.php" class="text-agro-green font-semibold hover:underline">Masuk di sini</a>
                            </p>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="index.php" class="text-agro-green font-semibold hover:underline">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>

</html>
