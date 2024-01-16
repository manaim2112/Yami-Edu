<?php

$type_kegiatan = [
    (object) [ "name" => "cbt", "alias" => "Computer Based Test"],
    (object) [ "name" => "wsh", "alias" => "Workshop"],
    (object) [ "name" => "pbt", "alias" => "Paper Based Test"],
    (object) [ "name" => "rpt", "alias" => "Rapat"],
    (object) [ "name" => "other", "alias" => "Lainnya"],
]
?>
<div id="event-create" data-modal-placement="bottom-right" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    Tambahkan Kegiatan
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="event-create">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="<?= route_to('edu.events.create.post', auth('edu')->username) ?>" method="post">
            <div class="p-4 md:p-5 space-y-4">
                <div class="mb-3">
                    <h3 class="text-lg">Tanggal pelaksanaan</h3>
                    <div class="grid gap-2 grid-cols-2">
                        <div>
                            <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Awal</label>
                            <input type="date" id="tanggal" name="tanggal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="2121432353423" required>
                        </div>
                        <div>
                            <label for="tanggal_end" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Akhir</label>
                            <input type="date" id="tanggal_end" name="tanggal_end" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="2121432353423" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kegiatan</label>
                    <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Johny Plate" required>
                </div>
                <div class="mb-3">
                <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-white">Jenis Kegiatan :</h3>
                    <ul class="grid w-full gap-4 md:grid-cols-3">
                        <?php foreach ($type_kegiatan as $key => $category): ?>
                        <li>
                            <input type="radio" id="options-<?= $key ?>" name="type" value="<?= $category->name ?>" class="hidden peer">
                            <label for="options-<?= $key ?>" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                                <div class="block">
                                    
                                    <div class="w-full text-sm font-semibold"><?= $category->alias ?></div>
                                    <!-- <div class="w-full text-sm">A JavaScript library for building user interfaces.</div> -->
                                </div>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="mb-3">    
                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ketua Pelaksana</label>
                    <select name="ketua" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <?php foreach($edu as $e) : ?>
                        <option value="<?= $e->id ?>"><?= $e->alias ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Saya terima Pegawai Baru</button>
                <button data-modal-hide="event-create" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
            </div>
            </form>
        </div>
    </div>
</div>