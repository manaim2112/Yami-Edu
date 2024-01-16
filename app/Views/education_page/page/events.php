<?= $this->include('education_page/header', ['title' => "selamat Datang", "page_active", "setting_account"]) ?>

<?= $this->extend("education_page/layout") ?>
<?= $this->section("slug") ?>



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
                <button data-modal-target="event-create" data-modal-toggle="event-create" class="text-white bg-yellow-800 hover:bg-yellow-900 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-yellow-300 dark:text-gray-800 dark:hover:bg-yellow-400 dark:focus:ring-yellow-800">
                <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                    <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                </svg>
                Buat sekarang
            </button>
            </div>
        </div>

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
                    <?= $v->start_date ?>
                </th>
                <td class="px-6 py-4">
                    <?= $v->name ?>
                </td>
                <td class="px-6 py-4">
                    <?php foreach($edu as $e) {
                        if($e->id == $v->edu_id) {
                            $name = $e->alias;
                            break;
                        }
                    } ?>
                    <?= $name ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v->jenis ?>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-2 rounded-lg <?= ($v->status == 'WAITING') ? 'bg-slate-300 text-slate-800' : (($v->status == 'PROCCESS') ? 'bg-green-300 text-green-800' : (($v->status == 'SUCCESS') ? 'bg-blue-300 text-blue-800' : 'bg-red-300 text-red-800')) ?>">
                    <?= $v->status ?>
                </span>
                </td>
                <td class="px-6 py-4">
                    <a onclick="edit(<?= $v->id ?>)" data-modal-target="event-edit" data-modal-toggle="event-edit" href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Create Kegiatan -->
<?= $this->include("education_page/pop_up/event_create"); ?>
<?= $this->include("education_page/pop_up/event_edit"); ?>
<?= $this->section("script") ?>
<script type="text/javascript">
    const events = <?= json_encode($data) ?>;
    function edit(id) {
        const i = events.findIndex(Obj => Obj.id == id);
        if(i < 0) return;
        const event = events[i];
        document.getElementsByName("id")[0].value = event.id;
        document.getElementsByName("tanggal_edit")[0].value = event.start_date.split(" ")[0];
        document.getElementsByName("tanggal_end_edit")[0].value = event.end_date.split(" ")[0];
        const type = document.getElementsByName("type_edit");
        type.forEach((v,k) => {
            if(v.value == event.jenis) {
                v.checked = true;
            }
        })
        document.getElementsByName("name_edit")[0].value = event.name;
        const ketua = document.getElementsByName("ketua_edit")[0];
        for(let e = 0; e < ketua.options.length; e++) {
            if(ketua.options[e].value == event.edu_id) {
                ketua.options[e].selected = true;
                break;
            }
        }

        const status = document.getElementsByName("status_edit");
        status.forEach((v,k) => {
            if(v.value == event.status) {
                v.checked = true;
            }
        })
    }
</script>
<?php $this->endSection(); ?>
<?= $this->endSection() ?>
<?= $this->include("education_page/footer") ?>