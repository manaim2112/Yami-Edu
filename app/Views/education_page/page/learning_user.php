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
<div class="flex gap-2">
    <button data-modal-target="tambah" data-modal-toggle="tambah" class="block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
       + Tambah
   </button>
    <button data-modal-target="upload" data-modal-toggle="upload" class="block w-full md:w-auto text-white bg-lime-700 hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800" type="button">
       Upload
   </button>
    <a href="<?= route_to('edu.learners_user.kelas', auth('edu')->username) ?>" class="block w-full md:w-auto text-white bg-lime-700 hover:bg-lime-800 focus:ring-4 focus:outline-none focus:ring-lime-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-lime-600 dark:hover:bg-lime-700 dark:focus:ring-lime-800" type="button">
       Atur Kelas
    </a>
</div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    No
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama
                </th>
                <th scope="col" class="px-6 py-3">
                    Kelas
                </th>
                <th scope="col" class="px-6 py-3">
                    Jabatan
                </th>
                <th scope="col" class="px-6 py-3">
                    Foto
                </th>
                <th scope="col" class="px-6 py-3">
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $key => $v) : ?>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $key+1 ?>
                </th>
                <td class="px-6 py-4">
                    <?= $v->name ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->kelas ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->jabat ?>
                </td>
                <td class="px-6 py-4">
                    <img src="/resources/images/photo/<?= $v->photo_id ?>" width="100" onerror="image(this)"/>
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
<?= $this->include("education_page/pop_up/learners_user_upload"); ?>

<?= $this->endSection() ?>
<?= $this->include("education_page/footer") ?>

