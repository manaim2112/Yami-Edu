<?php
$uri = service('uri');
$kelas = $data['kelas'] ?? [];
$user = $data['user'] ?? [];
$guru = $data['guru'] ?? [];
?>
<?= $this->extend('admin/layout', ['title' => "Pengguna user"]) ?>

<?= $this->section('content') ?>
<?php if($uri->getSegment(5) == 'upload') : ?>
    <div class="flex items-center justify-center w-full">
        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                </svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span></p>
                <p class="text-xs text-gray-500 dark:text-gray-400">XLSX (MAX. 1024Mb)</p>
            </div>
            <input id="dropzone-file" type="file" class="hidden" />
        </label>
    </div> 

<?php elseif($uri->getSegment(5) == 'kelas') : ?>
    <a href="<?= url_to("admin", $uri->getSegment(3)) ?>/users/template_kelas" class="text-black bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-100 hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-200 focus:ring-4 focus:outline-1 border border-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 mb-3 focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-700 dark:focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-800" title="Template Peserta didik">
        <svg class="w-4 h-4 me-3 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12V1m0 0L4 5m4-4 4 4m3 5v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
    </svg>
        TEMPLATE KELAS</a>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 w-40 min-w-40">
                        Kelas
                    </th>
                    <th scope="col" class="px-6 py-3 min-w-40">
                        Wali Kelas
                    </th>
                    <th scope="col" class="px-6 py-3 min-w-40">
                        Ketua kelas
                    </th>
                    <th scope="col" class="px-6 py-3">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <form method="POST" action="<?= url_to("admin", $uri->getSegment(3))?>/users/kelas">
                        <?= csrf_field() ?>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <?= sizeof($kelas)+1 ?>
                        </th>
                        <td class="px-6 py-4">
                            <input type="text" id="first_name" name="kelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="7-G" required>
                        </td>
                        <td class="px-6 py-4">
                            <select id="countries" name="walikelas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <?php foreach ($guru as $val) : ?>
                                    <option value="<?= $val->id ?>"><?= $val->alias ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                           
                        </td>
                        <td class="px-6 py-4">
                            <button type="submit" class="text-black bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-100 hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-200 focus:ring-4 focus:outline-1 border border-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 mb-3 focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-700 dark:focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-800" title="Template Peserta didik"> Tambah kelas </button>
                        </td>
                    </form>
                </tr>
                <?php foreach($kelas as $key => $v) : ?>
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <?= $key+1 ?>
                    </th>
                    <td class="px-6 py-4">
                        <?= $v->name ?>
                    </td>
                    <td class="px-6 py-4">
                       <?php foreach($guru as $g) : ?>
                            <?php if($g->id === $v->edu_id) : ?>
                                <?= $g->alias ?>
                            <?php endif; ?>
                       <?php endforeach; ?>
                    </td>
                    <td class="px-6 py-4">
                    </td>
                    <td class="px-6 py-4">
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
<?php else : ?>
    <div class="flex gap-2">
        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-black bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-100 hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-200 focus:ring-4 focus:outline-1 border border-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 mb-3 focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-700 dark:focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-800" type="button"><?= sizeof($kelas) > 0 ? $kelas[0]->name : "Kelas tidak ditemukan" ?> <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        </button>
        <div class="inline-flex rounded-md shadow-sm" role="group"> 
            <a href="<?= url_to("admin", $uri->getSegment(3)) ?>/users/template_user" class="text-black bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-100 hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-200 focus:ring-4 focus:outline-1 border-y border-s border-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 mb-3 focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-300 font-medium rounded-l-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-700 dark:focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-800" title="Template Peserta didik">
                <svg class="w-4 h-4 me-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
                </svg>
                Template
            </a>
            <a href="<?= url_to("admin", $uri->getSegment(3)) ?>/users/template_user" class="text-black bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-100 hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-200 focus:ring-4 focus:outline-1 border-y border-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 mb-3 focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-300 font-medium text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-700 dark:focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-800" title="Template Peserta didik">
            <svg class="w-4 h-4 me-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
            </svg>
            SEMUA KELAS</a>
            <a href="<?= url_to("admin", $uri->getSegment(3)) ?>/users/template_user" class="text-black bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-100 hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-200 focus:ring-4 focus:outline-1 border-y border-r border-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 mb-3 focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-300 font-medium rounded-r-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-700 dark:focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-800" title="Template Peserta didik">
            <svg class="w-4 h-4 me-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
            </svg>
            KELAS INI</a>
        </div>
        <a href="<?= url_to("admin", $uri->getSegment(3)) ?>/users/upload" class="text-black bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-100 hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-200 focus:ring-4 focus:outline-1 border border-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 mb-3 focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:hover:bg-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-700 dark:focus:ring-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-800" title="Template Peserta didik">
                    <svg class="w-4 h-4 me-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12V1m0 0L4 5m4-4 4 4m3 5v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
            </svg>
            UPLOAD DATA</a>
    </div>

<!-- Dropdown menu -->
<div id="dropdown" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
        <?php foreach($kelas as $k) : ?>
      <li>
        <a href="<?= url_to('admin', service('uri')->getSegment(3)) ?>/users?kelas=<?= esc($k->name) ?>" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><?= $k->name ?></a>
      </li>
      <?php endforeach; ?>
      <li>
        <a href="<?= url_to('admin', service('uri')->getSegment(3)) ?>/users?kelas=none" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Belum Penempatan</a>
      </li>
      <li>
        <a href="<?= url_to('admin', service('uri')->getSegment(3)) ?>/users/kelas" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Atur Kelas</a>
      </li>
    </ul>
</div>

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
            <?php foreach($user as $key => $v) : ?>
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
                    <a href="<?= url_to("admin", service('request')->admin->name) ?>/cbt/id/<?= $v->id ?>" class="font-medium text-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:text-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 hover:underline">Lihat</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?= $this->endSection() ?>