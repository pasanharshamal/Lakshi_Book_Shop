<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require_once 'includes/header.php';
?>

<main class="flex-1 min-h-[60vh] bg-slate-50 dark:bg-slate-900 py-12">
    <div class="max-w-[1200px] mx-auto px-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-6">Welcome to your Dashboard, <?php echo sanitize_input($_SESSION['user_name']); ?>!</h1>
        
        <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
            <h2 class="text-xl font-semibold mb-4 text-slate-800 dark:text-slate-100">Account Overview</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-6">From your dashboard, you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Orders -->
                <div class="p-6 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-4xl text-primary mb-2">shopping_bag</span>
                    <h3 class="font-bold text-slate-900 dark:text-white">Orders</h3>
                    <p class="text-sm text-slate-500 mt-1">View your order history</p>
                </div>
                <!-- Profile -->
                <div class="p-6 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-4xl text-emerald-500 mb-2">person</span>
                    <h3 class="font-bold text-slate-900 dark:text-white">Profile</h3>
                    <p class="text-sm text-slate-500 mt-1">Manage details</p>
                </div>
                <!-- Wishlist -->
                <div class="p-6 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-100 dark:border-slate-700 shadow-sm flex flex-col items-center text-center">
                    <span class="material-symbols-outlined text-4xl text-rose-500 mb-2">favorite</span>
                    <h3 class="font-bold text-slate-900 dark:text-white">Wishlist</h3>
                    <p class="text-sm text-slate-500 mt-1">Your saved items</p>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700">
                <a href="auth/logout.php" class="inline-flex items-center gap-2 text-rose-500 font-semibold hover:text-rose-600 transition-colors">
                    <span class="material-symbols-outlined">logout</span>
                    Logout Securely
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
