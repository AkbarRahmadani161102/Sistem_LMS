<?php include_once('../template/header.php') ?>

<div class="w-screen h-screen">
    <div class="flex justify-items-center items-center">
        <div class="hidden md:flex w-full h-screen bg-register-siswa bg-cover bg-center bg-no-repeat flex justify-center items-center">
            <div class="max-w-lg">
                <h3 class="text-white display-6 m-0">Bimbel SMART Solution </h3>
                <h5 class="text-white">Memberikan solusi cerdas untuk masa depan</h5>
                <h6 class="mt-5">Hadir untuk memberikan pendidikan yang bermutu dan menginspirasi siswa untuk belajar lebih keras dan mencapai prestasi terbaik.</h6>
            </div>
        </div>
        <div class="w-full flex-col justify-items-center p-5">
            <h3 class="text-center text-gray-400 dark:text-white">Daftar sebagai siswa</h3>
            <form class="max-w-md mx-auto my-5" action="../../api/auth/auth.php" method="post">
                <div class="mb-5 space-y-2">
                    <label for="nama_lengkap" class="form-label text-secondary text-gray-400 dark:text-white">Nama Lengkap</label>
                    <input type="text" class="input" id="nama_lengkap" name="nama" required>
                </div>
                <div class="mb-5 space-y-2">
                    <label for="email" class="form-label text-secondary text-gray-400 dark:text-white">Email address</label>
                    <input type="email" class="input" id="email" name="email" required>
                </div>
                <div class="mb-5 space-y-2">
                    <label for="password" class="form-label text-secondary text-gray-400 dark:text-white">Password</label>
                    <input type="password" class="input" id="password" name="password" required>
                </div>
                <button type="submit" class="btn w-full bg-amber-500 dark:bg-amber-500 dark:text-white" name="register">Register</button>
            </form>
            <p class="text-center text-gray-400 dark:text-white">Sudah memiliki akun? <a class="text-amber-500" href="./login.php">Klik Disini</a></p>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>