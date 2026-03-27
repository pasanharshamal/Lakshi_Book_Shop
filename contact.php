<?php
// Safely import essential dependencies
require_once 'includes/db.php';
require_once 'includes/functions.php';

$message_status = '';

// Check if contact form has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Read and sanitize user provided message inputs safely
    $name = sanitize_input($_POST['fullName'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? '');

    // Validate input existence
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Securely prepare SQL insertion pattern against injection attempts
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        
        // Formulate final message structure storing subject prefix 
        $full_message = "Subject: " . $subject . "\n\n" . $message;
        $stmt->bind_param("sss", $name, $email, $full_message);
        
        // Execute insertion into backend database 
        if ($stmt->execute()) {
            $message_status = "<p class='text-emerald-600 font-medium mb-4'>Your message has been sent successfully!</p>";
        } else {
            $message_status = "<p class='text-rose-500 font-medium mb-4'>Error saving message.</p>";
        }
        $stmt->close();
    } else {
        $message_status = "<p class='text-rose-500 font-medium mb-4'>Please fill all fields.</p>";
    }
}
// Finally construct the remaining layout
require_once 'includes/header.php';
?>

  <main class="flex-1">
    <div class="max-w-[1200px] mx-auto px-6 pt-12 pb-6">
      <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400">
          <a class="hover:text-primary" href="index.php">Home</a>
          <span class="material-symbols-outlined text-xs">chevron_right</span>
          <span class="text-slate-900 dark:text-white">Contact Us</span>
        </div>
        <h1 class="text-slate-900 dark:text-white text-4xl md:text-5xl font-black leading-tight tracking-tight mt-4">Get in Touch</h1>
        <p class="text-slate-600 dark:text-slate-400 text-lg max-w-2xl">Have a question about an order or looking for a specific title? We're here to help you.</p>
      </div>
    </div>

    <div class="max-w-[1200px] mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-12 pb-24">
      <div class="lg:col-span-2 space-y-12">
        <div class="w-full aspect-video bg-slate-200 dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 relative">
          <iframe
            class="w-full h-full"
            src="https://www.google.com/maps?q=Rajarata%20University%20of%20Sri%20Lanka&output=embed"
            loading="lazy">
          </iframe>
          <div class="absolute bottom-6 left-6 bg-white dark:bg-slate-900 p-4 rounded-lg shadow-xl border border-slate-100 dark:border-slate-800">
            <h4 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">location_on</span>
              Visit our Mihintale Store
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Rajarata University of Sri Lanka, Mihintale, 50300</p>
          </div>
        </div>

        <section class="bg-white dark:bg-slate-900 p-8 md:p-10 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-8">Send us a Message</h2>

          <?php echo $message_status; ?>

          <form action="contact.php" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Full Name</label>
                <input id="fullName" name="fullName" required class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary transition-all" placeholder="Aruni Perera" type="text" />
              </div>
              <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Email Address</label>
                <input id="email" name="email" required class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary transition-all" placeholder="aruni@example.lk" type="email" />
              </div>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Subject</label>
              <select id="subject" name="subject" class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary transition-all">
                <option>Order Status Inquiry</option>
                <option>Request a Specific Title</option>
                <option>Returns & Exchanges</option>
                <option>Store Events & Book Signings</option>
                <option>Other</option>
              </select>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Your Message</label>
              <textarea id="message" name="message" required class="w-full px-4 py-3 rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 focus:border-primary focus:ring-primary transition-all" placeholder="Tell us how we can help..." rows="5"></textarea>
            </div>

            <button class="w-full md:w-auto px-10 py-4 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition-all flex items-center justify-center gap-2" type="submit">
              <span>Send Message</span>
              <span class="material-symbols-outlined text-lg">send</span>
            </button>
          </form>
        </section>
      </div>

      <aside class="space-y-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
          <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-4">
            <span class="material-symbols-outlined text-2xl">storefront</span>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Visit Our Store</h3>
          <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
            Rajarata University Of Sri Lanka<br />
            Mihinthale<br />
            50300
          </p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
          <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-4">
            <span class="material-symbols-outlined text-2xl">call</span>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Call Us</h3>
          <p class="text-slate-600 dark:text-slate-400 text-sm">General: <span class="font-semibold text-slate-900 dark:text-white">+94 11 234 5678</span></p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
          <div class="size-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-4">
            <span class="material-symbols-outlined text-2xl">mail</span>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Email Support</h3>
          <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">Our team typically responds within 24 business hours.</p>
          <a class="text-primary font-bold hover:underline" href="mailto:support@lakshibookshop.lk">support@lakshibookshop.lk</a>
        </div>

        <div class="bg-primary p-8 rounded-2xl text-white shadow-lg">
          <h3 class="text-xl font-bold mb-4">Store Hours</h3>
          <ul class="space-y-3 opacity-90 text-sm">
            <li class="flex justify-between border-b border-white/20 pb-2">
              <span>Monday - Friday</span>
              <span>9am - 8pm</span>
            </li>
            <li class="flex justify-between border-b border-white/20 pb-2">
              <span>Saturday</span>
              <span>10am - 6pm</span>
            </li>
            <li class="flex justify-between">
              <span>Sunday</span>
              <span>11am - 5pm</span>
            </li>
          </ul>
        </div>
      </aside>
    </div>
  </main>

<?php require_once 'includes/footer.php'; ?>