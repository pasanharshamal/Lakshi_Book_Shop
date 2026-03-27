<?php
// Maintain user session context
session_start();

// Include database connection and helper functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize email string input directly
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Verify fields contain data
    if (!empty($email) && !empty($password)) {
        // Query the database securely for the provided email mapping
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch the associated user row
        if ($user = $result->fetch_assoc()) {
            // Validate the hashed password using password_verify
            if (password_verify($password, $user['password'])) {
                // Initialize user session variables representing active login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                // Redirect user gracefully to store root dashboard/index
                header("Location: ../index.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Lakshi Book Shop</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#1152d4", "background-light": "#f6f6f8" },
                    fontFamily: { display: ["Inter", "sans-serif"] }
                }
            }
        };
    </script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full bg-background-light flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 pt-8 pb-6 bg-slate-50 border-b border-slate-100 flex flex-col items-center">
            <a href="../index.php" class="bg-primary p-2 rounded-xl mb-4 shadow-md flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-3xl">auto_stories</span>
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Welcome Back</h1>
            <p class="text-sm text-slate-500 mt-1">Sign in to your Lakshi account</p>
        </div>
        
        <div class="p-8">
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm font-medium mb-6 flex items-start gap-2 border border-red-100">
                    <span class="material-symbols-outlined text-lg">error</span>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-5">
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700">Email Address</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border-slate-200 focus:border-primary focus:ring-primary transition-all bg-slate-50" placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
                </div>
                
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700 block flex justify-between">
                        Password
                        <a href="#" class="text-primary hover:underline font-medium text-xs">Forgot?</a>
                    </label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg border-slate-200 focus:border-primary focus:ring-primary transition-all bg-slate-50" placeholder="••••••••" />
                </div>

                <button type="submit" class="w-full py-3.5 bg-primary text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-primary/20 mt-2">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-slate-500">
                Don't have an account? <a href="register.php" class="text-primary font-bold hover:underline">Create one</a>
            </div>
            <div class="mt-4 text-center">
                <a href="../index.php" class="text-xs text-slate-400 hover:text-slate-600 flex items-center justify-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                    Return to Store
                </a>
            </div>
        </div>
    </div>
</body>
</html>
