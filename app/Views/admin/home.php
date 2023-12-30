<?= $this->extend('admin/layout', ['title' => "Welcome"]) ?>

<?= $this->section("content") ?>

    <div class="grid grid-cols-3 gap-4 mb-4">
         <div class="h-24 rounded bg-gradient-to-tr shadow-lg from-blue-300 to-blue-500 dark:bg-gray-800 p-5 relative">
           <h1 class="text-5xl font-bold text-white">CBT</h1>
           <div class="absolute bottom-0 right-0">
            <h1 class="text-7xl text-blue-400 drop-shadow-md">3</h1>
           </div>
         </div>
         <div class="h-24 rounded bg-gradient-to-tr shadow-lg from-yellow-300 to-yellow-500 dark:bg-gray-800 p-5 relative">
           <h1 class="text-5xl font-bold text-white">Assesment</h1>
           <div class="absolute bottom-0 right-0">
            <h1 class="text-7xl text-yellow-400 drop-shadow-md">3</h1>
           </div>
         </div>
         <div class="h-24 rounded bg-gradient-to-tr shadow-lg from-red-300 to-red-500 dark:bg-gray-800 p-5 relative">
           <h1 class="text-5xl font-bold text-white">History</h1>
           <div class="absolute bottom-0 right-0">
            <h1 class="text-7xl text-red-400 drop-shadow-md">3</h1>
           </div>
         </div>

      </div>

<?= $this->endSection() ?>