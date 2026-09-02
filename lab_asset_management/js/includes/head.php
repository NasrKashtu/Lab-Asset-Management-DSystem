<?php if (!isset($page_title)) { $page_title = 'Lab Asset Management'; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?> &middot; Lab Asset Management</title>
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%23006FEE'/%3E%3Cpath fill='none' stroke='white' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round' d='M9.75 4.1v4.7a1.9 1.9 0 0 1-.55 1.34L5.7 13.9m4.05-9.8a20 20 0 0 1 3.6 0M13.75 4.1v4.7c0 .5.2.98.55 1.34l3.5 3.76M13.75 4.1a20 20 0 0 1 .6.07M17.8 13.9l1.1 1.12c1 1 .5 2.7-.85 2.93a39 39 0 0 1-13.1 0c-1.35-.23-1.85-1.93-.85-2.93l1.1-1.12'/%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
        colors: {
          primary: { DEFAULT: '#006FEE', 50: '#e6f1ff', 100: '#cce3ff', 500: '#006FEE', 600: '#005bc4', 700: '#004a9f' },
        },
      },
    },
  };
</script>
<?php
include __DIR__ . '/icons.php';
include __DIR__ . '/ui.php';
?>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-50/60 via-gray-50 to-gray-50 font-sans text-gray-900 antialiased">
<?php if (isset($_SESSION['username'])) { include __DIR__ . '/nav.php'; } ?>
