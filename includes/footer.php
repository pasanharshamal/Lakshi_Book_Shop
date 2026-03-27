  <footer class="mt-auto bg-slate-900 text-slate-400 pt-16 pb-8 border-t-4 border-primary">
    <div class="container mx-auto px-4 md:px-20">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
        <div>
          <div class="flex items-center gap-3 mb-6">
            <div class="bg-primary p-1 rounded-lg">
              <span class="material-symbols-outlined text-white text-2xl">auto_stories</span>
            </div>
            <h2 class="text-xl font-black text-white uppercase tracking-tighter">Lakshi</h2>
          </div>
          <p class="text-sm leading-relaxed mb-6">Lakshi Book Shop is Sri Lanka's destination for fiction, children's books, novels, translated books, short stories and educational titles.</p>
        </div>
        <div>
          <h4 class="text-white font-black uppercase text-sm mb-6 tracking-widest">Customer Service</h4>
          <ul class="space-y-4 text-sm font-medium">
            <li><a class="hover:text-primary transition-colors" href="contact.php">Contact Us</a></li>
            <li><a class="hover:text-primary transition-colors" href="shop.php">Shop</a></li>
            <li><a class="hover:text-primary transition-colors" href="cart.php">Cart</a></li>
            <li><a class="hover:text-primary transition-colors" href="wishlist.php">Wishlist</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-black uppercase text-sm mb-6 tracking-widest">Popular Categories</h4>
          <ul class="space-y-4 text-sm font-medium">
            <li><a class="hover:text-primary transition-colors" href="shop.php?category=fiction">Fiction</a></li>
            <li><a class="hover:text-primary transition-colors" href="shop.php?category=children-books">Children's Books</a></li>
            <li><a class="hover:text-primary transition-colors" href="shop.php?category=novels">Novels</a></li>
            <li><a class="hover:text-primary transition-colors" href="shop.php?category=translated-books">Translated Books</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-white font-black uppercase text-sm mb-6 tracking-widest">Newsletter</h4>
          <p class="text-sm mb-6">Subscribe to get updates on new arrivals and special offers.</p>
          <div class="flex gap-2">
            <input class="bg-slate-800 border-none rounded-lg py-2 px-4 text-sm focus:ring-1 focus:ring-primary w-full" placeholder="Email Address" type="email" />
            <button class="bg-primary text-white p-2 px-4 rounded-lg font-bold text-xs uppercase">Join</button>
          </div>
        </div>
      </div>

      <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <p class="text-[10px] font-bold uppercase tracking-widest">© 2026 Lakshi Book Shop (Pvt) Ltd. All Rights Reserved.</p>
        <div class="flex gap-4 text-xs">
          <a class="hover:text-primary" href="#">Privacy Policy</a>
          <a class="hover:text-primary" href="#">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>

  <?php
  require_once __DIR__ . '/db.php';
  // Load featured products dynamically from database for index.php to use
  $featuredResult = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 8");
  $featuredBooksDyn = [];
  if ($featuredResult && $featuredResult->num_rows > 0) {
      while($row = $featuredResult->fetch_assoc()) {
          $featuredBooksDyn[] = [
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
    // Page-specific logic remains if needed.
    // Shared functions like getWishlist, saveWishlist, etc. are now in Js/app.js
  </script>
</body>
</html>
