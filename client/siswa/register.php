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
        <div class="w-full flex-col justify-items-center mt-16 md:m-0">
            <h3 class="text-center text-gray-400 dark:text-white">Daftar sebagai siswa</h3>
            <form class="max-w-md mx-auto my-5" action="../../api/auth/auth.php" method="post">
                <div class="mb-5">
                    <label for="nama_lengkap" class="form-label text-secondary text-gray-400 dark:text-white">Nama Lengkap</label>
                    <input type="text" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="nama_lengkap" name="nama" required>
                </div>
                <div class="mb-5">
                    <label for="email" class="form-label text-secondary text-gray-400 dark:text-white">Email address</label>
                    <input type="email" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="email" name="email" required>
                </div>
                <div class="mb-5">
                    <label for="password" class="form-label text-secondary text-gray-400 dark:text-white">Password</label>
                    <input type="password" class="border rounded w-full py-1.5 border-gray-400 mt-1" id="password" name="password" required>
                </div>
                <button type="submit" class="w-full bg-orange-500 rounded py-1.5 text-white" name="register">Register</button>
            </form>
            <p class="text-center text-gray-400 dark:text-white">Sudah memiliki akun? <a class="text-orange-500" href="./login.php">Klik Disini</a></p>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>