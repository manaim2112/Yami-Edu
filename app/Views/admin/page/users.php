<?= $this->extend('admin/layout', ['title' => "Pengguna user"]) ?>

<?= $this->section('content') ?>

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
            <?php foreach($data as $key => $v) : ?>
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <?= $k+1 ?>
                </th>
                <td class="px-6 py-4">
                    <?= $v->name ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->fullname ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->status ?>
                </td>
                <td class="px-6 py-4">
                </td>
                <td class="px-6 py-4">
                    <a href="<?= url_to("admin", service('request')->admin->name) ?>/cbt/id/<?= $v->id ?>" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Lihat</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>