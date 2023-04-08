<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./client/assets/image/icon.png">
    <link rel="stylesheet" href="./client/assets/css/output.css" />
    <link rel="stylesheet" href="./client/assets/css/flowbite.min.css" />
    <link rel="stylesheet" href="./client/assets/icons/remixicon/remixicon.css" />
    <script src="./client/assets/js/jquery-3.6.4.min.js" defer></script>
    <script src="./client/assets/js/flowbite.min.js" defer></script>
    <script src="./client/assets/js/script.js" defer></script>
    <title>SMART APP</title>
</head>

<body>
    <div class="landing-page min-h-screen flex flex-col">
        <nav id="tentang" class="border-gray-200 bg-white dark:bg-gray-900 shadow">
            <div class="max-w-screen flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="#" class="flex items-center">
                    <img src="./client/assets/image/icon.png" class="h-8 mr-3" alt="Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"><span class="text-amber-500">SMART</span> Solution</span>
                </a>
                <button data-collapse-toggle="main-navbar" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="main-navbar" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="main-navbar">
                    <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li class="relative group">
                            <a href="#tentang" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-700 md:p-0 dark:text-white md:dark:hover:text-amber-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent transition-all">Tentang</a>
                            <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                        </li>
                        <li class="relative group">
                            <a href="#program" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-700 md:p-0 dark:text-white md:dark:hover:text-amber-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent transition-all">Program</a>
                            <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                        </li>
                        <li class="relative group">
                            <a href="#biaya" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-700 md:p-0 dark:text-white md:dark:hover:text-amber-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent transition-all">Biaya Pendidikan</a>
                            <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                        </li>
                        <li class="relative group">
                            <a href="#kontak" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-700 md:p-0 dark:text-white md:dark:hover:text-amber-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent transition-all">Kontak</a>
                            <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                        </li>
                        <li class="relative group">
                            <a href="#lokasi" class="block py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-700 md:p-0 dark:text-white md:dark:hover:text-amber-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent transition-all">Lokasi</a>
                            <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                        </li>
                        <li class="relative group">
                            <button id="login-dropdown" data-dropdown-toggle="dropdown-login" class="flex items-center justify-between w-full py-2 pl-3 pr-4 text-gray-700 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-amber-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-amber-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent transition-all">Masuk</button>
                            <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                            <!-- Dropdown menu -->
                            <div id="dropdown-login" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="p-3 text-sm text-gray-700 dark:text-gray-400 flex flex-col gap-5 ">
                                    <a href="./client/siswa/login.php" class="btn w-full block btn--outline-green">Masuk Sebagai Siswa</a>
                                    <hr>
                                    <a href="./client/instruktur/login.php" class="btn w-full block btn--outline-amber">Masuk Sebagai Instruktur</a>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="flex flex-col text-white ml-16 gap-5 max-w-4xl my-auto">
            <h1 class="text-4xl">Temukan Solusi Belajar Terbaik di Sistem Bimbel Kami</h1>
            <p class="text-lg">Sistem bimbel kami hadir untuk memberikan solusi belajar terbaik bagi siswa dan siswi dari berbagai jenjang pendidikan. Kami menyediakan program belajar yang berkualitas dan didukung oleh tutor profesional untuk membantu siswa mencapai potensi terbaik mereka.</p>
            <a href="./client/siswa/register.php" class="btn bg-white text-gray-800 w-fit hover:translate-y-0.5 hover:text-white hover:bg-amber-500 transition-all">Daftar Sekarang</a>
        </div>
    </div>

    <div id="program" class="flex flex-col p-16 min-h-screen">
        <div class="relative w-fit mb-8">
            <h1 class="text-2xl">Program Kami</h1>
            <div class="bg-gray-800 w-full mb-8 h-1 absolute -top-1 left-0">&nbsp;</div>
        </div>
        <div class="flex gap-5 justify-between items-center flex-wrap flex-1 min-h-full">
            <div class="rounded w-96 h-96 flex flex-col space-y-4">
                <div class="text-5xl text-amber-500"><i class="ri-chat-private-line"></i></div>
                <h3 class="text-xl h-fit font-semibold">Program Private Tutoring</h3>
                <p class="text-lg break-normal">Program Private Tutoring memberikan layanan les privat bagi siswa yang membutuhkan bantuan dalam pemahaman materi pelajaran.</p>
            </div>
            <div class="rounded w-96 h-96 flex flex-col space-y-4">
                <div class="text-5xl text-indigo-500"><i class="ri-link-unlink-m"></i></div>
                <h3 class="text-xl h-fit font-semibold">Program Intensive Course</h3>
                <p class="text-lg break-normal">Program Intensive Course merupakan program yang ditujukan untuk siswa yang ingin mempersiapkan diri untuk menghadapi ujian tertentu, seperti ujian nasional, ujian masuk perguruan tinggi, dan sebagainya.</p>
            </div>
            <div class="rounded w-96 h-96 flex flex-col space-y-4">
                <div class="text-5xl text-blue-500"><i class="ri-article-line"></i></div>
                <h3 class="text-xl h-fit font-semibold">Program Persiapan Ujian Masuk Perguruan Tinggi</h3>
                <p class="text-lg break-normal">Program ini ditujukan untuk siswa-siswa yang hendak mengikuti ujian masuk perguruan tinggi. Dalam program ini, siswa akan mendapatkan materi-materi persiapan ujian seperti soal-soal latihan, tips dan trik mengerjakan soal, serta bimbingan konsultasi dengan guru-guru yang berpengalaman.</p>
            </div>
            <div class="rounded w-96 h-96 flex flex-col space-y-4">
                <div class="text-5xl text-red-500"><i class="ri-git-repository-commits-line"></i></div>
                <h3 class="text-xl h-fit font-semibold">Program Peningkatan Kemampuan Akademik</h3>
                <p class="text-lg break-normal">Program ini ditujukan untuk siswa-siswa yang mengalami kesulitan dalam memahami materi pelajaran di sekolah. Dalam program ini, siswa akan mendapatkan bimbingan belajar secara intensif dari guru-guru yang berpengalaman.</p>
            </div>
        </div>
    </div>

    <div id="biaya" class="flex flex-col p-16 min-h-screen bg-slate-50">
        <div class="relative w-fit mb-8">
            <h1 class="text-2xl">Biaya Pendidikan</h1>
            <div class="bg-gray-800 w-full mb-8 h-1 absolute -top-1 left-0">&nbsp;</div>
        </div>
        <div class="flex justify-between items-center flex-1 flex-wrap gap-48">
            <div class="flex flex-1 justify-between">
                <div class="rounded-xl flex-1 h-96 flex flex-col shadow-lg justify-around pl-9 bg-gradient-to-r from-red-500 text-white">
                    <h3 class="text-4xl font-semibold">Sekolah Dasar</h3>
                    <div class="flex flex-col">
                        <p class="text-2xl">Mulai Dari</p>
                        <div class="flex items-end">
                            <p class="text-4xl font-semibold">Rp. 75.000</p>
                            <span class="text-xl">/ Bulan</span>
                        </div>
                    </div>
                    <a href="./client/siswa/register.php" class="btn w-fit bg-white hover:bg-gray-800 hover:text-white text-gray-800 px-8 py-2 transition-all">DAFTAR</a>
                </div>
            </div>
            <div class="flex flex-1 justify-between">
                <div class="rounded-xl flex-1 h-96 flex flex-col shadow-lg justify-around pl-9 bg-gradient-to-r from-blue-500 text-white">
                    <h3 class="text-4xl font-semibold">Sekolah Menengah Pertama</h3>
                    <div class="flex flex-col">
                        <p class="text-2xl">Mulai Dari</p>
                        <div class="flex items-end">
                            <p class="text-4xl font-semibold">Rp. 130.000</p>
                            <span class="text-xl">/ Bulan</span>
                        </div>
                    </div>
                    <a href="./client/siswa/register.php" class="btn w-fit bg-white hover:bg-gray-800 hover:text-white text-gray-800 px-8 py-2 transition-all">DAFTAR</a>
                </div>
            </div>
            <div class="flex flex-1 justify-between">
                <div class="rounded-xl flex-1 h-96 flex flex-col shadow-lg justify-around pl-9 bg-gradient-to-r from-gray-500 text-white">
                    <h3 class="text-4xl font-semibold">Sekolah Menengah Atas</h3>
                    <div class="flex flex-col">
                        <p class="text-2xl">Mulai Dari</p>
                        <div class="flex items-end">
                            <p class="text-4xl font-semibold">Rp. 150.000</p>
                            <span class="text-xl">/ Bulan</span>
                        </div>
                    </div>
                    <a href="./client/siswa/register.php" class="btn w-fit bg-white hover:bg-gray-800 hover:text-white text-gray-800 px-8 py-2 transition-all">DAFTAR</a>
                </div>
            </div>
        </div>
    </div>

    <div id="testimoni" class="flex flex-col p-16 min-h-screen">
        <div class="relative w-fit mb-8">
            <div class="text-2xl">Testimoni</div>
            <div class="bg-gray-800 w-full mb-8 h-1 absolute -top-1 left-0">&nbsp;</div>
        </div>
        <div class="flex justify-between items-center flex-1 flex-wrap gap-5">
            <figure class="max-w-screen-md">
                <div class="flex items-center mb-4 text-yellow-300">
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                </div>
                <blockquote>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">"Saya sangat merekomendasikan sistem informasi bimbel ini kepada siapa saja yang ingin memperdalam pemahaman mereka di bidang tertentu. Harganya sangat terjangkau dan kualitasnya tidak kalah dengan bimbel-bimbel ternama lainnya. Terima kasih Bimbel SMART Solution!"</p>
                </blockquote>
                <figcaption class="flex items-center mt-6 space-x-3">
                    <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/14431385/pexels-photo-14431385.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                    <div class="flex items-center divide-x-2 divide-gray-300 dark:divide-gray-700">
                        <cite class="pr-3 font-medium text-gray-900 dark:text-white">Aldi Yusuf</cite>
                        <cite class="pl-3 text-sm text-gray-500 dark:text-gray-400">Alumni 2015</cite>
                    </div>
                </figcaption>
            </figure>

            <figure class="max-w-screen-md">
                <div class="flex items-center mb-4 text-yellow-300">
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                </div>
                <blockquote>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">"Saya senang dengan pilihan tutor yang tersedia di sistem informasi bimbel ini. Mereka semua sangat berpengalaman dan memahami betul kebutuhan saya dalam belajar. Tidak hanya itu, waktu belajar yang fleksibel juga memungkinkan saya untuk memilih jadwal yang sesuai dengan kesibukan saya. Sangat direkomendasikan!"</p>
                </blockquote>
                <figcaption class="flex items-center mt-6 space-x-3">
                    <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/7646458/pexels-photo-7646458.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                    <div class="flex items-center divide-x-2 divide-gray-300 dark:divide-gray-700">
                        <cite class="pr-3 font-medium text-gray-900 dark:text-white">Muhammad Arifin</cite>
                        <cite class="pl-3 text-sm text-gray-500 dark:text-gray-400">Alumni 2018</cite>
                    </div>
                </figcaption>
            </figure>

            <figure class="max-w-screen-md">
                <div class="flex items-center mb-4 text-yellow-300">
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                </div>
                <blockquote>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">"Saya merasa sangat puas dengan hasil yang saya dapatkan setelah belajar di sistem informasi bimbel ini. Tidak hanya penguasaan materi, saya juga merasa lebih percaya diri dan siap menghadapi ujian. Terima kasih Bimbel SMART Solution!"</p>
                </blockquote>
                <figcaption class="flex items-center mt-6 space-x-3">
                    <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/10902589/pexels-photo-10902589.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                    <div class="flex items-center divide-x-2 divide-gray-300 dark:divide-gray-700">
                        <cite class="pr-3 font-medium text-gray-900 dark:text-white">Cahyadi Bahar</cite>
                        <cite class="pl-3 text-sm text-gray-500 dark:text-gray-400">Alumni 2017</cite>
                    </div>
                </figcaption>
            </figure>

            <figure class="max-w-screen-md">
                <div class="flex items-center mb-4 text-yellow-300">
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    <i class="ri-star-fill text-amber-400 text-2xl"></i>
                </div>
                <blockquote>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">"Program bimbingan belajar di sistem informasi bimbel ini sangat membantu saya dalam mempersiapkan ujian masuk perguruan tinggi. Tidak hanya diberikan latihan soal secara intensif, tetapi juga diberikan tips dan trik dalam menghadapi ujian. Saya berhasil diterima di universitas impian saya, terima kasih bimbel SMART Solution!"</p>
                </blockquote>
                <figcaption class="flex items-center mt-6 space-x-3">
                    <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/5792654/pexels-photo-5792654.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                    <div class="flex items-center divide-x-2 divide-gray-300 dark:divide-gray-700">
                        <cite class="pr-3 font-medium text-gray-900 dark:text-white">Yahya Anwar</cite>
                        <cite class="pl-3 text-sm text-gray-500 dark:text-gray-400">Alumni 2020</cite>
                    </div>
                </figcaption>
            </figure>

        </div>
    </div>

    <div id="kontak" class="flex flex-col p-16 bg-slate-50">
        <div class="relative w-fit mb-8">
            <h1 class="text-2xl">Kontak Kami</h1>
            <div class="bg-gray-800 w-full mb-8 h-1 absolute -top-1 left-0">&nbsp;</div>
        </div>
        <div class="w-3/4 flex-col gap-2">
            <div class="space-y-2">
                <p class="text-lg">Jangan ragu untuk menghubungi kami jika ada pertanyaan atau informasi lebih lanjut yang Anda butuhkan. Tim kami siap membantu Anda dengan senang hati.</p>
                <p class="text-lg">Anda dapat menghubungi kami melalui:</p>
            </div>
            <div class="mt-5 space-y-2">
                <p><span class="font-semibold">Telepon:</span> 0813-9125-0606</p>
                <p><span class="font-semibold">Email:</span> bimbelsmartsolution@gmail.com</p>
                <p><span class="font-semibold">Alamat:</span> Gedangan, Kabupaten Sukoharjo, Jawa Tengah 57552, Indonesia </p>
            </div>
        </div>
    </div>

    <div id="lokasi" class="flex flex-col p-16">
        <div class="relative w-fit mb-8">
            <h1 class="text-2xl">Lokasi</h1>
            <div class="bg-gray-800 w-full mb-8 h-1 absolute -top-1 left-0">&nbsp;</div>
        </div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.2976038678607!2d112.69759431546774!3d-8.17274358417597!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd61f44ddac6311%3A0xbaf67cbef55a263c!2sBimbel%20SMART!5e0!3m2!1sid!2sid!4v1680863237905!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <footer class="flex bg-zinc-800 text-white p-16 gap-5 flex-col lg:flex-row">
        <div class="flex flex-col">
            <div class="flex items-center mb-5">
                <img class="w-12 h-12" src="./client/assets/image/icon.png" alt="Footer Logo">
                <h3 class="text-2xl"><span class="text-amber-500">SMART</span> Solution</h3>
            </div>
            <div class="flex flex-col gap-3">
                <p class="w-1/2">Unlock Your Full Potential with Our Comprehensive Learning Solutions</p>
                <p class="text-xl font-semibold tracking-wide">(+62) 813-9125-0606</p>
                <a class="underline text-sm hover:text-amber-500 transition-all w-fit" href="">bimbelsmartsolution@gmail.com</a>
            </div>
        </div>
        <div class="flex flex-1 flex-col gap-3">
            <h3 class="font-semibold text-xl">Navigation</h3>
            <a class="hover:text-amber-500 transition-all w-fit" href="#tentang">Tentang</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#program">Program</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#biaya">Biaya Pendidikan</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#kontak">Kontak</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#lokasi">Lokasi</a>
        </div>
        <div class="flex flex-1 flex-col gap-3">
            <h3 class="font-semibold text-xl">Layanan</h3>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Rekrutmen Instruktur</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Pengajuan Kelas Privat</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Mitra</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Pertanyaan</a>
        </div>
        <div class="flex flex-1 flex-col gap-3">
            <h3 class="font-semibold text-xl">FAQ</h3>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Pertanyaan Umum</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Pertanyaan Teknis</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Panduan Penggunaan</a>
        </div>
        <div class="flex flex-1 flex-col gap-3">
            <h3 class="font-semibold text-xl">Syarat dan Ketentuan</h3>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Kebijakan Privasi</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Syarat dan Ketentuan Penggunaan</a>
            <a class="hover:text-amber-500 transition-all w-fit" href="#">Disclaimer</a>
        </div>
    </footer>
</body>

</html>