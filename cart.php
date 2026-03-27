<?php require_once 'includes/header.php'; ?>

  <main class="container mx-auto px-4 md:px-20 py-8 space-y-8">
    <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <p class="text-primary font-bold uppercase tracking-widest text-xs mb-2">Lakshi Collection</p>
          <h2 class="text-3xl md:text-4xl font-black tracking-tight">Shopping Cart</h2>
          <p class="text-slate-500 mt-2">Review your selected books before checkout.</p>
        </div>

        <div class="flex flex-wrap gap-3">
          <button id="clearCartBtn" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all px-5 py-3 rounded-lg font-bold text-sm uppercase">
            Clear Cart
          </button>
          <a href="shop.php" class="bg-primary text-white hover:bg-blue-700 transition-all px-5 py-3 rounded-lg font-bold text-sm uppercase">
            Continue Shopping
          </a>
        </div>
      </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-8">
      <div class="xl:col-span-2 space-y-6">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-black uppercase tracking-tight">Cart Items</h3>
          <p id="cartSummary" class="text-sm font-semibold text-slate-500">0 Items</p>
        </div>

        <div id="cartItems" class="space-y-4"></div>

        <div id="emptyCart" class="hidden bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-10 text-center">
          <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">shopping_cart</span>
          <h3 class="text-2xl font-black mb-2">Your cart is empty</h3>
          <p class="text-slate-500 mb-6">Add some books to your cart to continue.</p>
          <a href="shop.php" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-bold uppercase text-sm">
            Browse Books
          </a>
        </div>
      </div>

      <div class="xl:col-span-1">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 sticky top-28">
          <h3 class="text-xl font-black uppercase tracking-tight mb-6">Order Summary</h3>

          <div class="space-y-4 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-slate-500">Items</span>
              <span id="summaryItems" class="font-bold">0</span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-slate-500">Subtotal</span>
              <span id="subtotal" class="font-bold">LKR 0</span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-slate-500">Delivery</span>
              <span id="delivery" class="font-bold">LKR 500</span>
            </div>

            <div class="flex items-center justify-between">
              <span class="text-slate-500">Discount</span>
              <span id="discount" class="font-bold text-green-600">LKR 0</span>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 pt-4 flex items-center justify-between text-lg">
              <span class="font-black">Total</span>
              <span id="grandTotal" class="font-black text-primary">LKR 0</span>
            </div>
          </div>

          <button id="checkoutBtn" class="w-full mt-6 bg-primary text-white hover:bg-blue-700 transition-all py-3 rounded-lg text-sm font-bold uppercase">
            Proceed to Checkout
          </button>

          <p class="text-xs text-slate-500 mt-4 text-center">
            Secure checkout and island-wide delivery available.
          </p>
        </div>
      </div>
    </section>
  </main>

  <script>
    // Shared functions like getCart, saveCart are now in Js/app.js

    function removeFromCart(id) {
      const cart = getCart().filter(item => String(item.id) !== String(id));
      saveCart(cart);
      renderCart();
    }

    function updateQuantity(id, change) {
      const cart = getCart();
      const item = cart.find(book => String(book.id) === String(id));

      if (!item) return;

      item.quantity = (item.quantity || 1) + change;

      if (item.quantity <= 0) {
        removeFromCart(id);
        return;
      }

      saveCart(cart);
      renderCart();
    }

    function clearCartPage() {
      localStorage.removeItem("cart");
      updateCartCount();
      renderCart();
    }

    function calculateTotals(cart) {
      const subtotal = cart.reduce((sum, item) => {
        return sum + (Number(item.price) * (item.quantity || 1));
      }, 0);

      const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
      const delivery = subtotal === 0 ? 0 : 500;
      const discount = subtotal >= 10000 ? 500 : 0;
      const grandTotal = subtotal + delivery - discount;

      return { subtotal, totalItems, delivery, discount, grandTotal };
    }

    function renderCart() {
      const cart = getCart();
      const container = document.getElementById("cartItems");
      const emptyState = document.getElementById("emptyCart");
      const summary = document.getElementById("cartSummary");

      const { subtotal, totalItems, delivery, discount, grandTotal } = calculateTotals(cart);

      if(document.getElementById("summaryItems")) document.getElementById("summaryItems").textContent = totalItems;
      if(document.getElementById("subtotal")) document.getElementById("subtotal").textContent = "LKR " + subtotal.toLocaleString();
      if(document.getElementById("delivery")) document.getElementById("delivery").textContent = "LKR " + delivery.toLocaleString();
      if(document.getElementById("discount")) document.getElementById("discount").textContent = "LKR " + discount.toLocaleString();
      if(document.getElementById("grandTotal")) document.getElementById("grandTotal").textContent = "LKR " + grandTotal.toLocaleString();
      if(summary) summary.textContent = totalItems + " Item" + (totalItems !== 1 ? "s" : "");

      if (cart.length === 0) {
        if(container) container.innerHTML = "";
        if(emptyState) emptyState.classList.remove("hidden");
        return;
      }

      if(emptyState) emptyState.classList.add("hidden");

      if(container) {
        container.innerHTML = cart.map(item => `
          <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 md:p-5">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="w-full sm:w-28 h-40 sm:h-32 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
                <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.title)}" class="w-full h-full object-cover">
              </div>

              <div class="flex-1 flex flex-col justify-between gap-4">
                <div>
                  <p class="text-[10px] font-bold uppercase text-slate-400 mb-1">${escapeHtml(categoryLabels[item.category] || item.category)}</p>
                  <h3 class="font-bold text-lg leading-tight">${escapeHtml(item.title)}</h3>
                  <p class="text-sm text-slate-500 mt-1">${escapeHtml(item.author)}</p>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                  <div class="flex items-center gap-2">
                    <button onclick="updateQuantity('${item.id}', -1)" class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center">
                      <span class="material-symbols-outlined text-sm">remove</span>
                    </button>

                    <span class="w-10 text-center font-bold">${item.quantity || 1}</span>

                    <button onclick="updateQuantity('${item.id}', 1)" class="w-9 h-9 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 flex items-center justify-center">
                      <span class="material-symbols-outlined text-sm">add</span>
                    </button>
                  </div>

                  <div class="flex items-center justify-between md:justify-end gap-4">
                    <div class="text-right">
                      <p class="text-xs text-slate-500">Unit Price</p>
                      <p class="font-bold">LKR ${Number(item.price).toLocaleString()}</p>
                    </div>

                    <div class="text-right">
                      <p class="text-xs text-slate-500">Total</p>
                      <p class="font-black text-primary">LKR ${(Number(item.price) * (item.quantity || 1)).toLocaleString()}</p>
                    </div>

                    <button onclick="removeFromCart('${item.id}')" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all px-3 py-2 rounded-lg text-xs font-bold uppercase flex items-center gap-1">
                      <span class="material-symbols-outlined text-sm">delete</span>
                      Remove
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        `).join("");
      }
    }

    if(document.getElementById("clearCartBtn")) {
      document.getElementById("clearCartBtn").addEventListener("click", function () {
        if (getCart().length === 0) {
          alert("Cart is already empty.");
          return;
        }
        if (confirm("Are you sure you want to clear the cart?")) {
          clearCartPage();
        }
      });
    }

    if(document.getElementById("checkoutBtn")) {
      document.getElementById("checkoutBtn").addEventListener("click", function () {
        const cart = getCart();
        if (cart.length === 0) {
          alert("Your cart is empty.");
          return;
        }
        alert("Checkout page not added yet. Next step is creating checkout.php");
      });
    }

    document.addEventListener("DOMContentLoaded", () => {
        renderCart();
    });
  </script>

<?php require_once 'includes/footer.php'; ?>