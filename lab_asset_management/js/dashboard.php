<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

$all_options = [
    ['label' => 'Asset Management',           'href' => 'asset_management.php', 'icon' => 'archive-box',        'desc' => 'Register and browse lab equipment, chemicals, and supplies.', 'roles' => ['admin', 'researcher']],
    ['label' => 'Maintenance and Calibration', 'href' => 'maintenance.php',      'icon' => 'wrench-screwdriver', 'desc' => 'Log calibration and maintenance records for assets.',          'roles' => ['admin', 'technician']],
    ['label' => 'Procurement Management',      'href' => 'procurement.php',      'icon' => 'shopping-cart',      'desc' => 'Submit and review asset procurement requests.',                'roles' => ['admin', 'researcher']],
    ['label' => 'Asset Disposal',              'href' => 'disposal.php',         'icon' => 'trash',              'desc' => 'Request and track disposal of retired assets.',                'roles' => ['admin']],
    ['label' => 'Reporting and Analytics',     'href' => 'report.php',           'icon' => 'chart-bar',          'desc' => 'View asset utilization and compliance reports.',              'roles' => ['admin', 'researcher', 'technician', 'student']],
];
$options = array_values(array_filter($all_options, fn($o) => in_array($role, $o['roles'], true)));

$page_title = 'Dashboard';
include 'includes/head.php';
?>

<main class="mx-auto max-w-6xl px-6 py-10">
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p class="mt-1 text-sm text-gray-500">
            Signed in as <span class="font-medium capitalize text-gray-700"><?php echo htmlspecialchars($role); ?></span>.
            Choose where you'd like to go.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($options as $opt): ?>
            <a href="<?php echo $opt['href']; ?>"
               class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary"><?php echo icon($opt['icon'], 'h-6 w-6'); ?></div>
                <h3 class="font-semibold text-gray-900 group-hover:text-primary"><?php echo htmlspecialchars($opt['label']); ?></h3>
                <p class="mt-1 text-sm text-gray-500"><?php echo htmlspecialchars($opt['desc']); ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/foot.php'; ?>
