<?php
// Start session for generic tracking
session_start();

// Include database connection and helper functions
require_once '../includes/db.php';
require_once '../includes/functions.php';

$error = '';
$success = '';

// Process the form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form inputs
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate if any fields are empty
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        // Ensure passwords map perfectly
        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Check existing email
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            // If email is already bound to a user, show error
            if ($stmt->get_result()->num_rows > 0) {
                $error = "Email address is already registered.";
            } else {
                // Securely hash the password string before DB insertion
                $hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Prepare INSERT statement to protect against SQL injections
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hash);
                
                // Execute user storage
                if ($stmt->execute()) {
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            }
            $stmt->close();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Account - Lakshi Book Shop</title>
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
<body class="min-h-full bg-background-light flex items-center justify-center p-4 py-12">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 pt-8 pb-6 bg-slate-50 border-b border-slate-100 flex flex-col items-center">
            <a href="../index.php" class="bg-primary p-2 rounded-xl mb-4 shadow-md flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-3xl">auto_stories</span>
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create Account</h1>
            <p class="text-sm text-slate-500 mt-1">Join Lakshi Book Shop today</p>
        </div>
        
        <div class="p-8">
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm font-medium mb-6 flex items-start gap-2 border border-red-100">
                    <span class="material-symbols-outlined text-lg">error</span>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg text-sm font-medium mb-6 flex items-start gap-2 border border-emerald-100">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    <?php echo htmlspecialchars($success); ?>
                </div>
                <a href="login.php" class="w-full py-3.5 bg-primary text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md flex justify-center text-center">
                    Proceed to Login
                </a>
            <?php else: ?>
                <form action="register.php" method="POST" class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-slate-700">Full Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-lg border-slate-200 focus:border-primary focus:ring-primary transition-all bg-slate-50" placeholder="Aruni Perera" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" />
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-slate-700">Email Address</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 rounded-lg border-slate-200 focus:border-primary focus:ring-primary transition-all bg-slate-50" placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-slate-700">Password</label>
                        <input type="password" name="password" required minlength="6" class="w-full px-4 py-3 rounded-lg border-slate-200 focus:border-primary focus:ring-primary transition-all bg-slate-50" placeholder="••••••••" />
                        <p class="text-[10px] text-slate-400">Must be at least 6 characters</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-slate-700">Confirm Password</label>
                        <input type="password" name="confirm_password" required minlength="6" class="w-full px-4 py-3 rounded-lg border-slate-200 focus:border-primary focus:ring-primary transition-all bg-slate-50" placeholder="••••••••" />
                    </div>

                    <p class="text-xs text-slate-500 py-2">
                        By creating an account, you agree to our Terms of Service and Privacy Policy.
                    </p>

                    <button type="submit" class="w-full py-3.5 bg-primary text-white font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-primary/20">
                        Create Account
                    </button>
                </form>
            <?php endif; ?>

            <div class="mt-8 text-center text-sm text-slate-500">
                Already have an account? <a href="login.php" class="text-primary font-bold hover:underline">Sign in</a>
            </div>
            
            <?php if (empty($success)): ?>
            <div class="mt-4 text-center">
                <a href="../index.php" class="text-xs text-slate-400 hover:text-slate-600 flex items-center justify-center gap-1 transition-colors">
                    <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                    Return to Store
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
