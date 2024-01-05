<?= $this->extend("layout") ?>

<?= $this->section("body") ?>
   
    <div class="h-screen" style="
        background-image: url(https://w0.peakpx.com/wallpaper/420/836/HD-wallpaper-pemandangan-alam-indonesia-trees-wooden-water-path-river-clouds-sky.jpg);
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
    ">
        <div class="absolute h-screen w-full bg-gradient-to-br from-blue-400 to-transparent opacity-50"></div>
        <div class="max-w-xl mx-auto pt-20">
            <div class="text-center">
                <?= var_dump($navigation) ?>
                <h1 class="lg:text-7xl md:text-6xl text-5xl font-bold uppercase drop-shadow-lg drop-shadow-blue-400 text-white">Kita Membuat sebuah perubahan</h1>
                <span class="bg-lime-600/10 border-10 border-lime-600 rounded-full px-5 py-2">
                    Terbaik untuk sekolah anak anda

                </span>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>