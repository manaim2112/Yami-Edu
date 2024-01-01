<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Instalasi Yami Edu</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= csrf_meta() ?>
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <!-- <link rel="stylesheet" href="/resources/style.css" {csp-style-nonce}> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode : 'class',
        }
    </script>
    <link rel="stylesheet" href="/resources/flowbite.min.css" {csp-style-nonce}>
</head>
<body class="antialiased bg-gray-800">
    <div class="flex flex-col h-screen align-middle justify-center items-center shadow">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-4xl font-bold">
                Selamat Datang di Instalasi <span class="bg-clip-text text-transparent bg-gradient-to-tr from-blue-600 to-yellow-300"> Yami Edu </span>
            </h1>
            <p>
                Yami Edu adalah Layanan Edukasi Untuk sekolah naungan Kemendikbud maupun kemenag yang berbasis digital.
            </p>

            
            <ul class="space-y-1 mt-3 text-left text-gray-500 dark:text-gray-400">
                <li class="flex items-center space-x-3 rtl:space-x-reverse" id="proses">
                    <span class="text-green-500 animate-spin dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                    |
                    </span>
                    <span>Installing ...</span>
                </li>
                <li id="setup" class="hidden flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="text-green-500 animate-spin dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                    |
                    </span>
                    <span>Setting Up ...</span>
                </li>
            </ul>
            <div class="text-red-500 mt-5 text-sm">
                *Di himbau tidak meninggalkan halaman ini sebelum proses install selesai
            </div>
        </div>
    </div>

    <script type="module" src="/resources/flowbite.min.js" {csp-script-nonce}></script>
    <script src="/resources/main.js" {csp-script-nonce}></script>
    <script>
        document.addEventListener('DOMContentLoaded',async function() {
            const proccess = await fetch("<?= url_to('install.index', 'install') ?>", {
                method: 'POST',
                headers : {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            const response = await proccess.json();
            if(response.status === 'success') {
                document.getElementById('proses').innerHTML = `
                    <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                    </svg>
                    <span>Berhasil Install</span>
                `;
                document.getElementById('setup').classList.remove('hidden');
                const setup = await fetch("<?= url_to("install.index", "setup") ?>", {
                    "method" : "POST",
                    "headers" : {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                const responseSetup = await setup.json();
                if(responseSetup.status === "success") {
                    document.getElementById('setup').innerHTML = `
                        <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                        </svg>
                        <span>Berhasil Install</span>
                    `;
                } else {
                    document.getElementById('proses').innerHTML = `
                        <span class="text-red-500 dark:text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            GAGAL
                        </span>
                        <span>Terjadi Kegagalan saat Setting Up aplikasi</span>
                    `;
                }
            } else {
                document.getElementById('proses').innerHTML = `
                    <span class="text-red-500 dark:text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                        GAGAL
                    </span>
                    <span>Terjadi Kegagalan saat install aplikasi</span>
                `;
            }
        })
    </script>
</body>
</html>