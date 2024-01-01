<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="/resources/flowbite.min.css" {csp-style-nonce}>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css"  rel="stylesheet" /> -->
    <style type="text/css">
        .linier-animate {
            background: linear-gradient(270deg, #f0fd32, #0f61e3);
            background-size: 400% 400%;

            -webkit-animation: AnimationName 10s ease infinite;
            -moz-animation: AnimationName 10s ease infinite;
            animation: AnimationName 10s ease infinite;
        }
        @-webkit-keyframes AnimationName {
            0%{background-position:0% 50%}
            50%{background-position:100% 50%}
            100%{background-position:0% 50%}
        }
        @-moz-keyframes AnimationName {
            0%{background-position:0% 50%}
            50%{background-position:100% 50%}
            100%{background-position:0% 50%}
        }
        @keyframes AnimationName {
            0%{background-position:0% 50%}
            50%{background-position:100% 50%}
            100%{background-position:0% 50%}
        }
    </style>
</head>
<body class="antialiased bg-white dark:bg-gray-900">
    <?=  $this->include('component/navbar') ?>

    <?= $this->renderSection('body') ?>

    <script type="module" src="/resources/flowbite.min.js"></script>
    <script src="/resources/main.js"></script>
    
</body>
</html>