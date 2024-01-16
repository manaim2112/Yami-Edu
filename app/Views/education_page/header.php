<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? "Selamat datang di admin") ?></title>
    <?= csrf_meta() ?>
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <!-- <link rel="stylesheet" href="/resources/style.css" {csp-style-nonce}> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode : 'class',
            theme: {
                extend: {
                colors: {
                    clifford: '#da373d',
                }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css"  rel="stylesheet" />
</head>
<body class="antialiased bg-white dark:bg-gray-800">

<?= $this->include("education_page/navbar") ?>

<?= $this->include("education_page/sidebar", ['page_active' => $page_active ?? "home"]); ?>