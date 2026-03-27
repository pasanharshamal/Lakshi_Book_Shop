document.addEventListener("DOMContentLoaded", () => {
  const CART_KEY = "lakshi_cart";
  const PROMO_KEY = "lakshi_promo_discount";

  function getCart() {
    return JSON.parse(localStorage.getItem(CART_KEY)) || [];
  }

  function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
  }

  function getPromoDiscount() {
    return Number(localStorage.getItem(PROMO_KEY)) || 0;
  }

  function savePromoDiscount(value) {
    localStorage.setItem(PROMO_KEY, String(value));
  }

  function formatLKR(value) {
    return `LKR ${Number(value).toLocaleString()}`;
  }

  function updateCartBadge() {
    const cart = getCart();
    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.querySelectorAll("#cartCount").forEach((badge) => {
      badge.textContent = totalQty;
    });
  }

  function addToCartFromButton(button) {
    const item = {
      id: button.dataset.id,
      title: button.dataset.title,
      author: button.dataset.author || "",
      category: button.dataset.category || "",
      price: Number(button.dataset.price),
      image: button.dataset.image || "",
      quantity: 1
    };

    const cart = getCart();
    const existing = cart.find((product) => product.id === item.id);

    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push(item);
    }

    saveCart(cart);
    updateCartBadge();
    alert(`${item.title} added to cart`);
  }

  document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", () => addToCartFromButton(button));
  });

  function setupHomeSearch() {
    const input = document.getElementById("searchInput");
    const button = document.getElementById("searchBtn");

    if (!input || !button) return;

    const runSearch = () => {
      const value = input.value.trim();
      if (value) {
        window.location.href = `shop.html?search=${encodeURIComponent(value)}`;
      } else {
        window.location.href = "shop.html";
      }
    };

    button.addEventListener("click", runSearch);
    input.addEventListener("keypress", (e) => {
      if (e.key === "Enter") runSearch();
    });
  }

  function setupShopSearchAndSort() {
    const searchInput = document.getElementById("shopSearchInput");
    const sortSelect = document.getElementById("sortSelect");
    const productGrid = document.getElementById("productGrid");
    const resultCount = document.getElementById("resultCount");

    if (!productGrid) return;

    const cards = Array.from(productGrid.querySelectorAll(".book-card"));
    const params = new URLSearchParams(window.location.search);
    const initialSearch = params.get("search") || "";
    const category = params.get("category") || "";

    if (searchInput) {
      searchInput.value = initialSearch;
    }

    function filterCards() {
      const query = (searchInput?.value || "").toLowerCase().trim();

      let visibleCount = 0;

      cards.forEach((card) => {
        const title = (card.dataset.title || "").toLowerCase();
        const author = (card.dataset.author || "").toLowerCase();
        const cardCategory = (card.dataset.category || "").toLowerCase();

        const matchesSearch =
          !query ||
          title.includes(query) ||
          author.includes(query) ||
          cardCategory.includes(query);

        const matchesCategory =
          !category ||
          cardCategory.includes(category.toLowerCase());

        const show = matchesSearch && matchesCategory;
        card.style.display = show ? "" : "none";

        if (show) visibleCount += 1;
      });

      if (resultCount) {
        resultCount.textContent = `Showing ${visibleCount} result(s)`;
      }
    }

    function sortCards() {
      if (!sortSelect) return;

      const cardsToSort = Array.from(productGrid.querySelectorAll(".book-card"));

      cardsToSort.sort((a, b) => {
        const aPrice = Number(a.dataset.price || 0);
        const bPrice = Number(b.dataset.price || 0);
        const aTitle = a.dataset.title || "";
        const bTitle = b.dataset.title || "";

        switch (sortSelect.value) {
          case "low-high":
            return aPrice - bPrice;
          case "high-low":
            return bPrice - aPrice;
          case "title":
            return aTitle.localeCompare(bTitle);
          default:
            return 0;
        }
      });

      cardsToSort.forEach((card) => productGrid.appendChild(card));
      filterCards();
    }

    if (searchInput) {
      searchInput.addEventListener("input", filterCards);
    }

    if (sortSelect) {
      sortSelect.addEventListener("change", sortCards);
    }

    filterCards();
  }

  function renderCartPage() {
    const container = document.getElementById("cart-items-container");
    if (!container) return;

    const subtotalEl = document.getElementById("subtotal");
    const taxEl = document.getElementById("tax");
    const totalEl = document.getElementById("total");
    const itemCountText = document.getElementById("cartItemCountText");
    const clearCartBtn = document.getElementById("clear-cart");
    const promoInput = document.getElementById("promo-code");
    const applyPromoBtn = document.getElementById("apply-promo");
    const promoMessage = document.getElementById("promo-message");

    function calculateSummary() {
      const cart = getCart();
      const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
      const tax = subtotal * 0.05;
      const discount = getPromoDiscount();
      const total = Math.max(subtotal + tax - discount, 0);

      if (subtotalEl) subtotalEl.textContent = formatLKR(subtotal);
      if (taxEl) taxEl.textContent = formatLKR(tax);
      if (totalEl) totalEl.textContent = formatLKR(total);
      if (itemCountText) {
        const qty = cart.reduce((sum, item) => sum + item.quantity, 0);
        itemCountText.textContent = `${qty} Item${qty === 1 ? "" : "s"} Selected`;
      }
    }

    function buildCartItem(item) {
      return `
        <div class="bg-white dark:bg-slate-900 rounded-xl p-4 md:p-6 shadow-sm border border-slate-200 dark:border-slate-800 transition-all hover:shadow-md">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <div class="col-span-1 md:col-span-6 flex gap-6">
              <div class="w-24 h-32 shrink-0 bg-slate-100 dark:bg-slate-800 rounded-lg overflow-hidden shadow-sm">
                <img class="w-full h-full object-cover" src="${item.image}" alt="${item.title}">
              </div>
              <div class="flex flex-col justify-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white leading-snug">${item.title}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">${item.author}</p>
                <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mt-2 flex items-center gap-1">
                  <span class="material-symbols-outlined text-sm">check_circle</span> In Stock
                </p>
                <button class="remove-item text-xs font-semibold text-rose-500 hover:text-rose-600 mt-4 flex items-center gap-1 uppercase tracking-tighter" data-id="${item.id}">
                  <span class="material-symbols-outlined text-sm">delete</span> Remove
                </button>
              </div>
            </div>

            <div class="col-span-1 md:col-span-2 text-center">
              <span class="text-lg font-semibold">${formatLKR(item.price)}</span>
            </div>

            <div class="col-span-1 md:col-span-2 flex justify-center">
              <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                <button class="decrease-qty p-2 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500" data-id="${item.id}">
                  <span class="material-symbols-outlined text-sm">remove</span>
                </button>
                <input class="w-10 text-center border-none focus:ring-0 bg-transparent text-sm font-bold" type="text" value="${item.quantity}" readonly />
                <button class="increase-qty p-2 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500" data-id="${item.id}">
                  <span class="material-symbols-outlined text-sm">add</span>
                </button>
              </div>
            </div>

            <div class="col-span-1 md:col-span-2 text-right">
              <span class="text-lg font-bold text-slate-900 dark:text-white">${formatLKR(item.price * item.quantity)}</span>
            </div>
          </div>
        </div>
      `;
    }

    function render() {
      const cart = getCart();

      if (cart.length === 0) {
        container.innerHTML = `
          <div class="bg-white dark:bg-slate-900 rounded-xl p-10 text-center border border-slate-200 dark:border-slate-800">
            <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">shopping_cart</span>
            <h3 class="text-2xl font-bold mb-2">Your cart is empty</h3>
            <p class="text-slate-500 mb-6">Looks like you haven't added any books yet.</p>
            <a href="shop.html" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-primary/90">
              <span class="material-symbols-outlined">menu_book</span>
              Browse Books
            </a>
          </div>
        `;
      } else {
        container.innerHTML = cart.map(buildCartItem).join("");
      }

      calculateSummary();
      updateCartBadge();
      attachCartEvents();
    }

    function attachCartEvents() {
      container.querySelectorAll(".increase-qty").forEach((btn) => {
        btn.addEventListener("click", () => {
          const cart = getCart();
          const item = cart.find((i) => i.id === btn.dataset.id);
          if (item) item.quantity += 1;
          saveCart(cart);
          render();
        });
      });

      container.querySelectorAll(".decrease-qty").forEach((btn) => {
        btn.addEventListener("click", () => {
          const cart = getCart();
          const item = cart.find((i) => i.id === btn.dataset.id);
          if (item && item.quantity > 1) item.quantity -= 1;
          saveCart(cart);
          render();
        });
      });

      container.querySelectorAll(".remove-item").forEach((btn) => {
        btn.addEventListener("click", () => {
          let cart = getCart();
          cart = cart.filter((i) => i.id !== btn.dataset.id);
          saveCart(cart);
          render();
        });
      });
    }

    if (clearCartBtn) {
      clearCartBtn.addEventListener("click", () => {
        if (confirm("Are you sure you want to clear the entire cart?")) {
          saveCart([]);
          savePromoDiscount(0);
          if (promoInput) promoInput.value = "";
          if (promoMessage) promoMessage.textContent = "";
          render();
        }
      });
    }

    if (applyPromoBtn) {
      applyPromoBtn.addEventListener("click", () => {
        const code = (promoInput?.value || "").trim().toUpperCase();

        if (code === "SAVE10") {
          const subtotal = getCart().reduce((sum, item) => sum + item.price * item.quantity, 0);
          const discount = subtotal * 0.10;
          savePromoDiscount(discount);
          if (promoMessage) {
            promoMessage.textContent = "Promo code applied: 10% discount";
            promoMessage.className = "text-xs mt-2 text-emerald-600";
          }
        } else if (code === "BOOK50") {
          savePromoDiscount(50);
          if (promoMessage) {
            promoMessage.textContent = "Promo code applied: LKR 50 discount";
            promoMessage.className = "text-xs mt-2 text-emerald-600";
          }
        } else {
          savePromoDiscount(0);
          if (promoMessage) {
            promoMessage.textContent = "Invalid promo code";
            promoMessage.className = "text-xs mt-2 text-rose-500";
          }
        }

        calculateSummary();
      });
    }

    render();
  }

  function setupContactForm() {
    const form = document.getElementById("contactForm");
    if (!form) return;

    const fullName = document.getElementById("fullName");
    const email = document.getElementById("email");
    const message = document.getElementById("message");
    const contactMessage = document.getElementById("contactMessage");

    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const nameValue = fullName.value.trim();
      const emailValue = email.value.trim();
      const messageValue = message.value.trim();

      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!nameValue || !emailValue || !messageValue) {
        contactMessage.textContent = "Please fill in all required fields.";
        contactMessage.className = "text-sm font-medium text-rose-500";
        return;
      }

      if (!emailPattern.test(emailValue)) {
        contactMessage.textContent = "Please enter a valid email address.";
        contactMessage.className = "text-sm font-medium text-rose-500";
        return;
      }

      contactMessage.textContent = "Your message has been sent successfully!";
      contactMessage.className = "text-sm font-medium text-emerald-600";
      form.reset();
    });
  }
  

  updateCartBadge();
  setupHomeSearch();
  setupShopSearchAndSort();
  renderCartPage();
  setupContactForm();
});
