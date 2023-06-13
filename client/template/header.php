<?php
include_once "../../config.php";
include_once('../../api/auth/access_control.php');
include_once('../../api/util/db.php');
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/image/icon.png">
    <link rel="stylesheet" href="../assets/css/flowbite.min.css" />
    <link rel="stylesheet" href="../assets/css/sweetalert2.min.css" />
    <link rel="stylesheet" href="../assets/css/selectize.default.min.css" />
    <link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="../assets/css/select.dataTables.min.css" />
    <link rel="stylesheet" href="../assets/css/searchPanes.dataTables.min.css" />
    <link rel="stylesheet" href="../assets/css/output.css" />
    <link rel="stylesheet" href="../assets/icons/remixicon/remixicon.css" />
    <script src="../assets/js/chart.umd.min.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/js/jquery-3.6.4.min.js"></script>
    <script src="../assets/js/selectize.min.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.select.min.js"></script>
    <script src="../assets/js/dataTables.searchPanes.min.js"></script>
    <script>
        function generateConfirmationDialog(url, body) {
            return Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        await $.post(url, body)
                        await Swal.fire(
                            'Terhapus!',
                            'Data berhasil dihapus',
                            'success',
                        )
                    } finally {
                        location.reload()
                    }
                }
            })
        }

        let permission;
        (async () => {
            permission = await Notification.requestPermission();
        })()

        const pushNotification = (sessionKey = 'tglNotifikasiInstruktur', title, body = '', onClickEvent = () => {}) => {
            const icon = 'https://i3.lensdump.com/i/k66AYD.png'

            const isNotYetNotified = !sessionStorage.getItem(sessionKey) || sessionStorage.getItem(sessionKey) != new Date().getDate()
            if (isNotYetNotified && permission === 'granted') {
                sessionStorage.setItem(sessionKey, new Date().getDate())
                new Notification(title, {
                    body,
                    icon
                }).onclick = () => onClickEvent();
            }
        }
    </script>

    <script src="../assets/js/flowbite.min.js" defer></script>
    <script src="../assets/js/script.js" defer></script>

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches))
            document.documentElement.classList.add('dark');
        else
            document.documentElement.classList.remove('dark')
    </script>

    <title>SMART App</title>
</head>

<body class="bg-white dark:bg-gray-800 relative">
    <div id="loading-screen" class="top-0 left-0 w-screen h-screen absolute flex items-center justify-center z-50">
        <div class="w-fit flex items-center gap-5">
            <div role="status">
                <svg aria-hidden="true" class="w-16 h-16 text-gray-200 animate-spin dark:text-transparent fill-white" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor" />
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill" />
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
            <h4 class="text-4xl	text-white">Loading</h4>
        </div>
    </div>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            customClass: {
                popup: 'colored-toast'
            },
            showConfirmButton: false,
            timer: <?= isset($_SESSION['toast']['text']) ? 7000 : 3000 ?>,
            timerProgressBar: true
        })
    </script>

    <?php if (isset($_SESSION['toast'])) : ?>
        <script>
            Toast.fire({
                icon: "<?= $_SESSION['toast']['icon'] ?>",
                title: "<?= $_SESSION['toast']['title'] ?>",
                iconColor: "<?= $_SESSION['toast']['icon_color'] ?>",
                text: "<?= isset($_SESSION['toast']['text']) ? $_SESSION['toast']['text'] : '' ?>",
            })
        </script>
    <?php endif ?>

    <?php unset($_SESSION['toast']); ?>

    <?php if (isset($_SESSION['alert'])) : ?>
        <script>
            Swal.fire({
                icon: "<?= $_SESSION['alert']['icon'] ?>",
                title: "<?= $_SESSION['alert']['title'] ?>",
                showConfirmButton: false,
                timerProgressBar: true,
                timer: <?= isset($_SESSION['alert']['text']) ? 7000 : 3000 ?>
            })
        </script>
    <?php endif ?>

    <?php unset($_SESSION['alert']); ?>