<?= $this->extend('admin/layout', ['title' => "kegiatan anda selama sesi ini"]); ?>

<?= $this->section('content') ?>

<?php

use CodeIgniter\HTTP\URI;

 if($subpage === 'create') : ?>
    <?php 
        $db = db_connect();
        $listadmin = $db->table("admin")->select("id, username")->get()->getResult();
        $listsession = $db->table("session")->select()->get()->getResult();
    ?>
    <h2 class="text-3xl">Buat kegiatan</h2>
    <div class="mb-3">
        Pastikan buat kegiatan tidak asal-asal, karena tidak bisa di hapus
    </div>
    <?php if(session()->has("errors")) : ?>
        <div id="alert-additional-content-4" class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300 dark:border-red-800" role="alert">
            <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium">Terjadi Kesalahan</h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
            <?= session()->get("errors") ?>
            </div>
            <div class="flex">
                <a href="<?= url_to('admin', service('request')->admin->name) ?>/events/create" class="text-white bg-red-800 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-red-300 dark:text-gray-800 dark:hover:bg-red-400 dark:focus:ring-red-800">
                <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
                Buat sekarang
                </a>
                <button type="button" class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-300 dark:border-red-300 dark:text-red-300 dark:hover:text-gray-800 dark:focus:ring-yellow-800" data-dismiss-target="#alert-additional-content-4" aria-label="Close">
                Dismiss
                </button>
            </div>
        </div>

    <?php endif; ?>
    <form action="<?= url_to('admin.post', service('uri')->getSegment(3)) ?>/events/create" method="post">
        <?= csrf_field("_token") ?>
    <div class="mb-5">
        <label for="text" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tema Kegiatan</label>
        <input type="text" name="tema" id="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required>
    </div>
    <div class="mb-5">
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bentuk Kegiatan</label>
        <select id="countries" name="jenis" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected>Pilih salah satu ...</option>
            <option value="workshop">Workshop</option>
            <option value="cbt">Computer Based Test</option>
            <option value="manualtest">Ujian Tulis</option>
    </select>
    </div>
    <div class="mb-5">
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sesi Tahun</label>
        <select id="countries" name="sesi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <?php foreach($listsession as $la) : ?>
                <option value="<?= $la->id ?>"><?= $la->name ?></option>
            <?php endforeach; ?>
        </select>
            <a href="<?= url_to("auto.session") ?>" class="text-blue-500 hover:underline hover:text-blue-600">Check update Sesi</a>
    </div>
    <div class="mb-5">
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ketua Pelaksana</label>
        <select id="countries" name="ketua" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <?php foreach($listadmin as $la) : ?>
                <option value="<?= $la->id ?>_<?= $la->username ?>"><?= $la->username ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex items-center mb-4">
        <input id="default-checkbox" name="confirm" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Dengan anda klik ini, anda sudah yakin untuk membuat kegiatan ini, pastikan kegiatan benar benar terjadi</label>
    </div>
    <div class="mb-5">
        <button type="submit" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Terbitkan Kegiatan</button>
    </div>
    </form>

<?php elseif($subpage === 'edit') : ?>
    <?php 
        $db = db_connect();
        $listadmin = $db->table("admin")->select("id, username")->get()->getResult();
        $listsession = $db->table("session")->select()->get()->getResult();
    ?>
    <h2 class="text-3xl">Perbarui kegiatan</h2>
    <div class="mb-3">
        Pastikan buat kegiatan tidak asal-asal, karena tidak bisa di hapus
    </div>
    <?php if(session()->has("errors")) : ?>
        <div id="alert-additional-content-4" class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300 dark:border-red-800" role="alert">
            <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium">Terjadi Kesalahan</h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
            <?= session()->get("errors") ?>
            </div>
            <div class="flex">
                <a href="<?= url_to('admin', service('request')->admin->name) ?>/events/create" class="text-white bg-red-800 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-red-300 dark:text-gray-800 dark:hover:bg-red-400 dark:focus:ring-red-800">
                <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
                Buat sekarang
                </a>
                <button type="button" class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-300 dark:border-red-300 dark:text-red-300 dark:hover:text-gray-800 dark:focus:ring-yellow-800" data-dismiss-target="#alert-additional-content-4" aria-label="Close">
                Dismiss
                </button>
            </div>
        </div>

    <?php endif; ?>
    <form action="<?= url_to('admin.post', service('uri')->getSegment(3)) ?>/events/edit?<?= service('uri')->getQuery(['id']) ?>" method="post">
        <?= csrf_field("_token") ?>
    <div class="mb-5">
        <label for="text" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tema Kegiatan</label>
        <input type="text" name="tema" id="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="contoh : AMBK atau Sumatif Akhir Semester" value="<?= $data['name'] ?>" required>
    </div>
    <div class="mb-5">
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bentuk Kegiatan</label>
        <select id="countries" name="jenis" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?= $data['jenis'] ?>">
            <option value="workshop" <?= ($data['jenis'] === 'workshop') ? 'selected' : '' ?>>Workshop</option>
            <option value="cbt" <?= ($data['jenis'] === 'cbt') ? 'selected' : '' ?>>Computer Based Test</option>
            <option value="manualtest" <?= ($data['jenis'] === 'manualtest') ? 'selected' : '' ?>>Ujian Tulis</option>
    </select>
    </div>
    <div class="mb-5">
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sesi Tahun</label>
        <select id="countries" name="sesi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?= $data['session_id'] ?>">
            <?php foreach($listsession as $la) : ?>
                <option value="<?= $la->id ?>" <?= ($la->id === $data['session_id']) ? 'selected' : '' ?>><?= $la->name ?></option>
            <?php endforeach; ?>
        </select>
            <a href="<?= url_to("auto.session") ?>" class="text-blue-500 hover:underline hover:text-blue-600">Check update Sesi</a>
    </div>
    <div class="mb-5">
        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ketua Pelaksana</label>
        <select id="countries" name="ketua" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?= $data['admin_id'] ?>">
            <?php foreach($listadmin as $la) : ?>
                <option value="<?= $la->id ?>_<?= $la->username ?>" <?= ($la->id === $data['admin_id']) ? 'selected' : '' ?>><?= $la->username ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex items-center mb-4">
        <input id="default-checkbox" name="confirm" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Dengan anda klik ini, anda sudah yakin untuk mengubah kegiatan ini, pastikan kegiatan benar benar terjadi</label>
    </div>
    <div class="mb-5">
        <button type="submit" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Perbarui Kegiatan</button>
    </div>
</form>

<?php elseif($subpage === 'delete') : ?>

<?php else : ?>

<?php if(sizeof($data) === 0) : ?>
    <div id="alert-additional-content-4" class="p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300 dark:border-yellow-800" role="alert">
            <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium">Belum ada kegiatan yang dibuat, silahkan klik buat dan </h3>
            </div>
            <div class="mt-2 mb-4 text-sm">
                Jika anda merasa sudah membuat kegiatan, tapi tidak ada di list, segera hubungi operator
            </div>
            <div class="flex">
                <a href="<?= url_to('admin', service('request')->admin->name) ?>/events/create" class="text-white bg-yellow-800 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
                <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
                Buat sekarang
                </a>
                <button type="button" class="text-yellow-800 bg-transparent border border-yellow-800 hover:bg-yellow-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-yellow-300 dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-gray-800 dark:focus:ring-yellow-800" data-dismiss-target="#alert-additional-content-4" aria-label="Close">
                Dismiss
                </button>
            </div>
        </div>
<?php else : ?>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Tanggal Pelaksanaan
                </th>
                <th scope="col" class="px-6 py-3">
                    Tema kegiatan
                </th>
                <th scope="col" class="px-6 py-3">
                    ketua Pelaksana
                </th>
                <th scope="col" class="px-6 py-3">
                    jenis Kegiatan
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
                <th scope="col" class="px-6 py-3">
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $v) : ?>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $v['created_at'] ?>
                </th>
                <td class="px-6 py-4">
                    <?= $v['name'] ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v['admin_name'] ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v['jenis'] ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v['status'] ?>
                </td>
                <td class="px-6 py-4">
                    <a href="<?= url_to("admin", service('request')->admin->name) ?>/events/edit?id=<?= $v['id'] ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php endif; ?>

<?php endif; ?>


<?= $this->endSection() ?>