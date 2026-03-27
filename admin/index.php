<?php require_once 'header.php'; ?>

<?php
$users_count = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
$products_count = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0] ?? 0;
$messages_count = $conn->query("SELECT COUNT(*) FROM contact_messages")->fetch_row()[0] ?? 0;

$recent_messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
?>

<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-6">
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl">
                <span class="material-symbols-outlined text-4xl">inventory_2</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Products</p>
                <p class="text-3xl font-black text-slate-900"><?php echo htmlspecialchars($products_count); ?></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-6">
            <div class="p-4 bg-blue-50 text-blue-600 rounded-xl">
                <span class="material-symbols-outlined text-4xl">group</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Registered Users</p>
                <p class="text-3xl font-black text-slate-900"><?php echo htmlspecialchars($users_count); ?></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-6">
            <div class="p-4 bg-purple-50 text-purple-600 rounded-xl">
                <span class="material-symbols-outlined text-4xl">forum</span>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Contact Messages</p>
                <p class="text-3xl font-black text-slate-900"><?php echo htmlspecialchars($messages_count); ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">mail</span>
                Recent Messages
            </h2>
            <a href="messages.php" class="text-xs font-bold text-primary hover:underline uppercase">View All</a>
        </div>
        
        <?php if ($recent_messages && $recent_messages->num_rows > 0): ?>
            <div class="divide-y divide-slate-100">
                <?php while($msg = $recent_messages->fetch_assoc()): ?>
                    <div class="p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-primary"><?php echo htmlspecialchars($msg['name']); ?></h3>
                            <span class="text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">
                                <?php echo htmlspecialchars(date('M d, Y', strtotime($msg['created_at']))); ?>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 font-medium mb-2"><?php echo htmlspecialchars($msg['email']); ?></p>
                        <p class="text-sm text-slate-700 line-clamp-2"><?php echo htmlspecialchars($msg['message']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="p-8 text-center text-slate-500">
                <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">inbox</span>
                <p>No messages received yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>
