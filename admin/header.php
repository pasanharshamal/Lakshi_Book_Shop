<?php
session_start();
// Basic authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lakshi Book Shop - Admin Dashboard</title>
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
<body class="bg-background-light text-slate-800 flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col h-full shadow-xl z-20">
        <div class="h-16 flex items-center px-6 border-b border-slate-800 bg-slate-950">
            <a href="index.php" class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">auto_stories</span>
                <span class="font-black text-lg tracking-tight uppercase">Admin Panel</span>
            </a>
        </div>
        
        <nav class="flex-1 py-6 px-4 space-y-2">
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-primary text-white' : 'text-slate-300'; ?>">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-medium text-sm">Dashboard</span>
            </a>
            
            <a href="products.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'bg-primary text-white' : 'text-slate-300'; ?>">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-medium text-sm">Products</span>
            </a>

            <a href="messages.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors <?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'bg-primary text-white' : 'text-slate-300'; ?>">
                <span class="material-symbols-outlined">forum</span>
                <span class="font-medium text-sm">Messages</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-950">
            <div class="flex items-center gap-3 px-2 mb-4">
                <div class="bg-primary/20 text-primary p-2 rounded-lg">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Logged in as</p>
                    <p class="text-sm font-bold truncate max-w-[140px]"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></p>
                </div>
            </div>
            <a href="../index.php" class="flex justify-center items-center gap-2 w-full py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-xs font-bold uppercase transition-colors">
                <span class="material-symbols-outlined text-[16px]">storefront</span>
                Back to Site
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-slate-50">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-8 shadow-sm justify-between z-10">
            <h1 class="text-xl font-bold tracking-tight text-slate-800 border-l-4 border-primary pl-3">
                Overview
            </h1>
            <a href="../auth/logout.php" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase flex items-center gap-1 bg-red-50 py-2 px-4 rounded-lg">
                <span class="material-symbols-outlined text-sm">logout</span> Logout
            </a>
        </header>
        
        <div class="flex-1 overflow-y-auto p-8">
