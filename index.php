<?php

require_once('./vendor/autoload.php');
require_once('./config.php');

use Dotenv\Dotenv;

// Load ENV
$dotenv = Dotenv::createImmutable(__DIR__, './.env')->load();

// Connect Database
if (ENVIRONMENT === 'PRODUCTION')
    $db = mysqli_connect($_ENV['PROD_DB_HOST'], $_ENV['PROD_DB_USER'], $_ENV['PROD_DB_PASS'], $_ENV['PROD_DB_NAME']) or die("ERROR: Could not connect. " . mysqli_connect_error());
else
    $db = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']) or die("ERROR: Could not connect. " . mysqli_connect_error());

$sql = "SELECT * FROM jenjang WHERE nama IN ('SD', 'SMP', 'SMA')";

$arr_jenjang = [];
$data_jenjang = $db->query($sql) or die($db->error);

foreach ($data_jenjang as $key => $value)
    $arr_jenjang[$key] = $value['biaya_pendidikan'];

$data_jenjang = $arr_jenjang;

function format_rupiah(int $dec)
{
    return number_format($dec, 0, "", ".");
}
?>

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
    <main class="landing-page">
        <section id="hero" class="min-h-screen flex flex-col relative">
            <!-- Video by Max Fischer: https://www.pexels.com/video/a-male-teacher-walking-inside-the-classroom-while-teaching-5198165/ -->
            <video src="./client/assets/video/hero_background.mp4" autoplay loop muted class="absolute top-0 left-0 w-full h-full object-cover hidden md:block"></video>
            <img src="./client/assets/image/register_siswa.jpg" alt="Hero Image" class="absolute top-0 left-0 w-full h-full object-cover block md:hidden">
            <!-- Overlay -->
            <div class="absolute top-0 left-0 w-full h-full bg-gray-500 mix-blend-multiply">&nbsp;</div>
            <nav id="tentang" class="border-gray-200 z-20">
                <div class="max-w-screen flex flex-wrap items-center justify-between mx-auto px-16 py-4">
                    <a href="#" class="flex items-center">
                        <img src="./client/assets/image/icon.png" class="h-8 mr-3" alt="Logo" />
                        <span class="self-center text-2xl font-semibold whitespace-nowrap text-white"><span class="text-amber-500">SMART</span> Solution</span>
                    </a>
                    <button data-collapse-toggle="main-navbar" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="main-navbar" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="hidden w-full md:block md:w-auto" id="main-navbar">
                        <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-transparent md:text-white">
                            <li class="relative group">
                                <a href="#tentang" class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:p-0 transition-all">Tentang</a>
                                <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                            </li>
                            <li class="relative group">
                                <a href="#program" class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:p-0 transition-all">Program</a>
                                <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                            </li>
                            <li class="relative group">
                                <a href="#biaya" class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:p-0 transition-all">Biaya Pendidikan</a>
                                <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                            </li>
                            <li class="relative group">
                                <a href="#kontak" class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:p-0 transition-all">Kontak</a>
                                <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                            </li>
                            <li class="relative group">
                                <a href="#lokasi" class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:p-0 transition-all">Lokasi</a>
                                <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                            </li>
                            <li class="relative group">
                                <button id="login-dropdown" data-dropdown-toggle="dropdown-login" class="flex items-center justify-between w-full py-2 pl-3 pr-4 border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:p-0 md:w-auto transition-all">Masuk</button>
                                <div class="mt-1 w-0 group-hover:w-full transition-all absolute bg-amber-500 h-0.5">&nbsp;</div>
                                <!-- Dropdown menu -->
                                <div id="dropdown-login" class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                    <ul class="p-3 text-sm text-gray-700 flex flex-col gap-5 ">
                                        <a href="./client/siswa/login.php" class="btn w-full block btn--outline-green">Masuk Sebagai Siswa</a>
                                        <a href="./client/instruktur/login.php" class="btn w-full block btn--outline-amber">Masuk Sebagai Instruktur</a>
                                        <hr>
                                        <a href="./client/admin/login.php" class="btn w-full block btn--outline-red">Masuk Sebagai Admin</a>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="flex flex-1 items-center text-white z-10 md:ml-16">
                <div class="flex flex-col gap-5 w-full md:!w-3/5 text-center md:!text-left items-center md:!items-start">
                    <h1 class="text-4xl">Temukan Solusi Belajar Terbaik di Sistem Bimbel Kami</h1>
                    <p class="text-lg">Sistem bimbel kami hadir untuk memberikan solusi belajar terbaik bagi siswa dan siswi dari berbagai jenjang pendidikan. Kami menyediakan program belajar yang berkualitas dan didukung oleh tutor profesional untuk membantu siswa mencapai potensi terbaik mereka.</p>
                    <a href="./client/siswa/register.php" class="p-3 px-8 bg-amber-500 hover:bg-emerald-500 transition-all duration-300 active:translate-y-2 hover:shadow-lg hover:shadow-green-500/50 rounded-full font-bold">
                        DAFTAR SEKARANG
                    </a>
                </div>
            </div>
        </section>

        <section id="program">
            <div class="relative text-center mb-16">
                <h2>Program Kami</h2>
            </div>
            <div class="flex items-center flex-col md:flex-row flex-1 min-h-full gap-12 flex-wrap">
                <div class="flex flex-1 flex-wrap gap-12 flex-col lg:flex-row">
                    <div class="rounded w-96 h-96 flex flex-1 flex-col lg:pr-5">
                        <div class="text-5xl text-amber-500"><i class="ri-chat-private-line"></i></div>
                        <h3 class="text-xl h-fit font-semibold my-5 h-fit md:h-1/5">Private Tutoring</h3>
                        <p class="text-lg break-normal">Kami memberikan layanan les privat bagi siswa yang membutuhkan bantuan dalam pemahaman materi pelajaran</p>
                    </div>
                    <div class="rounded w-96 h-96 flex flex-1 flex-col lg:pr-5">
                        <div class="text-5xl text-indigo-500"><i class="ri-link-unlink-m"></i></div>
                        <h3 class="text-xl h-fit font-semibold my-5 h-fit md:h-1/5">Intensive Course</h3>
                        <p class="text-lg break-normal">Program ini ditujukan untuk siswa yang ingin mempersiapkan diri untuk menghadapi ujian tertentu</p>
                    </div>
                </div>
                <div class="flex flex-1 flex-wrap gap-12 flex-col lg:flex-row">
                    <div class="rounded w-96 h-96 flex flex-1 flex-col lg:pr-5">
                        <div class="text-5xl text-blue-500"><i class="ri-article-line"></i></div>
                        <h3 class="text-xl h-fit font-semibold my-5 h-fit md:h-1/5">Persiapan Masuk Perguruan Tinggi</h3>
                        <p class="text-lg break-normal">Dalam program ini, siswa akan mendapatkan materi-materi persiapan ujian</p>
                    </div>
                    <div class="rounded w-96 h-96 flex flex-1 flex-col lg:pr-5">
                        <div class="text-5xl text-red-500"><i class="ri-git-repository-commits-line"></i></div>
                        <h3 class="text-xl h-fit font-semibold my-5 h-fit md:h-1/5">Peningkatan Kemampuan Akademik</h3>
                        <p class="text-lg break-normal">Dalam program ini, siswa akan mendapatkan bimbingan belajar secara intensif dari instruktur yang berpengalaman.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="quote" class="bg-landing-page-quote object-fit bg-cover bg-fixed relative h-96 !min-h-0">
            <!-- Overlay -->
            <div class="absolute top-0 left-0 h-full w-full bg-gray-500 mix-blend-multiply">&nbsp;</div>
            <div class="flex flex-1 flex-col items-end text-white z-10 text-2xl">
                <blockquote class="flex flex-1 items-center">
                    Pendidikan adalah kekuatan, pengetahuan adalah kekuatan, dan pendidikan adalah pengetahuan, dan dengan pengetahuan, kita memiliki kekuatan untuk mengubah dunia.
                </blockquote>
                <div class="flex flex-1 items-center gap-2">
                    <cite>Unknown</cite>
                    <span>&mdash;</span>
                </div>
            </div>
        </section>

        <section id="biaya">
            <div class="relative text-center mb-16">
                <h2>Biaya Pendidikan</h2>
            </div>
            <div class="flex flex-col lg:flex-row justify-around gap-16 lg:gap-0 items-center">
                <div class="flip-card" id="landing-page-biaya-sd">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <div class="flip-card-image"></div>
                            <h3><span>SEKOLAH DASAR</span></h3>
                            <ul class="flip-card-feature">
                                <li>Program Belajar Terstruktur</li>
                                <li>Tutor Berkualitas</li>
                                <li>Monitoring Kemajuan Siswa</li>
                            </ul>
                        </div>
                        <div class="flip-card-back">
                            <p>Mulai dari</p>
                            <h6>Rp <?= format_rupiah($data_jenjang[0]) ?><span>/ Bulan</span></h6>
                            <a href="./client/siswa/login.php">DAFTAR</a>
                        </div>
                    </div>
                </div>
                <div class="flip-card" id="landing-page-biaya-smp">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <div class="flip-card-image"></div>
                            <h3><span>SEKOLAH MENENGAH PERTAMA</span></h3>
                            <ul class="flip-card-feature">
                                <li>Program Belajar Berstandar Nasional</li>
                                <li>Konseling Pendidikan</li>
                                <li>Simulasi Ujian</li>
                            </ul>
                        </div>
                        <div class="flip-card-back">
                            <p>Mulai dari</p>
                            <h6>Rp <?= format_rupiah($data_jenjang[1]) ?><span>/ Bulan</span></h6>
                            <a href="./client/siswa/login.php">DAFTAR</a>
                        </div>
                    </div>
                </div>
                <div class="flip-card" id="landing-page-biaya-sma">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <div class="flip-card-image"></div>
                            <h3><span>SEKOLAH MENENGAH KEATAS</span></h3>
                            <ul class="flip-card-feature">
                                <li>Program Belajar Berbasis Kurikulum</li>
                                <li>Persiapan Ujian Masuk Perguruan Tinggi</li>
                                <li>Bimbingan Karir</li>
                            </ul>
                        </div>
                        <div class="flip-card-back">
                            <p>Mulai dari</p>
                            <h6>Rp <?= format_rupiah($data_jenjang[2]) ?><span>/ Bulan</span></h6>
                            <a href="./client/siswa/login.php">DAFTAR</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="./client/siswa/login.php" class="btn btn--amber py-3 px-8 rounded-full mt-16 w-60 text-center mx-auto font-normal tracking-wider hover:tracking-widest hover:shadow-lg hover:shadow-amber-500/50 active:translate-y-2 transition-all">LIHAT LEBIH LANJUT</a>
        </section>

        <section id="testimoni">
            <div class="relative text-center mb-16">
                <h2>Testimoni</h2>
            </div>
            <div class="flex flex-col lg:flex-row gap-8 p-16 lg:p-0">
                <figure class="flex flex-1 flex-col shadow-lg rounded-lg p-8 space-y-6">
                    <div class="flex flex-1 justify-between">
                        <div class="flex gap-5 items-center">
                            <img class="w-20 h-20 rounded-lg" src="https://images.pexels.com/photos/5792654/pexels-photo-5792654.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                            <figcaption class="flex flex-col gap-1">
                                <cite class="font-medium text-gray-900">Yahya Anwar</cite>
                                <cite class="text-sm text-gray-500">Alumni 2020</cite>
                            </figcaption>
                        </div>
                        <i class="ri-double-quotes-r text-amber-500 text-6xl"></i>
                    </div>
                    <blockquote>
                        <p class="text-lg text-gray-600">Program bimbingan belajar di sistem informasi bimbel ini sangat membantu saya dalam mempersiapkan ujian masuk perguruan tinggi. Tidak hanya diberikan latihan soal secara intensif, tetapi juga diberikan tips dan trik dalam menghadapi ujian. Saya berhasil diterima di universitas impian saya, terima kasih bimbel SMART Solution!</p>
                    </blockquote>
                    <div class="flex items-center text-yellow-300">
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    </div>
                </figure>

                <figure class="flex flex-1 flex-col shadow-lg rounded-lg p-8 space-y-6">
                    <div class="flex flex-1 justify-between">
                        <div class="flex gap-5 items-center">
                            <img class="w-20 h-20 rounded-lg" src="https://images.pexels.com/photos/14431385/pexels-photo-14431385.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                            <figcaption class="flex flex-col gap-1">
                                <cite class="font-medium text-gray-900">Aldi Yusuf</cite>
                                <cite class="text-sm text-gray-500">Alumni 2015</cite>
                            </figcaption>
                        </div>
                        <i class="ri-double-quotes-r text-amber-500 text-6xl"></i>
                    </div>
                    <blockquote>
                        <p class="text-lg text-gray-600">Saya sangat merekomendasikan sistem informasi bimbel ini kepada siapa saja yang ingin memperdalam pemahaman mereka di bidang tertentu. Harganya sangat terjangkau dan kualitasnya tidak kalah dengan bimbel-bimbel ternama lainnya. Terima kasih Bimbel SMART Solution!</p>
                    </blockquote>
                    <div class="flex items-center text-yellow-300">
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    </div>
                </figure>
            </div>
            <!-- <div class="flex justify-between items-center flex-1 flex-wrap gap-5">
                <figure class="max-w-screen-md">
                    <div class="flex items-center mb-4 text-yellow-300">
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                        <i class="ri-star-fill text-amber-400 text-2xl"></i>
                    </div>
                    <blockquote>
                        <p class="text-2xl font-semibold text-gray-900">"Saya sangat merekomendasikan sistem informasi bimbel ini kepada siapa saja yang ingin memperdalam pemahaman mereka di bidang tertentu. Harganya sangat terjangkau dan kualitasnya tidak kalah dengan bimbel-bimbel ternama lainnya. Terima kasih Bimbel SMART Solution!"</p>
                    </blockquote>
                    <figcaption class="flex items-center mt-6 space-x-3">
                        <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/14431385/pexels-photo-14431385.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                        <div class="flex items-center divide-x-2 divide-gray-300">
                            <cite class="pr-3 font-medium text-gray-900">Aldi Yusuf</cite>
                            <cite class="pl-3 text-sm text-gray-500">Alumni 2015</cite>
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
                        <p class="text-2xl font-semibold text-gray-900">"Saya senang dengan pilihan tutor yang tersedia di sistem informasi bimbel ini. Mereka semua sangat berpengalaman dan memahami betul kebutuhan saya dalam belajar. Tidak hanya itu, waktu belajar yang fleksibel juga memungkinkan saya untuk memilih jadwal yang sesuai dengan kesibukan saya. Sangat direkomendasikan!"</p>
                    </blockquote>
                    <figcaption class="flex items-center mt-6 space-x-3">
                        <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/7646458/pexels-photo-7646458.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                        <div class="flex items-center divide-x-2 divide-gray-300">
                            <cite class="pr-3 font-medium text-gray-900">Muhammad Arifin</cite>
                            <cite class="pl-3 text-sm text-gray-500">Alumni 2018</cite>
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
                        <p class="text-2xl font-semibold text-gray-900">"Saya merasa sangat puas dengan hasil yang saya dapatkan setelah belajar di sistem informasi bimbel ini. Tidak hanya penguasaan materi, saya juga merasa lebih percaya diri dan siap menghadapi ujian. Terima kasih Bimbel SMART Solution!"</p>
                    </blockquote>
                    <figcaption class="flex items-center mt-6 space-x-3">
                        <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/10902589/pexels-photo-10902589.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                        <div class="flex items-center divide-x-2 divide-gray-300">
                            <cite class="pr-3 font-medium text-gray-900">Cahyadi Bahar</cite>
                            <cite class="pl-3 text-sm text-gray-500">Alumni 2017</cite>
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
                        <p class="text-2xl font-semibold text-gray-900">"Program bimbingan belajar di sistem informasi bimbel ini sangat membantu saya dalam mempersiapkan ujian masuk perguruan tinggi. Tidak hanya diberikan latihan soal secara intensif, tetapi juga diberikan tips dan trik dalam menghadapi ujian. Saya berhasil diterima di universitas impian saya, terima kasih bimbel SMART Solution!"</p>
                    </blockquote>
                    <figcaption class="flex items-center mt-6 space-x-3">
                        <img class="w-8 h-8 rounded-full" src="https://images.pexels.com/photos/5792654/pexels-photo-5792654.jpeg?auto=compress&cs=tinysrgb&w=1600" alt="profile picture">
                        <div class="flex items-center divide-x-2 divide-gray-300">
                            <cite class="pr-3 font-medium text-gray-900">Yahya Anwar</cite>
                            <cite class="pl-3 text-sm text-gray-500">Alumni 2020</cite>
                        </div>
                    </figcaption>
                </figure>
            </div> -->
        </section>

        <section id="kontak" class="!min-h-0">
            <div class="relative text-center mb-16">
                <h2>Kontak Kami</h2>
            </div>
            <div class="w-3/4 flex-col gap-2">
                <div class="space-y-2">
                    <p class="text-lg">Jangan ragu untuk menghubungi kami jika ada pertanyaan atau informasi lebih lanjut yang Anda butuhkan. Tim kami siap membantu Anda dengan senang hati.</p>
                    <p class="text-lg">Anda dapat menghubungi kami melalui:</p>
                </div>
                <div class="mt-5 space-y-2">
                    <p><span class="font-semibold">Telepon:</span> 0813-9125-0606</p>
                    <p><span class="font-semibold">Email:</span> bimbelsmartsolution@gmail.com</p>
                    <p><span class="font-semibold">Alamat:</span> Turen, Kec. Turen, Kabupaten Malang, Jawa Timur 65175 </p>
                </div>
            </div>
        </section>

        <section id="lokasi">
            <div class="relative text-center mb-16">
                <h2>Lokasi</h2>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.2976038678607!2d112.69759431546774!3d-8.17274358417597!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd61f44ddac6311%3A0xbaf67cbef55a263c!2sBimbel%20SMART!5e0!3m2!1sid!2sid!4v1680863237905!5m2!1sid!2sid" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>

    </main>
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
            <h3 class="font-semibold text-xl">Navigasi</h3>
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