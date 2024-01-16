<?= $this->include('education_page/header', ['title' => "selamat Datang", "page_active", "setting_account"]) ?>

<?= $this->extend("education_page/layout") ?>
<?= $this->section("slug") ?>



<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-3">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    No
                </th>
                <th scope="col" class="px-6 py-3">
                    Kode
                </th>
                <th scope="col" class="px-6 py-3">
                    Kelas
                </th>
                <th scope="col" class="px-6 py-3">
                </th>
            </tr>
        </thead>
        <tbody>
            <form action="<?= route_to('edu.learners_user.kelas.create', auth('edu')->username) ?>" method="post">
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= sizeof($data)+1 ?>
                </th>
                <td class="px-6 py-4">
                    <input type="text" name="kode" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="UNIK KODE" required>
                </td>
                <td class="px-6 py-4">
                    <input type="text" name="name" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Nama kelas" required>
                </td>
                <td class="px-6 py-4">
                    <button type="submit" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">+ TAMBAH</button>
                </td>
            </tr>
            </form>
            <?php foreach($data as $key => $v) : ?>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $key+1 ?>
                </th>
                <td class="px-6 py-4">
                    <?= $v->kode ?? "T" ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->name ?>
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

