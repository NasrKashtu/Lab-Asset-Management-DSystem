<?php
$__nav_items = [
    ['label' => 'Dashboard',   'href' => 'dashboard.php',        'roles' => ['admin', 'researcher', 'technician', 'student']],
    ['label' => 'Assets',      'href' => 'asset_management.php', 'roles' => ['admin', 'researcher']],
    ['label' => 'Maintenance', 'href' => 'maintenance.php',      'roles' => ['admin', 'technician']],
    ['label' => 'Procurement', 'href' => 'procurement.php',      'roles' => ['admin', 'researcher']],
    ['label' => 'Disposal',    'href' => 'disposal.php',         'roles' => ['admin']],
    ['label' => 'Reports',     'href' => 'report.php',           'roles' => ['admin', 'researcher', 'technician', 'student']],
];
$__current = basename($_SERVER['PHP_SELF']);
$__role = $_SESSION['role'] ?? '';
?>
<nav class="sticky top-0 z-10 border-b border-gray-200 bg-white/80 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-3">
        <a href="dashboard.php" class="flex shrink-0 items-center gap-2 whitespace-nowrap font-semibold text-gray-900">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-primary text-white shadow-sm shadow-primary/40"><?php echo icon('beaker', 'h-4.5 w-4.5'); ?></span>
            Lab Assets
        </a>
        <div class="hidden items-center gap-0.5 md:flex">
            <?php foreach ($__nav_items as $item): if (!in_array($__role, $item['roles'], true)) continue; ?>
                <a href="<?php echo $item['href']; ?>"
                   class="whitespace-nowrap rounded-full px-3.5 py-2 text-sm font-medium transition <?php echo $__current === $item['href'] ? 'bg-primary/10 text-primary' : 'text-gray-600 hover:bg-gray-100'; ?>">
                    <?php echo htmlspecialchars($item['label']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="flex shrink-0 items-center gap-3">
            <span class="hidden items-center gap-1.5 whitespace-nowrap text-sm text-gray-500 lg:flex">
                <?php echo icon('user-circle', 'h-5 w-5 shrink-0 text-gray-400'); ?>
                <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
                &middot; <span class="capitalize"><?php echo htmlspecialchars($__role); ?></span>
            </span>
            <a href="logout.php" class="flex items-center gap-1.5 whitespace-nowrap rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                <?php echo icon('logout', 'h-4 w-4'); ?>
                Logout
            </a>
        </div>
    </div>
</nav>
