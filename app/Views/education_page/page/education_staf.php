<?= $this->include('education_page/header', ['title' => "selamat Datang", "page_active", "setting_account"]) ?>

<?= $this->extend("education_page/layout") ?>
<?= $this->section("slug") ?>
<?php
/*
* @string 3.1 menyatakan waka kurikulum
* @string 3.2 menyatakan waka bidang kesiswaan
* @string 3.3 menyatakan waka bidang sarana dan prasarana
* @string 3.4 menyatakan waka bidang kehumasan
* @string 4.1 menyatakan Koordinator Tenaga Umum
* @string 4.2 menyatakan koordinator Bimbingan Konseling
* @string 4.3 menyatakan koordinator Laboratorium
* @string 4.5 menyatakan koordinator Perpustakaan
* @string 4.6 menyatakan koordinator IT
* @string 5.0 menyatakan tamu 
* @string 6.0 menyatakan perndaftar baru tidak terdeteksi
*/
$staf_category = [
    (object) [ "role" => 2.0, "name" => "Kepala Sekolah" ],
    (object) [ "role" => 3.1, "name" => "Waka Kurikulum" ],
    (object) [ "role" => 3.2, "name" => "Waka Bidang Kesiswaan"],
    (object) [ "role" => 3.3, "name" => "Waka Bidang Sarana dan Prasarana"],
    (object) [ "role" => 4.1, "name" => "Koordinator Tenaga Umum"],
    (object) [ "role" => 4.2, "name" => "Koordinator Bimbingan Konseling"],
    (object) [ "role" => 4.3, "name" => "Koordinator Laboratorium"],
    (object) [ "role" => 4.5, "name" => "Koordinator Perpustakaan"],
    (object) [ "role" => 4.6, "name" => "Koordinator IT"],
    (object) [ "role" => 5.0, "name" => "tamu"],
    (object) [ "role" => 6.0, "name" => "Pendaftar Baru"],
];

?>
 <button data-modal-target="bottom-right-modal" data-modal-toggle="bottom-right-modal" class="block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
    Tambah Pegawai
</button>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    No
                </th>
                <th scope="col" class="px-6 py-3">
                    ID Pegawai
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama Lengkap
                </th>
                <th scope="col" class="px-6 py-3">
                    email
                </th>
                <th scope="col" class="px-6 py-3">
                    Jabatan
                </th>
                <th scope="col" class="px-6 py-3">
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($edu as $key => $v) : ?>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $key+1 ?>
                </th>
                <td class="px-6 py-4">
                    <?= $v->username ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->alias ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->email  ?>
                </td>
                <td class="px-6 py-4">
                    <?php $r = explode("|", $v->role);
                        foreach ($r as $t) {
                            echo "<div class='m-2 inline-block rounded-lg text-black bg-blue-300 py-2 px-3'> $role[$t] </div>";
                        }

                    ?>

                </td>
                <td class="px-6 py-4">
                    <a href="<?= url_to("edu.educational_staf", auth("edu")->username) ?>/auto_login?id=<?= $v->id ?>" class="font-medium text-<?= setting()->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:text-<?= setting()->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 hover:underline">Login as</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- Modal Create Pegawai -->
<!-- Bottom Right Modal -->
<div id="bottom-right-modal" data-modal-placement="bottom-right" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    Tambahkan Pegawai
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="bottom-right-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="<?= route_to('edu.educational_staf.create.post', auth('edu')->username) ?>" method="post">
            <div class="p-4 md:p-5 space-y-4">
                <div class="mb-3">
                    <label for="nuptk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NPK/NUPTK/pEgId</label>
                    <input type="text" id="nuptk" name="nuptk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="2121432353423" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
                    <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Johny Plate" required>
                </div>
                <div class="mb-3">
                    <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Johny Plate" required>
                </div>
                <div class="mb-3">    
                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-white">Jabatan apa saja yang diterima :</h3>
                    <ul class="grid w-full gap-4 md:grid-cols-3">
                        <?php foreach ($staf_category as $key => $category): ?>
                        <li>
                            <input type="checkbox" id="options-<?= $key ?>" name="jabatan[]" value="<?= $category->role ?>" class="hidden peer">
                            <label for="options-<?= $key ?>" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">                           
                                <div class="block">
                                    
                                    <div class="w-full text-sm font-semibold"><?= $category->name ?></div>
                                    <!-- <div class="w-full text-sm">A JavaScript library for building user interfaces.</div> -->
                                </div>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Saya terima Pegawai Baru</button>
                <button data-modal-hide="bottom-right-modal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
            </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->include("education_page/footer") ?>

