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

// Track login attempts for brute force protection
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}

// Check if too many attempts
$time_threshold = 900; // 15 minutes
if ($_SESSION['login_attempts'] >= 5 && (time() - $_SESSION['last_attempt_time']) < $time_threshold) {
    $error = 'Terlalu banyak percobaan login. Silakan coba lagi dalam beberapa menit.';
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? (bool)$_POST['remember'] : false;
    
    // Validate input
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT user_id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Reset login attempts on successful login
                $_SESSION['login_attempts'] = 0;
                
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                // Remember Me functionality
                if ($remember) {
                    $token = bin2hex(random_bytes(50)); // Generate a random token
                    
                    // Store token in database (you might need to add a 'remember_token' column to your users table)
                    // For now, just set a long-lived cookie with token and user ID
                    $cookie_value = $user['user_id'] . ':' . $token;
                    $cookie_expiry = time() + (30 * 24 * 60 * 60); // 30 days
                    
                    // In a real application, you would store this token in a database table
                    setcookie('remember_me', $cookie_value, $cookie_expiry, '/', '', true, true);
                }
                
                // Redirect to home page
                header('Location: index.php');
                exit;
            } else {
                $error = 'Email atau password salah';
                $_SESSION['login_attempts']++;
                $_SESSION['last_attempt_time'] = time();
            }
        } else {
            $error = 'Email atau password salah';
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
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
    <title>Login - Toko Tani Maju</title>
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
    <main class="flex-grow flex items-center justify-center pt-[75px] pb-16">
        <div class="max-w-[1900px] mx-auto px-4 w-full">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 p-8 flex items-center justify-center bg-white">
                        <img src="images/petani.png" alt="Petani" class="max-w-full h-auto">
                    </div>
                    <div class="md:w-1/2 p-8">
                        <h2 class="text-3xl font-bold text-agro-dark text-center mb-8">Login</h2>
                        
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
                            
                            <div class="mb-6 flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="remember" 
                                    name="remember"
                                    class="mr-2 h-4 w-4 text-agro-green focus:ring-agro-green border-gray-300 rounded"
                                >
                                <label for="remember" class="text-agro-dark">Ingat saya</label>
                            </div>
                            
                            <button 
                                type="submit" 
                                class="w-full bg-agro-green hover:bg-[#3a5d50] text-white font-bold py-3 px-4 rounded-lg transition-colors"
                            >
                                Masuk
                            </button>
                        </form>
                        
                        <div class="mt-6 text-center">
                            <p class="text-agro-dark">
                                Belum punya akun? 
                                <a href="register.php" class="text-agro-green font-semibold hover:underline">Daftar di sini</a>
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