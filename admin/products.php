<?php require_once 'header.php'; ?>

<?php
$message = '';
$messageType = '';

// Handle Delete
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Product deleted successfully.";
        $messageType = 'success';
    } else {
        $message = "Error deleting product.";
        $messageType = 'error';
    }
    $stmt->close();
}

// Handle Add / Edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) && !empty($_POST['id']) ? intval($_POST['id']) : 0;
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $image = trim($_POST['image'] ?? '');

    if (!empty($title) && !empty($author) && !empty($price)) {
        if ($id > 0) {
            // Update
            $stmt = $conn->prepare("UPDATE products SET title=?, author=?, category=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("sssdsi", $title, $author, $category, $price, $image, $id);
            if ($stmt->execute()) {
                $message = "Product updated successfully.";
                $messageType = 'success';
            } else {
                $message = "Error updating product.";
                $messageType = 'error';
            }
            $stmt->close();
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO products (title, author, category, price, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssds", $title, $author, $category, $price, $image);
            if ($stmt->execute()) {
                $message = "Product added successfully.";
                $messageType = 'success';
            } else {
                $message = "Error adding product.";
                $messageType = 'error';
            }
            $stmt->close();
        }
    } else {
        $message = "Title, Author, and Price are required.";
        $messageType = 'error';
    }
}

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 50");
?>

<script>document.querySelector("header h1").innerText = "Manage Products";</script>

<?php if ($message): ?>
    <div class="p-4 rounded-lg mb-6 flex items-center gap-2 font-bold <?php echo $messageType === 'success' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100'; ?>">
        <span class="material-symbols-outlined"><?php echo $messageType === 'success' ? 'check_circle' : 'error'; ?></span>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="font-bold text-slate-800">Product List (Showing latest 50)</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Book</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if ($products && $products->num_rows > 0): ?>
                        <?php while($p = $products->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-md overflow-hidden bg-slate-200 shrink-0">
                                            <?php if($p['image']): ?>
                                                <img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-full h-full object-cover" />
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800"><?php echo htmlspecialchars($p['title']); ?></p>
                                            <p class="text-xs text-slate-500"><?php echo htmlspecialchars($p['author']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-1 rounded-full uppercase">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold">
                                    LKR <?php echo number_format($p['price'], 2); ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($p)); ?>)" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded-lg transition-colors inline-block mr-1">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </button>
                                    <a href="products.php?delete_id=<?php echo $p['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition-colors inline-block">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form -->
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-6">
            <h2 id="formTitle" class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_circle</span>
                Add New Product
            </h2>
            
            <form action="products.php" method="POST" class="space-y-4">
                <input type="hidden" name="id" id="prodId" value="">
                
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Title</label>
                    <input type="text" name="title" id="prodTitle" required class="w-full px-4 py-2 text-sm bg-slate-50 border-slate-200 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Author</label>
                    <input type="text" name="author" id="prodAuthor" required class="w-full px-4 py-2 text-sm bg-slate-50 border-slate-200 rounded-lg focus:ring-primary focus:border-primary">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase">Category</label>
                        <select name="category" id="prodCat" class="w-full px-4 py-2 text-sm bg-slate-50 border-slate-200 rounded-lg focus:ring-primary focus:border-primary">
                            <option value="fiction">Fiction</option>
                            <option value="children-books">Children's Books</option>
                            <option value="novels">Novels</option>
                            <option value="translated-books">Translated Books</option>
                            <option value="short-story">Short Story</option>
                            <option value="sinhala-short-stories">Sinhala Short Stories</option>
                            <option value="child-stories">Child Stories</option>
                            <option value="educational">Educational</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase">Price (LKR)</label>
                        <input type="number" step="0.01" name="price" id="prodPrice" required class="w-full px-4 py-2 text-sm bg-slate-50 border-slate-200 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase">Cover Image URL</label>
                    <input type="url" name="image" id="prodImg" class="w-full px-4 py-2 text-sm bg-slate-50 border-slate-200 rounded-lg focus:ring-primary focus:border-primary" placeholder="https://...">
                </div>

                <div class="pt-2 flex gap-2">
                    <button type="submit" class="flex-1 bg-primary text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition uppercase text-xs">
                        Save Product
                    </button>
                    <button type="button" onclick="resetForm()" class="px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition">
                        <span class="material-symbols-outlined text-sm flex items-center justify-center">close</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editProduct(product) {
        document.getElementById('prodId').value = product.id;
        document.getElementById('prodTitle').value = product.title;
        document.getElementById('prodAuthor').value = product.author;
        document.getElementById('prodCat').value = product.category;
        document.getElementById('prodPrice').value = product.price;
        document.getElementById('prodImg').value = product.image;
        document.getElementById('formTitle').innerHTML = '<span class="material-symbols-outlined text-emerald-500">edit</span> Edit Product';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('prodId').value = '';
        document.getElementById('prodTitle').value = '';
        document.getElementById('prodAuthor').value = '';
        document.getElementById('prodCat').value = 'fiction';
        document.getElementById('prodPrice').value = '';
        document.getElementById('prodImg').value = '';
        document.getElementById('formTitle').innerHTML = '<span class="material-symbols-outlined text-primary">add_circle</span> Add New Product';
    }
</script>

<?php require_once 'footer.php'; ?>
