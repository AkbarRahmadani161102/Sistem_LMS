<?php
include_once('../template/header.php');
user_access('admin');

$sql = "SELECT p.*, s.nama nama_siswa FROM pengajuan p, siswa s WHERE p.id_siswa = s.id_siswa LIMIT 6";
$data_pengajuan_siswa = $db->query($sql) or die($sql);
$data_pengajuan_siswa->fetch_assoc();

$sql = "SELECT * FROM kelas LIMIT 7";
$data_kelas = $db->query($sql) or die($sql);
$data_kelas->fetch_assoc();

$sql = "SELECT DISTINCT YEAR(tgl_dibuat) tahun FROM siswa ORDER BY tahun";
$tahun_pertumbuhan_siswa = $db->query($sql) or die($db->error);
$tahun_pertumbuhan_siswa->fetch_assoc();

$sql = "SELECT DISTINCT YEAR(tgl_dibuat) tahun FROM instruktur ORDER BY tahun";
$tahun_pertumbuhan_instruktur = $db->query($sql) or die($db->error);
$tahun_pertumbuhan_instruktur->fetch_assoc();
?>

<div id="index" class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Dashboard', 'filename' => 'index.php']]);
            ?>

            <div class="mt-8 flex flex-col lg:flex-row gap-12">
                <div class="flex flex-1 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5 divide-y">
                    <div class="flex justify-between items-center">
                        <h4>Pertumbuhan Siswa</h4>
                        <select name="" id="tahun_pertumbuhan_siswa" class="rounded-lg text-gray-800">
                            <?php foreach ($tahun_pertumbuhan_siswa as $key => $value) : ?>
                                <option value="<?= $value['tahun']; ?>"><?= $value['tahun']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative px-5 py-12">
                        <canvas id="chart_pertumbuhan_siswa"></canvas>
                    </div>
                </div>
                <div class="flex flex-1 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5 divide-y">
                    <div class="flex justify-between items-center">
                        <h4>Pertumbuhan Instruktur</h4>
                        <select name="" id="tahun_pertumbuhan_instruktur" class="rounded-lg text-gray-800">
                            <?php foreach ($tahun_pertumbuhan_instruktur as $key => $value) : ?>
                                <option value="<?= $value['tahun']; ?>"><?= $value['tahun']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative px-5 py-12">
                        <canvas id="chart_pertumbuhan_instruktur"></canvas>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col lg:flex-row items-start gap-12">
                <div class="flex w-full flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5">
                    <div class="flex justify-between items-center">
                        <h4>Pengajuan Siswa</h4>
                    </div>
                    <?php foreach ($data_pengajuan_siswa as $key => $value) : ?>
                        <a href="./pengajuan?id=<?= $value['id_pengajuan'] ?>" class="flex flex-col flex-1 rounded-lg p-5 bg-white dark:bg-gray-500 gap-2">
                            <div class="flex justify-between">
                                <h6><?= $value['nama_siswa'] ?></h6>
                                <h6 class="<?= $value['status'] === 'Pending' ? 'text-amber-500' : 'text-green-500' ?>"><?= $value['status'] ?></h6>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-sm"><?= $value['judul'] ?></p>
                                <p class="text-sm text-gray-500 dark:text-gray-200"><?= $value['tgl_dibuat'] ?></p>
                            </div>
                        </a>
                    <?php endforeach ?>
                </div>

                <div class="flex w-full flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5">
                    <div class="flex justify-between items-center">
                        <h4>Ketersediaan Kelas</h4>
                    </div>
                    <?php foreach ($data_kelas as $key => $value) : ?>
                        <div class="flex flex-col flex-1 rounded-lg p-5 bg-white dark:bg-gray-500">
                            <div class="flex justify-between">
                                <h6><?= $value['nama'] ?></h6>
                                <h6 class="<?= $value['status'] === 'Reguler' ? 'text-green-500' : 'text-amber-500' ?>"><?= $value['status'] ?></h6>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>

                <div class="flex flex-col w-full lg:w-fit gap-12">
                    <div class="flex w-full lg:w-80 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5">
                        <div class="flex justify-between items-center">
                            <h4>Kehadiran Siswa</h4>
                        </div>
                        <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative p-16 lg:p-0 border-t-2 lg:border-0">
                            <canvas id="chart_kehadiran_siswa_hari_ini"></canvas>
                        </div>
                    </div>
                    <div class="flex w-full lg:w-80 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5">
                        <div class="flex justify-between items-center">
                            <h4>Kehadiran Instruktur</h4>
                        </div>
                        <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative p-16 lg:p-0 border-t-2 lg:border-0">
                            <canvas id="chart_kehadiran_instruktur_hari_ini"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    (async () => {
        const chartPertumbuhanSiswa = document.getElementById('chart_pertumbuhan_siswa');
        const chartPertumbuhanInstruktur = document.getElementById('chart_pertumbuhan_instruktur');
        const chartKehadiranSiswa = document.getElementById('chart_kehadiran_siswa_hari_ini');
        const chartKehadiranInstruktur = document.getElementById('chart_kehadiran_instruktur_hari_ini');

        const tahun_siswa = $('select#tahun_pertumbuhan_siswa option:selected').text()
        const tahun_instruktur = $('select#tahun_pertumbuhan_instruktur option:selected').text()

        let pertumbuhanSiswa = await $.get(window.location.href.replace('client', 'api'), {
            pertumbuhan_siswa: tahun_siswa
        })

        let pertumbuhanInstruktur = await $.get(window.location.href.replace('client', 'api'), {
            pertumbuhan_instruktur: tahun_instruktur
        })

        const isDarkMode = $('html').hasClass('dark')
        const color = isDarkMode ? "#fff" : "#1f2937"

        const lineOptions = {
            responsive: true,
            layout: {
                padding: 20
            },
            scales: {
                x: {
                    ticks: {
                        color,
                    },
                    grid: {
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    border: {
                        color,
                    }
                },
                y: {
                    ticks: {
                        color,
                    },
                    grid: {
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    border: {
                        color,
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            elements: {
                point: {
                    radius: 0,
                    hitRadius: 50
                }
            }
        }

        const pieOptions = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        color,
                    }
                }
            },
        }

        let objChartPertumbuhanSiswa = new Chart(chartPertumbuhanSiswa, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Siswa',
                    data: [...pertumbuhanSiswa.map(data => data.jumlah_siswa)]
                }],
                labels: [...pertumbuhanSiswa.map(data => data.bulan)]
            },
            options: lineOptions
        });

        let objChartPertumbuhanInstruktur = new Chart(chartPertumbuhanInstruktur, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Instruktur',
                    data: [...pertumbuhanInstruktur.map(data => data.jumlah_instruktur)],
                    borderColor: '#f59e0b'
                }],
                labels: [...pertumbuhanInstruktur.map(data => data.bulan)]
            },
            options: lineOptions
        });

        new Chart(chartKehadiranSiswa, {
            type: 'pie',
            data: {
                datasets: [{
                    label: 'Siswa',
                    data: [70, 8, 5],
                    backgroundColor: [
                        "#22c55e",
                        "#f59e0b",
                        "#ef4444"
                    ]
                }],
                labels: ['Hadir', 'Izin', 'Tidak Ada Keterangan']
            },
            options: pieOptions
        });

        new Chart(chartKehadiranInstruktur, {
            type: 'pie',
            data: {
                datasets: [{
                    label: 'Instruktur',
                    data: [11, 0, 5],
                    backgroundColor: [
                        "#22c55e",
                        "#f59e0b",
                        "#ef4444"
                    ]
                }],
                labels: ['Hadir', 'Izin', 'Tidak Ada Keterangan']
            },
            options: pieOptions
        });

        $('select#tahun_pertumbuhan_siswa').on('change', async function() {
            pertumbuhanSiswa = await $.get(window.location.href.replace('client', 'api'), {
                pertumbuhan_siswa: $(this).val()
            })
            objChartPertumbuhanSiswa.data.datasets[0].data = [...pertumbuhanSiswa.map(data => data.jumlah_siswa)]
            objChartPertumbuhanSiswa.data.labels = [...pertumbuhanSiswa.map(data => data.bulan)]
            objChartPertumbuhanSiswa.update()
        })

        $('select#tahun_pertumbuhan_instruktur').on('change', async function() {
            pertumbuhanInstruktur = await $.get(window.location.href.replace('client', 'api'), {
                pertumbuhan_instruktur: $(this).val()
            })
            objChartPertumbuhanInstruktur.data.datasets[0].data = [...pertumbuhanInstruktur.map(data => data.jumlah_instruktur)]
            objChartPertumbuhanInstruktur.data.labels = [...pertumbuhanInstruktur.map(data => data.bulan)]
            objChartPertumbuhanInstruktur.update()
        })
    })()
</script>

<?php include_once('../template/footer.php') ?>