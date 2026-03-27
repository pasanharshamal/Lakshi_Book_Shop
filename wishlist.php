<?php require_once 'includes/header.php'; ?>

  <main class="container mx-auto px-4 md:px-20 py-8 space-y-8">
    <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <p class="text-primary font-bold uppercase tracking-widest text-xs mb-2">Lakshi Collection</p>
          <h2 class="text-3xl md:text-4xl font-black tracking-tight">My Wishlist</h2>
          <p class="text-slate-500 mt-2">Books you saved for later.</p>
        </div>

        <div class="flex flex-wrap gap-3">
          <button id="clearWishlistBtn" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all px-5 py-3 rounded-lg font-bold text-sm uppercase">
            Clear Wishlist
          </button>
          <a href="shop.php" class="bg-primary text-white hover:bg-blue-700 transition-all px-5 py-3 rounded-lg font-bold text-sm uppercase">
            Continue Shopping
          </a>
        </div>
      </div>
    </section>

    <section class="flex items-center justify-between">
      <h3 class="text-xl font-black uppercase tracking-tight">Saved Books</h3>
      <p id="wishlistSummary" class="text-sm font-semibold text-slate-500">0 Books</p>
    </section>

    <section>
      <div id="wishlistItems" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"></div>

      <div id="emptyWishlist" class="hidden bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-10 text-center">
        <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">favorite</span>
        <h3 class="text-2xl font-black mb-2">Your wishlist is empty</h3>
        <p class="text-slate-500 mb-6">Save books you like and come back to them later.</p>
        <a href="shop.php" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-bold uppercase text-sm">
          Browse Books
        </a>
      </div>
    </section>
  </main>

  <script>
    // Shared functions like getWishlist, saveWishlist are now in Js/app.js

    function removeFromWishlistPage(id) {
      const wishlist = getWishlist().filter(item => String(item.id) !== String(id));
      saveWishlist(wishlist);
      renderWishlist();
    }

    function moveToCart(id) {
      const wishlist = getWishlist();
      const book = wishlist.find(item => String(item.id) === String(id));

      if (!book) return;

      addToCart(book);
      removeFromWishlistPage(id);
    }

    function clearWishlistPage() {
      localStorage.removeItem("wishlist");
      updateWishlistCount();
      renderWishlist();
    }

    function renderWishlist() {
      const wishlist = getWishlist();
      const container = document.getElementById("wishlistItems");
      const emptyState = document.getElementById("emptyWishlist");
      const summary = document.getElementById("wishlistSummary");

      if (summary) {
        summary.textContent = wishlist.length + " Book" + (wishlist.length !== 1 ? "s" : "");
      }

      if (wishlist.length === 0) {
        if (container) container.innerHTML = "";
        if (emptyState) emptyState.classList.remove("hidden");
        return;
      }

      if (emptyState) emptyState.classList.add("hidden");

      if (container) {
        container.innerHTML = wishlist.map(book => `
          <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-xl transition-all group flex flex-col">
            <div class="relative aspect-[3/4] bg-slate-100 overflow-hidden">
              <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="${escapeHtml(book.image)}" alt="${escapeHtml(book.title)}" />
            </div>
            <div class="p-4 flex-1 flex flex-col">
              <p class="text-[10px] font-bold uppercase text-slate-400 mb-1">${escapeHtml(categoryLabels[book.category] || book.category)}</p>
              <h3 class="font-bold text-sm mb-1 line-clamp-2">${escapeHtml(book.title)}</h3>
              <p class="text-xs text-slate-500 mb-3">${escapeHtml(book.author)}</p>
              <div class="mt-auto space-y-2">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-primary font-black">LKR ${Number(book.price).toLocaleString()}</span>
                </div>

                <button
                  onclick="moveToCart('${book.id}')"
                  class="w-full bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all py-2 rounded-lg text-xs font-bold uppercase flex items-center justify-center gap-2">
                  <span class="material-symbols-outlined text-sm">shopping_cart</span>
                  Move to Cart
                </button>

                <button
                  onclick="removeFromWishlistPage('${book.id}')"
                  class="w-full bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all py-2 rounded-lg text-xs font-bold uppercase flex items-center justify-center gap-2">
                  <span class="material-symbols-outlined text-sm">delete</span>
                  Remove
                </button>
              </div>
            </div>
          </div>
        `).join("");
      }
    }

    if(document.getElementById("clearWishlistBtn")) {
      document.getElementById("clearWishlistBtn").addEventListener("click", function () {
        if (getWishlist().length === 0) {
          alert("Wishlist is already empty.");
          return;
        }

        if (confirm("Are you sure you want to clear the wishlist?")) {
          clearWishlistPage();
        }
      });
    }

    document.addEventListener("DOMContentLoaded", () => {
        renderWishlist();
    });
  </script>

<?php require_once 'includes/footer.php'; ?>