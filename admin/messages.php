<?php require_once 'header.php'; ?>

<?php
$message = '';

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Message deleted successfully.";
    }
    $stmt->close();
}

// Fetch messages
$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>

<script>document.querySelector("header h1").innerText = "Contact Messages";</script>

<?php if ($message): ?>
    <div class="p-4 rounded-lg mb-6 flex items-center gap-2 font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
        <span class="material-symbols-outlined">check_circle</span>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
        <h2 class="font-bold text-slate-800">All Messages</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50/50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-3 w-1/4">Sender details</th>
                    <th class="px-6 py-3 w-1/2">Message</th>
                    <th class="px-6 py-3">Received On</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if ($messages && $messages->num_rows > 0): ?>
                    <?php while($m = $messages->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 align-top">
                                <p class="font-bold text-slate-800"><?php echo htmlspecialchars($m['name']); ?></p>
                                <a href="mailto:<?php echo htmlspecialchars($m['email']); ?>" class="text-xs font-semibold text-primary hover:underline">
                                    <?php echo htmlspecialchars($m['email']); ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="text-slate-700 bg-slate-50 p-4 rounded-lg border border-slate-100 whitespace-pre-wrap font-medium">
<?php echo htmlspecialchars($m['message']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <span class="bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase truncate block max-w-max">
                                    <?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($m['created_at']))); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <a href="messages.php?delete_id=<?php echo $m['id']; ?>" onclick="return confirm('Delete this message permanently?');" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded-lg transition-colors inline-block opacity-0 group-hover:opacity-100">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2 text-slate-300 block">inbox</span>
                        No messages found.
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>
