<?php 
require_once 'includes/header.php'; 
require_once 'includes/db.php';

// Fetch trending/featured books (limit to 4 for home page)
$result = $conn->query("SELECT * FROM products ORDER BY id ASC LIMIT 4");
$featuredBooks = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $featuredBooks[] = [
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
  // Shared functions and categoryLabels are now in Js/app.js
  // Database-provided books for this page:
  const featuredBooks = <?php echo json_encode($featuredBooks); ?>;
</script>

  <main class="container mx-auto px-4 md:px-20 py-8 space-y-12">
    <div class="grid grid-cols-12 gap-6">
      <div class="col-span-12 lg:col-span-9">
        <div class="relative rounded-xl overflow-hidden aspect-[21/9] bg-slate-200">
          <img
            alt="Promotional Banner"
            class="w-full h-full object-cover"
            src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1200&q=80"
          />
          <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-transparent flex flex-col justify-center px-12 text-white">
            <span class="bg-primary text-xs font-bold px-3 py-1 rounded-full w-max mb-4 uppercase tracking-widest">New Collection</span>
            <h2 class="text-4xl md:text-5xl font-black mb-2">Discover Your Next Great Read</h2>
            <p class="text-lg md:text-xl opacity-90 mb-6 max-w-md">Explore fiction, children's books, novels, translated books, short stories and educational titles.</p>
            <a href="shop.php" class="bg-primary text-white px-8 py-3 rounded-lg font-bold w-max hover:bg-blue-700 transition-all">Shop Now</a>
          </div>
        </div>
      </div>

      <div class="hidden lg:flex lg:col-span-3 flex-col gap-6">
        <div class="flex-1 bg-yellow-100 rounded-xl p-6 flex flex-col justify-between border border-yellow-200">
          <div>
            <h3 class="text-yellow-800 font-black text-xl leading-tight">Children's<br />Books</h3>
            <p class="text-yellow-700 text-sm mt-2">Fun books for young readers.</p>
          </div>
          <a href="shop.php?category=children-books" class="bg-yellow-800 text-white py-2 rounded-lg text-sm font-bold uppercase text-center">View All</a>
        </div>
        <div class="flex-1 bg-blue-50 rounded-xl p-6 flex flex-col justify-between border border-blue-100">
          <div>
            <h3 class="text-blue-900 font-black text-xl leading-tight">School<br />Essentials</h3>
            <p class="text-blue-800 text-sm mt-2">Everything for classroom learning.</p>
          </div>
          <a href="shop.php?category=educational" class="bg-blue-900 text-white py-2 rounded-lg text-sm font-bold uppercase text-center">Explore</a>
        </div>
      </div>
    </div>

    <section>
      <div class="flex justify-between items-end mb-6">
        <div>
          <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Shop by Category</h2>
          <div class="h-1.5 w-20 bg-primary mt-2 rounded-full"></div>
        </div>
        <a class="text-primary font-bold text-sm flex items-center gap-1 hover:underline" href="shop.php">
          View All Categories <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </a>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
        <a href="shop.php?category=fiction" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">menu_book</span>
          </div>
          <span class="font-bold text-sm uppercase">Fiction</span>
        </a>

        <a href="shop.php?category=children-books" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">toys</span>
          </div>
          <span class="font-bold text-sm uppercase">Children's Books</span>
        </a>

        <a href="shop.php?category=novels" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">auto_stories</span>
          </div>
          <span class="font-bold text-sm uppercase">Novels</span>
        </a>

        <a href="shop.php?category=translated-books" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">translate</span>
          </div>
          <span class="font-bold text-sm uppercase">Translated Books</span>
        </a>

        <a href="shop.php?category=short-story" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">article</span>
          </div>
          <span class="font-bold text-sm uppercase">Short Story</span>
        </a>

        <a href="shop.php?category=sinhala-short-stories" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">library_books</span>
          </div>
          <span class="font-bold text-sm uppercase">කෙටි කතා</span>
        </a>

        <a href="shop.php?category=child-stories" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">child_care</span>
          </div>
          <span class="font-bold text-sm uppercase">ළමා කතා</span>
        </a>

        <a href="shop.php?category=educational" class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-center group">
          <div class="bg-blue-50 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white">school</span>
          </div>
          <span class="font-bold text-sm uppercase">Educational</span>
        </a>
      </div>
    </section>

    <section>
      <div class="flex justify-between items-end mb-6">
        <div>
          <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Trending Now</h2>
          <div class="h-1.5 w-20 bg-primary mt-2 rounded-full"></div>
        </div>
        <a href="shop.php" class="text-primary font-bold text-sm hover:underline">View All</a>
      </div>

      <div id="featuredBooksGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6"></div>
    </section>
  </main>
  
  <script>
    function renderFeaturedBooks() {
      const container = document.getElementById("featuredBooksGrid");
      if (!container || typeof featuredBooks === 'undefined' || featuredBooks.length === 0) {
        if (container) container.innerHTML = `<p class='col-span-full text-center text-slate-500 py-10'>No trending books to show. <a href='check_db.php' class='underline'>Check Database</a></p>`;
        return;
      }
      container.innerHTML = "";

      featuredBooks.forEach(book => {
        container.innerHTML += `
          <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-xl transition-all group flex flex-col">
            <div class="relative aspect-[3/4] bg-slate-100 overflow-hidden">
              <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="${escapeHtml(book.image)}" alt="${escapeHtml(book.title)} Cover" />
              <button
                class="add-to-wishlist absolute top-3 right-3 bg-white/90 hover:bg-red-500 hover:text-white text-red-500 w-10 h-10 rounded-full flex items-center justify-center shadow transition-all"
                data-id="${book.id}"
                data-title="${escapeHtml(book.title)}"
                data-author="${escapeHtml(book.author)}"
                data-category="${escapeHtml(book.category)}"
                data-price="${book.price}"
                data-image="${escapeHtml(book.image)}"
                type="button">
                <span class="material-symbols-outlined text-sm">favorite</span>
              </button>
            </div>
            <div class="p-4 flex-1 flex flex-col">
              <p class="text-[10px] font-bold uppercase text-slate-400 mb-1">${escapeHtml(categoryLabels[book.category])}</p>
              <h3 class="font-bold text-sm mb-1 line-clamp-2">${escapeHtml(book.title)}</h3>
              <p class="text-xs text-slate-500 mb-3">${escapeHtml(book.author)}</p>
              <div class="mt-auto space-y-2">
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-primary font-black">LKR ${Number(book.price).toFixed(2)}</span>
                </div>
                <button
                  class="add-to-cart w-full bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all py-2 rounded-lg text-xs font-bold uppercase flex items-center justify-center gap-2"
                  data-id="${book.id}"
                  data-title="${escapeHtml(book.title)}"
                  data-author="${escapeHtml(book.author)}"
                  data-category="${escapeHtml(book.category)}"
                  data-price="${book.price}"
                  data-image="${escapeHtml(book.image)}"
                  type="button">
                  <span class="material-symbols-outlined text-sm">shopping_cart</span> Add to Cart
                </button>
              </div>
            </div>
          </div>
        `;
      });
    }
    document.addEventListener("DOMContentLoaded", renderFeaturedBooks);
  </script>

<?php require_once 'includes/footer.php'; ?>