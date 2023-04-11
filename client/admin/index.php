<?php
include_once('../template/header.php');
user_access(['Super Admin', 'Admin Akademik', 'Admin Keuangan']);

$sql = "SELECT p.*, s.nama nama_siswa FROM pengajuan p, siswa s WHERE p.id_siswa = s.id_siswa AND p.status = 'Pending' LIMIT 6";
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
                <div class="flex flex-1 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-4 p-5">
                    <div class="flex justify-between items-center">
                        <h4>Pertumbuhan Siswa</h4>
                        <select name="" id="tahun_pertumbuhan_siswa" class="rounded-lg text-gray-800">
                            <?php foreach ($tahun_pertumbuhan_siswa as $key => $value) : ?>
                                <option value="<?= $value['tahun']; ?>"><?= $value['tahun']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative">
                        <canvas id="chart_pertumbuhan_siswa"></canvas>
                    </div>
                    <div class="text-center">
                        <p>Total: <span id="total_pertumbuhan_siswa"></span> Siswa</p>
                    </div>
                </div>
                <div class="flex flex-1 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-4 p-5">
                    <div class="flex justify-between items-center">
                        <h4>Pertumbuhan Instruktur</h4>
                        <select name="" id="tahun_pertumbuhan_instruktur" class="rounded-lg text-gray-800">
                            <?php foreach ($tahun_pertumbuhan_instruktur as $key => $value) : ?>
                                <option value="<?= $value['tahun']; ?>"><?= $value['tahun']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative">
                        <canvas id="chart_pertumbuhan_instruktur"></canvas>
                    </div>
                    <div class="text-center">
                        <p>Total: <span id="total_pertumbuhan_instruktur"></span> Instruktur</p>
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

                <div class="flex flex-col lg:flex-row w-full lg:w-fit gap-12">
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
        /**
         * Fungsi untuk mengambil data source grafik
         * @param {String} param
         * @param {String} tahun
         */
        const getSourceData = async (param, tahun) => await $.get(`${url}?${param}=${tahun}`)

        /**
         * Fungsi untuk mengupdate data source grafik
         * @param {Object} obj
         * @param {Object} data
         */
        const updateChartObject = (obj, data) => {
            obj.data.datasets[0].data = data.map(data => data.jumlah_siswa)
            obj.data.labels = data.map(data => data.bulan)
            obj.update()
        }

        const chartPertumbuhanSiswa = $('#chart_pertumbuhan_siswa');
        const chartPertumbuhanInstruktur = $('#chart_pertumbuhan_instruktur');
        const chartKehadiranSiswa = $('#chart_kehadiran_siswa_hari_ini');
        const chartKehadiranInstruktur = $('#chart_kehadiran_instruktur_hari_ini');

        let tahunSiswa = $('select#tahun_pertumbuhan_siswa option:selected').text()
        let tahunInstruktur = $('select#tahun_pertumbuhan_instruktur option:selected').text()

        const url = window.location.href.replace('client', 'api')

        let dataPertumbuhanSiswa = await getSourceData('pertumbuhan_siswa', tahunSiswa)
        let dataPertumbuhanInstruktur = await getSourceData('pertumbuhan_instruktur', tahunInstruktur)
        let dataKehadiranSiswaPerHari = Object.assign({}, ...await getSourceData('presensi_siswa_per_hari'))
        let dataKehadiranInstrukturPerHari = Object.assign({}, ...await getSourceData('presensi_instruktur_per_hari'))
        let kehadiranSiswa = {
            hadir: 0,
            izin: 0,
            tidakAdaKeterangan: 0
        }
        let kehadiranInstruktur = {
            hadir: 0,
            berhalangan: 0
        }
        for (const c of Object.keys(dataKehadiranSiswaPerHari)) {
            let value = parseInt(dataKehadiranSiswaPerHari[c])
            if (c === 'H') kehadiranSiswa.hadir = value
            else if (c === 'I') kehadiranSiswa.izin = value
            else if (c === 'T') kehadiranSiswa.tidakAdaKeterangan = value
        }
        for (const c of Object.keys(dataKehadiranInstrukturPerHari)) {
            let value = parseInt(dataKehadiranInstrukturPerHari[c])
            if (c === 'Hadir') kehadiranInstruktur.hadir = value
            if (c === 'Berhalangan') kehadiranInstruktur.berhalangan = value
        }

        $('#total_pertumbuhan_siswa').text(dataPertumbuhanSiswa.map(data => parseInt(data.jumlah_siswa)).reduce((a, c) => a + c))
        $('#total_pertumbuhan_instruktur').text(dataPertumbuhanInstruktur.map(data => parseInt(data.jumlah_instruktur)).reduce((a, c) => a + c))

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
                        stepSize: 1
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

        // Line Chart
        let objChartPertumbuhanSiswa = new Chart(chartPertumbuhanSiswa, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Siswa',
                    data: dataPertumbuhanSiswa.map(data => data.jumlah_siswa)
                }],
                labels: dataPertumbuhanSiswa.map(data => data.bulan)
            },
            options: lineOptions
        });

        let objChartPertumbuhanInstruktur = new Chart(chartPertumbuhanInstruktur, {
            type: 'line',
            data: {
                datasets: [{
                    label: 'Instruktur',
                    data: dataPertumbuhanInstruktur.map(data => data.jumlah_instruktur),
                    borderColor: '#f59e0b'
                }],
                labels: dataPertumbuhanInstruktur.map(data => data.bulan)
            },
            options: lineOptions
        });

        // Event Handler
        $('select#tahun_pertumbuhan_siswa').on('change', async function() {
            dataPertumbuhanSiswa = await getSourceData('pertumbuhan_siswa', $(this).val())
            updateChartObject(objChartPertumbuhanSiswa, dataPertumbuhanSiswa)
            $('#total_pertumbuhan_siswa').text(dataPertumbuhanSiswa.map(data => parseInt(data.jumlah_siswa)).reduce((a, c) => a + c))
        })

        $('select#tahun_pertumbuhan_instruktur').on('change', async function() {
            dataPertumbuhanInstruktur = await getSourceData('pertumbuhan_instruktur', $(this).val())
            updateChartObject(objChartPertumbuhanInstruktur, dataPertumbuhanInstruktur)
            $('#total_pertumbuhan_instruktur').text(dataPertumbuhanInstruktur.map(data => parseInt(data.jumlah_instruktur)).reduce((a, c) => a + c))
        })

        // Pie Chart
        const p = new Chart(chartKehadiranSiswa, {
            type: 'pie',
            data: {
                labels: ['Hadir', 'Izin', 'Tidak ada keterangan'],
                datasets: [{
                    label: 'Siswa',
                    data: [
                        kehadiranSiswa.hadir,
                        kehadiranSiswa.izin,
                        kehadiranSiswa.tidakAdaKeterangan,
                    ],
                    backgroundColor: [
                        "#22c55e",
                        "#f59e0b",
                        "#ef4444"
                    ]
                }],
            },
            options: pieOptions
        });

        new Chart(chartKehadiranInstruktur, {
            type: 'pie',
            data: {
                datasets: [{
                    label: 'Instruktur',
                    data: [kehadiranInstruktur.hadir, kehadiranInstruktur.berhalangan],
                    backgroundColor: [
                        "#22c55e",
                        "#f59e0b",
                    ]
                }],
                labels: ['Hadir', 'Berhalangan/Belum presensi']
            },
            options: pieOptions
        });
    })()
</script>

<?php include_once('../template/footer.php') ?>