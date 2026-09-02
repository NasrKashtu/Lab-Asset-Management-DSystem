<?php
include('includes/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT id, username, role FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}

$page_title = 'Login';
include 'includes/head.php';
?>

<div class="flex min-h-screen items-center justify-center px-4">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/30"><?php echo icon('beaker', 'h-7 w-7'); ?></div>
            <h1 class="text-2xl font-semibold text-gray-900">Lab Asset Management</h1>
            <p class="mt-1 text-sm text-gray-500">Sign in to continue</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm">
            <form method="post" action="" class="space-y-5">
                <div>
                    <label for="username" class="mb-1.5 block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required
                           class="w-full rounded-xl border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/30">
                </div>
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full rounded-xl border border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-primary focus:bg-white focus:ring-2 focus:ring-primary/30">
                </div>
                <?php if (isset($error)) { ?>
                    <p class="rounded-xl bg-red-50 px-3.5 py-2.5 text-sm text-red-600"><?php echo htmlspecialchars($error); ?></p>
                <?php } ?>
                <button type="submit" class="w-full rounded-full bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600 active:scale-[0.98]">Sign In</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/foot.php'; ?>
