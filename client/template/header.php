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
    <link rel="stylesheet" href="../assets/css/output.css" />
    <link rel="stylesheet" href="../assets/icons/remixicon/remixicon.css" />
    <script src="../assets/js/chart.umd.min.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/js/jquery-3.6.4.min.js"></script>
    <script src="../assets/js/selectize.min.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
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

<body class="bg-white dark:bg-gray-800">

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            customClass: {
                popup: 'colored-toast'
            },
            showConfirmButton: false,
            timer: 3000,
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
                timer: 3000
            })
        </script>
    <?php endif ?>

    <?php unset($_SESSION['alert']); ?>