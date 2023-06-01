<?php
include_once('../template/header.php');
user_access('admin');

$sql = "SELECT p.*, s.nama nama_siswa FROM pengajuan p, siswa s WHERE p.id_siswa = s.id_siswa AND p.status = 'Pending' LIMIT 6";
$data_pengajuan_siswa = $db->query($sql) or die($sql);
$data_pengajuan_siswa->fetch_assoc();

$sql = "SELECT * FROM kelas LIMIT 7";
$data_kelas = $db->query($sql) or die($sql);
$data_kelas->fetch_assoc();

$sql = "SELECT DISTINCT tahun FROM (
    SELECT YEAR(tgl_dibuat) tahun FROM siswa
    UNION ALL
    SELECT YEAR(tgl_dibuat) tahun FROM instruktur) tahun
    ORDER BY tahun DESC";
$tahun_pertumbuhan_pendaftar = $db->query($sql) or die($db->error);
$tahun_pertumbuhan_pendaftar->fetch_assoc();

$sql = "SELECT COUNT(*) jumlah_siswa, status FROM (SELECT dj.tgl_pertemuan, id_siswa, status FROM absensi_siswa a
JOIN detail_jadwal dj ON a.id_detail_jadwal = dj.id_detail_jadwal
WHERE dj.tgl_pertemuan = CURRENT_DATE()
GROUP BY id_siswa) grouped_absensi 
GROUP BY status";
$presensi_siswa = $db->query($sql) or die($db->error);

$sql = "SELECT COUNT(*) jumlah_instruktur, status_kehadiran_instruktur as status
FROM (SELECT IF(status_kehadiran_instruktur IS NULL, 'Berhalangan', status_kehadiran_instruktur) status_kehadiran_instruktur FROM detail_jadwal WHERE tgl_pertemuan = CURRENT_DATE() GROUP BY id_instruktur) grouped_instruktur
GROUP BY status_kehadiran_instruktur";
$presensi_instruktur = $db->query($sql) or die($db->error);
?>

<div class="w-full min-h-screen flex">
    <?php include_once '../components/dashboard_sidebar.php' ?>
    <div class="w-full flex flex-col">
        <div class="p-4 sm:ml-64">
            <?php include_once '../components/dashboard_navbar.php';
            generate_breadcrumb([['title' => 'Dashboard', 'filename' => 'index.php']]);
            ?>

            <div class="mt-8 flex flex-col lg:flex-row gap-12">
                <div class="flex flex-1 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-4 p-5 h-full">
                    <div class="flex justify-between items-center">
                        <div class="flex gap-2">
                            <h4>Grafik Finansial</h4>
                            <button data-popover-target="grafik_finansial_tooltip" data-popover-placement="right" type="button" class="text-gray-800 dark:text-white"><i class="ri-question-line"></i></button>
                            <div id="grafik_finansial_tooltip" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Untuk mengupdate data silahkan unduh laporan jurnal umum
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <select name="" id="tahun_pertumbuhan_finansial" class="input w-fit">
                            <?php foreach ($tahun_pertumbuhan_finansial as $key => $value) : ?>
                                <option value="<?= $value['tahun']; ?>"><?= $value['tahun']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative">
                        <canvas id="chart_pertumbuhan_finansial" height="100"></canvas>
                    </div>
                    <div class="text center text-gray-800 dark:text-white mx-auto flex gap-5">
                        <div class="flex gap-2">
                            <p>Rata rata:</p><span id="rata_rata_pertumbuhan_finansial">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col lg:flex-row gap-12">
                <div class="flex flex-1 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-4 p-5 h-full">
                    <div class="flex justify-between items-center">
                        <h4>Grafik Pendaftar</h4>
                        <select name="" id="tahun_pertumbuhan_pendaftar" class="input w-fit">
                            <?php foreach ($tahun_pertumbuhan_pendaftar as $key => $value) : ?>
                                <option value="<?= $value['tahun']; ?>"><?= $value['tahun']; ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative">
                        <canvas id="chart_pertumbuhan_user" height="100"></canvas>
                    </div>
                    <div class="text center text-gray-800 dark:text-white mx-auto flex gap-5">
                        <div class="flex gap-2">
                            <p>Total Siswa:</p><span id="total_pendaftar_siswa">0</span>
                        </div>
                        <div class="flex gap-2">
                            <p>Total Instruktur:</p><span id="total_pendaftar_instruktur">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col lg:flex-row items-start gap-12">
                <div class="flex flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5">
                    <div class="flex justify-between items-center">
                        <h4>Pengajuan Siswa</h4>
                    </div>
                    <?php if ($data_pengajuan_siswa->num_rows > 0) : ?>
                        <?php foreach ($data_pengajuan_siswa as $key => $value) : ?>
                            <a href="./pengajuan.php" class="flex flex-col flex-1 rounded-lg p-5 bg-white dark:bg-gray-500 gap-2">
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
                    <?php else : ?>
                        <cite>Tidak ada pengajuan</cite>
                    <?php endif ?>
                </div>

                <div class="flex flex-col lg:flex-row w-full lg:w-fit gap-12">
                    <div class="flex w-full lg:w-80 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5">
                        <div class="flex justify-between items-center">
                            <h4>Kehadiran Siswa</h4>
                        </div>
                        <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative p-16 lg:p-0 border-t-2 lg:border-0">
                            <?php if ($presensi_siswa->num_rows > 0) : ?>
                                <canvas id="chart_kehadiran_siswa_hari_ini"></canvas>
                            <?php else : ?>
                                <cite>Tidak ada presensi siswa</cite>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="flex w-full lg:w-80 flex-col text-gray-800 dark:text-white bg-gray-100 dark:bg-gray-700 rounded-lg space-y-6 p-5">
                        <div class="flex justify-between items-center">
                            <h4>Kehadiran Instruktur</h4>
                        </div>
                        <div class="flex flex-1 bg-gray-100 dark:bg-gray-700 relative p-16 lg:p-0 border-t-2 lg:border-0">
                            <?php if ($presensi_instruktur->num_rows > 0) : ?>
                                <canvas id="chart_kehadiran_instruktur_hari_ini"></canvas>
                            <?php else : ?>
                                <cite>Tidak ada presensi instruktur</cite>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    (async () => {
        const url = window.location.href.replace('client', 'api')

        /**
         * Fungsi untuk mengambil data source grafik
         * @param {String} param
         * @param {String} tahun
         */
        const getSourceData = async (param, tahun) => await $.get(`${url}?${param}=${tahun}`).then(response => response)

        const chartPertumbuhanFinansial = $('#chart_pertumbuhan_finansial')[0].getContext('2d');
        const chartPertumbuhanPendaftar = $('#chart_pertumbuhan_user')[0].getContext('2d');
        const chartKehadiranSiswa = $('#chart_kehadiran_siswa_hari_ini');
        const chartKehadiranInstruktur = $('#chart_kehadiran_instruktur_hari_ini');

        let valueTahunPendaftar = $('select#tahun_pertumbuhan_pendaftar option:selected').text()
        let dataPertumbuhanPendaftar = await getSourceData('pertumbuhan_pendaftar', valueTahunPendaftar) || []
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

        const isDarkMode = $('html').hasClass('dark')
        const color = isDarkMode ? "#fff" : "#1f2937"
        const bulan = <?= json_encode(BULAN) ?>

        const customPluginLegendMargin = {
            id: 'customPluginLegendMargin',
            beforeInit(chart, legend, options) {
                console.log(chart, legend, options)
                const fitValue = chart.legend.fit

                chart.legend.fit = function fit() {
                    fitValue.bind(chart.legend)();
                    return this.height += 30;
                }
            }
        }

        const optionsGrafikFinansial = {
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
                        color
                    },
                    border: {
                        color,
                    },
                },
                y: {
                    ticks: {
                        color,
                        stepSize: 10
                    },
                    grid: {
                        drawOnChartArea: false,
                        color
                    },
                    border: {
                        color,
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        padding: 5,
                        color,
                    }
                }
            },
            elements: {
                point: {
                    hitRadius: 50
                }
            }
        }

        const optionsGrafikPendaftar = {
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
                        color
                    },
                    border: {
                        color,
                    },
                },
                y: {
                    ticks: {
                        color,
                        stepSize: 1
                    },
                    grid: {
                        drawOnChartArea: false,
                        color
                    },
                    border: {
                        color,
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color,
                    }
                }
            },
            elements: {
                point: {
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
        let objChartPertumbuhanFinansial = new Chart(chartPertumbuhanFinansial, {
            type: 'bar',
            data: {
                datasets: [{
                        label: 'Saldo',
                        data: [10, 20, 30]
                    },
                    {
                        label: 'Kredit',
                        data: [50, 80, 20]
                    }
                ],
                labels: ['Mei', 'Juni', 'Juli']
            },
            options: optionsGrafikFinansial,
            plugins: [customPluginLegendMargin]
        });
        gradientChartPertumbuhanSiswa = chartPertumbuhanPendaftar.createLinearGradient(0, 25, 0, 1200);
        gradientChartPertumbuhanSiswa.addColorStop(0, 'rgba(14, 165, 233, .5)');
        gradientChartPertumbuhanSiswa.addColorStop(0.35, 'rgba(14, 165, 233, .25)');
        gradientChartPertumbuhanSiswa.addColorStop(1, 'rgba(14, 165, 233, 1)');

        gradientChartPertumbuhanInstruktur = chartPertumbuhanPendaftar.createLinearGradient(0, 0, 0, 800);
        gradientChartPertumbuhanInstruktur.addColorStop(0, 'rgba(245, 158, 11, .5)');
        gradientChartPertumbuhanInstruktur.addColorStop(0.35, 'rgba(245, 158, 11, .25)');
        gradientChartPertumbuhanInstruktur.addColorStop(1, 'rgba(245, 158, 11, 1)');

        let objChartPertumbuhanPendaftar = new Chart(chartPertumbuhanPendaftar, {
            type: 'line',
            data: {
                datasets: [{
                        label: 'Siswa',
                        fill: true,
                        backgroundColor: gradientChartPertumbuhanSiswa,
                        data: dataPertumbuhanPendaftar.siswa.map(data => ({
                            x: data.bulan,
                            y: data.jumlah_siswa
                        })),
                        pointBackgroundColor: 'rgba(14, 165, 233, 1)',
                        borderColor: 'rgba(14, 165, 233, 1)',
                    },
                    {
                        label: 'Instruktur',
                        fill: true,
                        backgroundColor: gradientChartPertumbuhanInstruktur,
                        data: dataPertumbuhanPendaftar.instruktur.map(data => ({
                            x: data.bulan,
                            y: data.jumlah_instruktur
                        })),
                        pointBackgroundColor: 'rgba(245, 158, 11, 1)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                    }
                ],
                labels: bulan
            },
            options: optionsGrafikPendaftar,
            plugins: [customPluginLegendMargin]
        });

        let jumlahPendaftarSiswa = dataPertumbuhanPendaftar.siswa.reduce((accumulator, currentValue) => accumulator + parseInt(currentValue.jumlah_siswa), 0);
        $('#total_pendaftar_siswa').text(jumlahPendaftarSiswa)

        let jumlahPendaftarInstruktur = dataPertumbuhanPendaftar.instruktur.reduce((accumulator, currentValue) => accumulator + parseInt(currentValue.jumlah_instruktur), 0);
        $('#total_pendaftar_instruktur').text(jumlahPendaftarInstruktur)

        // Event Handler
        $('select#tahun_pertumbuhan_pendaftar').on('change', async function() {
            dataPertumbuhanPendaftar = await getSourceData('pertumbuhan_pendaftar', $(this).val())

            objChartPertumbuhanPendaftar.data.datasets[0].data = dataPertumbuhanPendaftar.siswa.map(data => ({
                x: data.bulan,
                y: data.jumlah_siswa
            }))
            objChartPertumbuhanPendaftar.data.datasets[1].data = dataPertumbuhanPendaftar.instruktur.map(data => ({
                x: data.bulan,
                y: data.jumlah_instruktur
            }))

            objChartPertumbuhanPendaftar.update()

            jumlahPendaftarSiswa = dataPertumbuhanPendaftar.siswa.reduce((accumulator, currentValue) => accumulator + parseInt(currentValue.jumlah_siswa), 0);
            $('#total_pendaftar_siswa').text(jumlahPendaftarSiswa)

            jumlahPendaftarInstruktur = dataPertumbuhanPendaftar.instruktur.reduce((accumulator, currentValue) => accumulator + parseInt(currentValue.jumlah_instruktur), 0);
            $('#total_pendaftar_instruktur').text(jumlahPendaftarInstruktur)
        })

        // Pie Chart
        new Chart(chartKehadiranSiswa, {
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