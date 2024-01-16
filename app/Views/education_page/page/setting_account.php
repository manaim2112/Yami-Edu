<?= $this->include('education_page/header', ['title' => "selamat Datang", "page_active", "setting_account"]) ?>

<?= $this->extend("education_page/layout") ?>
<?= $this->section("slug") ?>

<h3 class="text-3xl">Pengaturan Akun</h3>

<form method="post" action="<?= url_to('edu.setting_account.post', auth('edu')->username) ?>?m=default">
    <?= csrf_field() ?>
    <div class="mt-3">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Panggilan</label>
        <input type="text" value="<?= 
            $account->alias ?>" id="name" name="alias" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required>
    </div>
    <div class="mt-3">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-Mail</label>
        <input type="text" value="<?= 
            $account->email ?>" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="John" required>
    </div>
    <div class="mt-3">
        <label for="password-confirm" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password Confirmation to update</label>
        <input type="text" value="" name="password" id="password-confirm" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Type your password to update information" required>
    </div>
    <div class="mt-3">
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update Account</button>
    </div>
</form>

<div class="mt-4">
    <h3 class="text-3xl">Detail Biodata</h3>
<form action="<?= url_to('edu.setting_account.post', service('uri')->getSegment(3)) ?>?m=bio" method="post">
    <div class="grid grid-cols-2 gap-3">
        <div>
            <?= csrf_field() ?>
            <div class="mt-3">
                <label for="fullname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Lengkap</label>
                <input type="text" value="<?= $account_biodata->fullname ?? "" ?>" id="fullname" name="fullname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Toni Sudijo" required>
            </div>
            <div class="mt-3">
                <label for="nuptk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NUPTK/PTK</label>
                <input type="number" value="<?= 
                    $account_biodata->nuptk ?? "" ?>" id="nuptk" name="nuptk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="2390234234242" required>
            </div>
            <div class="mt-3">
                <label for="tmt-date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Terhitung Mulai Tanggal (TMT)</label>
                <input type="date" value="<?= 
                    isset($account_biodata->tmt_date) ? date('Y-m-d', strtotime($account_biodata->tmt_date)) : "" ?>" id="tmt-date" name="tmt-date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="02/02/2023" required>
            </div>
        </div>
        <div>
            <div class="mt-3">
                <label for="tmt-sk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SK Awal</label>
                <input type="text" value="<?= 
                    $account_biodata->tmt_sk ?? "" ?>" id="tmt-sk" name="tmt-sk" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="012/12/kemendikbud/2023" required>
            </div>
            <div class="mt-3">
                <label for="ijazah-id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomer Ijazah</label>
                <input type="text" value="<?= 
                    $account_biodata->ijazah_id ?? "" ?>" id="ijazah-id" name="ijazah-id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="D903-2323" required>
            </div>
            <div class="mt-3">
                <label for="ijazah-date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Ijazah</label>
                <input type="date" value="<?= 
                    isset($account_biodata->ijazah_date) ? date('Y-m-d', strtotime($account_biodata->ijazah_date)) : "" ?>" id="ijazah-date" name="ijazah-date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="12-12-1212" required>
            </div>

        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Update Biodata</button>
    </div>
</form>
</div>
<?= $this->endSection() ?>

<?= $this->include('education_page/footer'); ?>
