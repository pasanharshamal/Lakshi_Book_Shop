/**
 * Shared JavaScript for Lakshi Book Shop
 * Handles Cart, Wishlist, and Search functionality across all pages.
 */

// --- Constants ---
const categoryLabels = {
    "fiction": "Fiction",
    "children-books": "Children's Books",
    "novels": "Novels",
    "translated-books": "Translated Books",
    "short-story": "Short Story",
    "sinhala-short-stories": "කෙටි කතා",
    "child-stories": "ළමා කතා",
    "educational": "Educational"
};

// --- Wishlist Logic ---
function getWishlist() {
    return JSON.parse(localStorage.getItem("wishlist")) || [];
}

function saveWishlist(items) {
    localStorage.setItem("wishlist", JSON.stringify(items));
    updateWishlistCount();
}

function addToWishlist(book) {
    const wishlist = getWishlist();
    const exists = wishlist.find(item => String(item.id) === String(book.id));
    if (exists) {
        alert("This book is already in your wishlist.");
        return;
    }
    wishlist.push(book);
    saveWishlist(wishlist);
    alert(book.title + " added to wishlist.");
}

function updateWishlistCount() {
    const wishlistCount = document.getElementById("wishlistCount");
    if (wishlistCount) wishlistCount.textContent = getWishlist().length;
}

// --- Cart Logic ---
function getCart() {
    return JSON.parse(localStorage.getItem("cart")) || [];
}

function saveCart(items) {
    localStorage.setItem("cart", JSON.stringify(items));
    updateCartCount();
}

function addToCart(book) {
    const cart = getCart();
    const existingItem = cart.find(item => String(item.id) === String(book.id));
    if (existingItem) {
        existingItem.quantity = (existingItem.quantity || 1) + 1;
    } else {
        cart.push({ ...book, quantity: 1 });
    }
    saveCart(cart);
    alert(book.title + " added to cart.");
}

function updateCartCount() {
    const cartCount = document.getElementById("cartCount");
    if (cartCount) {
        const cart = getCart();
        const totalQty = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        cartCount.textContent = totalQty;
    }
}

// --- Utilities ---
function escapeHtml(text) {
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function goToShopSearch() {
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        const term = searchInput.value.trim();
        if (term) {
            window.location.href = `shop.php?search=${encodeURIComponent(term)}`;
        } else {
            window.location.href = "shop.php";
        }
    }
}

// --- Global Event Listeners ---
document.addEventListener("DOMContentLoaded", () => {
    updateWishlistCount();
    updateCartCount();

    // Global Search button in header
    const searchBtn = document.getElementById("searchBtn");
    if (searchBtn) {
        searchBtn.addEventListener("click", goToShopSearch);
    }

    const globalSearchInput = document.getElementById("searchInput");
    if (globalSearchInput) {
        globalSearchInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") goToShopSearch();
        });
    }

    // Global click listener for Add to Cart/Wishlist buttons (handles dynamically rendered content)
    document.addEventListener("click", function (e) {
        const wishlistBtn = e.target.closest(".add-to-wishlist");
        const cartBtn = e.target.closest(".add-to-cart");

        if (wishlistBtn) {
            const book = {
                id: wishlistBtn.dataset.id,
                title: wishlistBtn.dataset.title,
                author: wishlistBtn.dataset.author,
                category: wishlistBtn.dataset.category,
                price: Number(wishlistBtn.dataset.price),
                image: wishlistBtn.dataset.image
            };
            addToWishlist(book);
        }

        if (cartBtn) {
            const book = {
                id: cartBtn.dataset.id,
                title: cartBtn.dataset.title,
                author: cartBtn.dataset.author,
                category: cartBtn.dataset.category,
                price: Number(cartBtn.dataset.price),
                image: cartBtn.dataset.image
            };
            addToCart(book);
        }
    });
});
