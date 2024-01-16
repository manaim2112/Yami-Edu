<?= $this->extend("admin/layout") ?>

<?php $this->section('content') ?>
<?php
$db = db_connect();
$edu = $db->table("edu")->get()->getResultObject();
$role = ["superuser", "kepala" . cache('setting')->kementrian == 'kemenag' ? 'Madrasah' : "Sekolah", "Operator", "Wakur", "Guru", "Tamu"];

?>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    1
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama
                </th>
                <th scope="col" class="px-6 py-3">
                    Nama Lengkap
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
                <th scope="col" class="px-6 py-3">
                    Photo
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
                    <?= $role[$v->role] ?>
                </td>
                <td class="px-6 py-4">
                    <a href="<?= url_to("admin", service('request')->admin->name) ?>/auto_login?id=<?= $v->id ?>" class="font-medium text-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:text-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 hover:underline">Login as</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $this->endSection() ?>

