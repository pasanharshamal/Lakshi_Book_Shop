<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Lakshi Book Shop</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
  <script src="Js/app.js" defer></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#1152d4",
            "background-light": "#f6f6f8",
            "background-dark": "#101622",
          },
          fontFamily: {
            display: ["Inter", "sans-serif"]
          }
        }
      }
    };
  </script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 flex flex-col min-h-screen">

  <div class="bg-primary text-white text-xs py-2 px-4 md:px-20 flex justify-between items-center">
    <div class="flex gap-4 items-center">
      <span class="flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">phone_in_talk</span>
        Hotline: +94 11 2 678 678
      </span>
      <span class="hidden md:flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">local_shipping</span>
        Island-wide Delivery
      </span>
    </div>
    <div class="flex gap-4 items-center">
      <a class="hover:underline" href="contact.php">Contact</a>
      <span class="opacity-50">|</span>
      <span>English</span>
    </div>
  </div>

  <header class="sticky top-0 z-50 bg-white dark:bg-slate-900 shadow-sm border-b border-slate-200 dark:border-slate-800">
    <div class="container mx-auto px-4 md:px-20 py-4">
      <div class="flex flex-col lg:flex-row items-center gap-6">
        <a href="index.php" class="flex items-center gap-3 min-w-max">
          <div class="bg-primary p-1.5 rounded-lg">
            <span class="material-symbols-outlined text-white text-3xl">auto_stories</span>
          </div>
          <div>
            <h1 class="text-2xl font-black tracking-tighter text-primary uppercase">Lakshi</h1>
            <p class="text-[10px] tracking-[0.2em] font-bold text-slate-500 -mt-1 uppercase">Book Shop</p>
          </div>
        </a>

        <div class="flex-1 w-full max-w-3xl">
          <div class="relative flex items-center">
            <input id="searchInput" class="w-full pl-4 pr-12 py-3 bg-slate-100 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary transition-all placeholder:text-slate-400" placeholder="Search by title, author or category..." type="text" />
            <button id="searchBtn" class="absolute right-2 bg-primary text-white p-2 rounded-md hover:bg-blue-700 transition-colors" type="button">
              <span class="material-symbols-outlined">search</span>
            </button>
          </div>
        </div>

        <div class="flex items-center gap-6">
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="flex flex-col items-center gap-0.5 text-slate-600 dark:text-slate-300">
              <span class="material-symbols-outlined">person</span>
              <span class="text-[10px] font-bold uppercase"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            </div>
            <a class="flex flex-col items-center gap-0.5 text-slate-600 dark:text-slate-300 hover:text-red-500 transition-colors" href="auth/logout.php">
              <span class="material-symbols-outlined">logout</span>
              <span class="text-[10px] font-bold uppercase">Logout</span>
            </a>
          <?php else: ?>
            <a class="flex flex-col items-center gap-0.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors" href="auth/login.php">
              <span class="material-symbols-outlined">login</span>
              <span class="text-[10px] font-bold uppercase">Login</span>
            </a>
          <?php endif; ?>

          <a class="flex flex-col items-center gap-0.5 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors relative" href="wishlist.php">
            <span class="material-symbols-outlined">favorite</span>
            <span class="text-[10px] font-bold uppercase">Wishlist</span>
            <span id="wishlistCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] min-w-[16px] h-4 px-1 rounded-full flex items-center justify-center font-bold">0</span>
          </a>

          <a class="flex flex-col items-center gap-0.5 text-primary transition-colors relative" href="cart.php">
            <span class="material-symbols-outlined">shopping_cart</span>
            <span class="text-[10px] font-bold uppercase">Cart</span>
            <span id="cartCount" class="absolute -top-1 -right-1 bg-primary text-white text-[10px] min-w-[16px] h-4 px-1 rounded-full flex items-center justify-center font-bold">0</span>
          </a>
        </div>
      </div>
    </div>

    <nav class="bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 hidden md:block">
      <div class="container mx-auto px-20">
        <ul class="flex items-center gap-8 py-3 text-sm font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200">
          <li><a class="hover:text-primary transition-colors" href="index.php">Home</a></li>
          <li><a class="hover:text-primary transition-colors" href="shop.php">Shop</a></li>
          <li><a class="hover:text-primary transition-colors" href="shop.php?category=fiction">Fiction</a></li>
          <li><a class="hover:text-primary transition-colors" href="shop.php?category=children-books">Children's Books</a></li>
          <li><a class="hover:text-primary transition-colors" href="shop.php?category=novels">Novels</a></li>
          <li><a class="hover:text-primary transition-colors" href="shop.php?category=educational">Educational</a></li>
          <li><a class="hover:text-primary transition-colors" href="contact.php">Contact</a></li>
          <li class="ml-auto text-red-600 flex items-center gap-1">
            <span class="material-symbols-outlined text-lg">sell</span> Offers
          </li>
        </ul>
      </div>
    </nav>
  </header>
