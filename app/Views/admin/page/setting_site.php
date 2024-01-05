<?php
 if(!in_array($auth->role, [1,2,3])) return redirect()->to(url_to("admin", service('uri')->getSegment(3)));
?>

<?= $this->extend('admin/layout', ['title' => "Pengaturan Akun"]); ?>
<?= $this->section('title') ?> 
    Pengaturan Website 
<?= $this->endSection() ?>

<?php
$db = db_connect();
$edu = $db->table("sesi")->select()->get()->getResultObject();
?>

<?= $this->section("content") ?>
<h3 class="text-3xl">Pengaturan Website</h3>
<?= validation_list_errors() ?>
<form method="post" action="<?= url_to('admin', service('uri')->getSegment(3)) ?>/setting_site">
    <?= csrf_field() ?>
    <div class="mt-3 grid grid-cols-2 gap-3">
        <div class="flex items-center ps-4 border  rounded <?= $setting->kementrian == 'kemendikbud' ? 'bg-blue-200 dark:bg-blue-900 border-blue-400 dark:border-blue-800' : 'dark:border-gray-700 border-gray-200' ?>  py-2">
            <input id="bordered-radio-1" type="radio" name="kementrian" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" <?= $setting->kementrian == 'kemendikbud' ? 'checked' : '' ?> value="kemendikbud">
            <div class="ms-2 text-sm">
                <label for="helper-radio" class="font-medium text-gray-900 dark:text-gray-300">Kemendikbud</label>
                <p id="helper-radio-text" class="text-xs font-normal text-gray-500 dark:text-gray-300">Kementrian Pendidikan dan Kebudayaan</p>
            </div>
        </div>
        <div class="flex items-center ps-4 border rounded dark:border-gray-700 py-2 <?= $setting->kementrian == 'kemenag' ? 'bg-green-200 dark:bg-green-900 border-green-400 dark:border-green-800' : 'dark:border-gray-700 border-gray-200' ?>">
            <input id="bordered-radio-2" type="radio" value="kemenag" name="kementrian" class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" <?= $setting->kementrian == 'kemenag' ? 'checked' : '' ?>>
            <div class="ms-2 text-sm">
                <label for="helper-radio" class="font-medium text-gray-900 dark:text-gray-300">Kemenag</label>
                <p id="helper-radio-text" class="text-xs font-normal text-gray-500 dark:text-gray-300">Kementrian Agama</p>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <label for="brand_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Website</label>
        <input type="text" value="<?= 
            $setting->brand_name ?>" id="brand_name" name="brand_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required>
    </div>
    <div class="mt-3">
        <label for="brand_logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Logo Website</label>
        <input type="text" value="<?= $setting->brand_logo ?>" name="brand_logo" id="brand_logo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="https://example.com/brand_logo.png">
    </div>
    <div class="mt-3">
        <label for="sesi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun Pelajaran</label>
        <select id="countries" name="sesi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <?php foreach ($edu as $sesi) : ?>
                <?php if ($setting->sesi == $sesi->name) : ?>
                    <option value="<?= $setting->sesi ?>" selected><?= $setting->sesi ?></option>
                <?php else : ?>
                    <option value="<?= $sesi->name ?>"><?= $sesi->name ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

    </div>
    <div class="mt-3">

        <label for="sesi_sub" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Semester</label>
        <select id="countries" name="sesi_sub" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="1" <?= $setting->sesi_sub == '1' ? 'selected' : '' ?>>Ganjil</option>
            <option value="2" <?= $setting->sesi_sub == '1' ? 'selected' : '' ?>>Genap</option>
        </select>
    </div>
    <div class="mt-3">
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update Website</button>
    </div>
</form>
<?= $this->endSection() ?>