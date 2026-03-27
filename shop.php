<?php require_once 'includes/header.php'; ?>

  <main class="container mx-auto px-4 md:px-20 py-8 space-y-8">
    <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 md:p-8">
      <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
        <div>
          <p class="text-primary font-bold uppercase tracking-widest text-xs mb-2">Lakshi Collection</p>
          <h2 class="text-3xl md:text-4xl font-black tracking-tight">Browse All Books</h2>
          <p class="text-slate-500 mt-2 max-w-2xl">
            Explore fiction, children's books, novels, translated books, short stories, Sinhala short stories, children stories and educational books.
          </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
          <select id="categoryFilter" class="bg-slate-100 dark:bg-slate-800 border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary">
            <option value="all">All Categories</option>
            <option value="fiction">Fiction</option>
            <option value="children-books">Children's Books</option>
            <option value="novels">Novels</option>
            <option value="translated-books">Translated Books</option>
            <option value="short-story">Short Story</option>
            <option value="sinhala-short-stories">කෙටි කතා</option>
            <option value="child-stories">ළමා කතා</option>
            <option value="educational">Educational</option>
          </select>

          <select id="sortFilter" class="bg-slate-100 dark:bg-slate-800 border-none rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary">
            <option value="default">Sort By</option>
            <option value="price-low">Price: Low to High</option>
            <option value="price-high">Price: High to Low</option>
            <option value="name-asc">Name: A to Z</option>
            <option value="name-desc">Name: Z to A</option>
          </select>
        </div>
      </div>
    </section>

    <section>
      <div class="flex flex-wrap gap-3">
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-primary text-white" data-category="all" type="button">All</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="fiction" type="button">Fiction</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="children-books" type="button">Children's Books</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="novels" type="button">Novels</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="translated-books" type="button">Translated Books</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="short-story" type="button">Short Story</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="sinhala-short-stories" type="button">කෙටි කතා</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="child-stories" type="button">ළමා කතා</button>
        <button class="category-chip px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700" data-category="educational" type="button">Educational</button>
      </div>
    </section>

    <section class="flex items-center justify-between">
      <h3 id="resultsTitle" class="text-xl font-black uppercase tracking-tight">All Books</h3>
      <p id="resultsCount" class="text-sm font-semibold text-slate-500">0 Books Found</p>
    </section>

    <section>
      <div id="booksGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6"></div>

      <div id="noResults" class="hidden bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-10 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 mb-4">search_off</span>
        <h3 class="text-xl font-black mb-2">No books found</h3>
        <p class="text-slate-500">Try a different search term or choose another category.</p>
      </div>
    </section>
  </main>

  <?php
  require_once 'includes/db.php';
  // Load products dynamically from database
  $result = $conn->query("SELECT * FROM products ORDER BY id ASC");
  $dynamicBooks = [];
  if ($result && $result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $dynamicBooks[] = [
              'id' => $row['id'],
              'title' => $row['title'],
              'author' => $row['author'],
              'price' => $row['price'],
              'category' => $row['category'],
              'image' => $row['image']
          ];
      }
  }
  ?>

  <script>
    const booksData = <?php echo json_encode($dynamicBooks); ?>;

    const booksGrid = document.getElementById("booksGrid");
    const searchInput = document.getElementById("searchInput");
    const categoryFilter = document.getElementById("categoryFilter");
    const sortFilter = document.getElementById("sortFilter");
    const resultsTitle = document.getElementById("resultsTitle");
    const resultsCount = document.getElementById("resultsCount");
    const noResults = document.getElementById("noResults");
    const categoryChips = document.querySelectorAll(".category-chip");

    function formatCategoryTitle(category) {
      if (category === "all") return "All Books";
      return categoryLabels[category] || "Books";
    }

    function updateChips(activeCategory) {
      categoryChips.forEach(chip => {
        if (chip.dataset.category === activeCategory) {
          chip.classList.add("bg-primary", "text-white");
          chip.classList.remove("bg-white", "dark:bg-slate-800", "border", "border-slate-200", "dark:border-slate-700");
        } else {
          chip.classList.remove("bg-primary", "text-white");
          chip.classList.add("bg-white", "dark:bg-slate-800", "border", "border-slate-200", "dark:border-slate-700");
        }
      });
    }

    function escapeHtml(text) {
      return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    function renderBooks(bookList) {
      if (bookList.length === 0 && booksData.length === 0) {
        console.warn("No books found in database. Please check your MySQL setup.");
        booksGrid.innerHTML = `
          <div class="col-span-full bg-orange-50 border border-orange-200 rounded-xl p-8 text-center text-orange-800">
            <h3 class="font-bold text-lg mb-2">Notice: No books found in your library.</h3>
            <p>Please make sure you have imported <strong>database.sql</strong> into your phpMyAdmin <strong>lakshi_bookshop</strong> database.</p>
            <p class="mt-4"><a href="check_db.php" class="underline font-bold">Run Database Check</a></p>
          </div>
        `;
        return;
      }
      booksGrid.innerHTML = "";
      bookList.forEach(book => {
        booksGrid.innerHTML += `
          <div class="book-card bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-xl transition-all group flex flex-col"
            data-id="${book.id}"
            data-category="${escapeHtml(book.category)}"
            data-title="${escapeHtml(book.title)}"
            data-author="${escapeHtml(book.author)}"
            data-price="${book.price}">
            <div class="relative aspect-[3/4] bg-slate-100 overflow-hidden">
              <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                src="${escapeHtml(book.image)}"
                alt="${escapeHtml(book.title)} Cover" />
            </div>
            <div class="p-4 flex-1 flex flex-col">
              <p class="text-[10px] font-bold uppercase text-slate-400 mb-1">${escapeHtml(categoryLabels[book.category] || book.category)}</p>
              <h3 class="font-bold text-sm mb-1 line-clamp-2">${escapeHtml(book.title)}</h3>
              <p class="text-xs text-slate-500 mb-3">${escapeHtml(book.author)}</p>
              <div class="mt-auto">
                <span class="text-primary font-black">LKR ${Number(book.price).toFixed(2)}</span>
                <button class="add-to-cart w-full mt-3 bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all py-2 rounded-lg text-xs font-bold uppercase"
                  data-id="${book.id}"
                  data-title="${escapeHtml(book.title)}"
                  data-author="${escapeHtml(book.author)}"
                  data-category="${escapeHtml(book.category)}"
                  data-price="${book.price}"
                  data-image="${escapeHtml(book.image)}"
                  type="button">
                  Add to Cart
                </button>
                <button class="add-to-wishlist w-full mt-2 bg-pink-100 text-pink-600 hover:bg-pink-600 hover:text-white transition-all py-2 rounded-lg text-xs font-bold uppercase"
                  data-id="${book.id}"
                  data-title="${escapeHtml(book.title)}"
                  data-author="${escapeHtml(book.author)}"
                  data-category="${escapeHtml(book.category)}"
                  data-price="${book.price}"
                  data-image="${escapeHtml(book.image)}"
                  type="button">
                  Add to Wishlist
                </button>
              </div>
            </div>
          </div>
        `;
      });
    }

    function filterAndSortBooks() {
      const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : "";
      const selectedCategory = categoryFilter ? categoryFilter.value : "all";
      const selectedSort = sortFilter ? sortFilter.value : "default";

      const filtered = booksData.filter(book => {
        const title = book.title.toLowerCase();
        const author = book.author.toLowerCase();
        const category = (categoryLabels[book.category] || book.category).toLowerCase();

        const matchesSearch =
          title.includes(searchTerm) ||
          author.includes(searchTerm) ||
          category.includes(searchTerm);

        const matchesCategory =
          selectedCategory === "all" || book.category === selectedCategory;

        return matchesSearch && matchesCategory;
      });

      filtered.sort((a, b) => {
        const priceA = Number(a.price);
        const priceB = Number(b.price);
        const titleA = a.title.toLowerCase();
        const titleB = b.title.toLowerCase();

        switch (selectedSort) {
          case "price-low":
            return priceA - priceB;
          case "price-high":
            return priceB - priceA;
          case "name-asc":
            return titleA.localeCompare(titleB);
          case "name-desc":
            return titleB.localeCompare(titleA);
          default:
            return a.id - b.id;
        }
      });

      renderBooks(filtered);
      if(resultsTitle) resultsTitle.textContent = formatCategoryTitle(selectedCategory);
      if(resultsCount) resultsCount.textContent = `${filtered.length} Books Found`;
      if(noResults) noResults.classList.toggle("hidden", filtered.length !== 0);
      if(booksGrid) booksGrid.classList.toggle("hidden", filtered.length === 0);
      updateChips(selectedCategory);
    }

    function updateUrlParams() {
      const params = new URLSearchParams();
      const category = categoryFilter ? categoryFilter.value : "all";
      const search = searchInput ? searchInput.value.trim() : "";

      if (category && category !== "all") params.set("category", category);
      if (search) params.set("search", search);

      const url = params.toString() ? `shop.php?${params.toString()}` : "shop.php";
      window.history.replaceState({}, "", url);
    }

    function runFilters() {
      filterAndSortBooks();
      updateUrlParams();
    }

    if(categoryFilter) categoryFilter.addEventListener("change", runFilters);
    if(sortFilter) sortFilter.addEventListener("change", filterAndSortBooks);
    if(searchInput) searchInput.addEventListener("input", runFilters);
    if(searchBtn) searchBtn.addEventListener("click", runFilters);

    if(searchInput) {
        searchInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") runFilters();
        });
    }

    categoryChips.forEach(chip => {
      chip.addEventListener("click", () => {
        if(categoryFilter) categoryFilter.value = chip.dataset.category;
        runFilters();
      });
    });

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

    const urlParams = new URLSearchParams(window.location.search);
    const categoryFromUrl = urlParams.get("category");
    const searchFromUrl = urlParams.get("search");

    if (categoryFromUrl && categoryFilter) categoryFilter.value = categoryFromUrl;
    if (searchFromUrl && searchInput) searchInput.value = searchFromUrl;

    document.addEventListener("DOMContentLoaded", filterAndSortBooks);
  </script>

<?php require_once 'includes/footer.php'; ?>