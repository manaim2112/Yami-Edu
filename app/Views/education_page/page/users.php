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
            <input id="dropzone-file" type="file" class="hidden" accept=".xlsx"  />
        </label>
    </div> 
    
    <div id="message" class="my-3"></div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NISN
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NIS
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NIK
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NAMA LENGKAP
                    </th>
                    <th scope="col" class="px-6 py-3">
                        TEMPAT LAHIR
                    </th>
                    <th scope="col" class="px-6 py-3">
                        TANGGAL LAHIR
                    </th>
                    <th scope="col" class="px-6 py-3">
                        ALAMAT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        SANDI
                    </th>
                    <th scope="col" class="px-6 py-3">
                        KELAS
                    </th>
                    <th scope="col" class="px-6 py-3">
                    </th>
                </tr>
            </thead>
            <tbody id="data_proses">
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        </th>

                        <td class="px-6 py-4">
                           
                        </td>

                </tr>
            </tbody>
        </table>
    </div>

    <!-- use xlsx.mini.min.js from version 0.20.1 -->
    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.mini.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('dropzone-file').addEventListener('change', function(evt) {
                let file = evt.target.files[0] ?? false;
                if(file.type !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    document.getElementById('message').innerHTML = `
                    <div id="alert-2" class="mt-3 flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div class="ms-3 text-sm font-medium">
                            Format File anda Tidak benar, perhatikan bahwa yang diperbolehkan hanya file Excel .xlsx
                        </div>
                        <button type="button" id="triggerElement" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                        </button>
                        </div>
                    `;
                    let dismis = new Dismiss(document.getElementById('alert-2'), document.getElementById('triggerElement'));

                };
                var fileReader = new FileReader();
                fileReader.onload = function (event) {
                    var data = event.target.result;

                    var workbook = XLSX.read(data, {
                        type: "binary",
                    });
                    workbook.SheetNames.forEach(async (sheet) => {
                        let rowObject = XLSX.utils.sheet_to_row_object_array(
                            workbook.Sheets[sheet]
                        );
                        let jsonObject = JSON.stringify(rowObject);
                        
                        // document.getElementById("jsonData").innerHTML = jsonObject;
                        let kelas = rowObject[0].__EMPTY_3;
                        rowObject.shift();
                        rowObject.shift();
                        function removeWhitespace(text) {
                            if(typeof text === "string") {
                                return text.trim() === "";
                            }
                        }
                        for(const i of rowObject) {
                            if(
                                removeWhitespace(i.__EMPTY_2) && 
                                removeWhitespace(i.__EMPTY_3) && 
                                removeWhitespace(i.__EMPTY_4) && 
                                removeWhitespace(i.__EMPTY_5) && 
                                removeWhitespace(i.__EMPTY_6) && 
                                removeWhitespace(i.__EMPTY_7) && 
                                removeWhitespace(i.__EMPTY_8) && 
                                removeWhitespace(i.__EMPTY_9)
                            ) {
                                break;
                            }
                            $element = `<tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    ${i.__EMPTY}
                                </th>
                                <td class="px-6 py-4">${i.__EMPTY_1}</td>
                                <td class="px-6 py-4">${i.__EMPTY_2}</td>
                                <td class="px-6 py-4">${i.__EMPTY_3}</td>
                                <td class="px-6 py-4">${i.__EMPTY_4}</td>
                                <td class="px-6 py-4">${i.__EMPTY_5}</td>
                                <td class="px-6 py-4">${i.__EMPTY_6}</td>
                                <td class="px-6 py-4">${i.__EMPTY_7}</td>
                                <td class="px-6 py-4">${i.__EMPTY_8 ?? '12345'}</td>
                                <td class="px-6 py-4">${kelas}</td>
                                <td class="px-6 py-4 animate-spin" id="loading">|</td>
                            </tr>`;
                            document.getElementById('data_proses').innerHTML += $element;
                            console.log(i.__EMPTY_9);
                            const f = await fetch("<?= url_to('admin', $uri->getSegment(3)) ?>/users/upload", {
                                method: 'POST',
                                headers : {
                                    "Content-Type": "application/json",
                                    "X-Requested-With": "XMLHttpRequest"
                                },
                                body : JSON.stringify({
                                    'kelas' : kelas,
                                    'nisn' : i.__EMPTY_1,
                                    'nis' : i.__EMPTY_2,
                                    'nik' : i.__EMPTY_3,
                                    'fullname' : i.__EMPTY_4,
                                    'city_born' : i.__EMPTY_5,
                                    'date_birth' : i.__EMPTY_6,
                                    'address' : i.__EMPTY_7,
                                    'password' : i.__EMPTY_8 ?? '12345',
                                    '<?= csrf_token() ?>' : '<?= csrf_hash() ?>'
                                })
                            });
                            const feed = await f.json();
                            document.getElementById('loading').classList.remove('animate-spin');
                            if(feed.status === 'success') {
                                document.getElementById('loading').innerText = "OK";
                            } else {
                                console.log(feed.message);
                                document.getElementById('loading').innerText = "GAGAL";
                            }
                            document.getElementById('loading').removeAttribute("id");
                        }
                    });
                };
                fileReader.readAsBinaryString(file);
            })
        })
    </script>

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
                    <?= $key+1 ?>
                </th>
                <td class="px-6 py-4">
                    <?= $v['kelas'] ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v['fullname'] ?>
                </td>
                <td class="px-6 py-4">
                    <?= $v['jabat'] ?? 'Anggota' ?>
                </td>
                <td class="px-6 py-4">
                </td>
                <td class="px-6 py-4">
                    <a href="<?= url_to("admin", service('request')->admin->name) ?>/cbt/id/<?= $v['id'] ?>" class="font-medium text-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-600 dark:text-<?= $setting->kementrian == 'kemenag' ? 'green' : 'blue' ?>-500 hover:underline">Lihat</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?= $this->endSection() ?>