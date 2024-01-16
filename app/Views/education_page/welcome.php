<?= $this->include('education_page/header', ['title' => "selamat Datang", "page_active", "home"]) ?>


<?= $this->extend("education_page/layout") ?>
<?= $this->section("slug") ?>
    <div class="grid grid-cols-4 gap-4 mb-4">
         <div class="h-24 rounded bg-gradient-to-tr shadow-lg from-blue-300 to-blue-500 dark:bg-gray-800 p-5 relative">
           <h1 class="text-5xl font-bold text-white">CBT</h1>
           <div class="absolute bottom-0 right-0">
            <h1 class="text-7xl text-blue-400 drop-shadow-md"><?= $count->cbt ?></h1>
           </div>
         </div>
         <div class="h-24 rounded bg-gradient-to-tr shadow-lg from-yellow-300 to-yellow-500 dark:bg-gray-800 p-5 relative">
           <h1 class="text-5xl font-bold text-white">kegiatan</h1>
           <div class="absolute bottom-0 right-0">
            <h1 class="text-7xl text-yellow-400 drop-shadow-md"><?= $count->event ?></h1>
           </div>
         </div>
         <div class="h-24 rounded bg-gradient-to-tr shadow-lg from-red-300 to-red-500 dark:bg-gray-800 p-5 relative">
           <h1 class="text-5xl font-bold text-white">Murid</h1>
           <div class="absolute bottom-0 right-0">
            <h1 class="text-7xl text-red-400 drop-shadow-md"><?= $count->user ?></h1>
           </div>
         </div>
         <div class="h-24 rounded bg-gradient-to-tr shadow-lg from-red-300 to-red-500 dark:bg-gray-800 p-5 relative">
           <h1 class="text-5xl font-bold text-white">Tendik</h1>
           <div class="absolute bottom-0 right-0">
            <h1 class="text-7xl text-red-400 drop-shadow-md"><?= $count->edu ?></h1>
           </div>
         </div>

      </div>
<?= $this->endSection() ?>


<?= $this->include('education_page/footer'); ?>

