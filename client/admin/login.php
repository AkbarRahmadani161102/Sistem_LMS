<?php include_once('../template/header.php') ?>

<div class="w-screen h-screen">
    <div class="flex justify-items-center items-center">
        <div class="w-full h-screen bg-login-admin bg-cover bg-center bg-no-repeat hidden md:block">
        </div>
        <div class="w-full flex-col justify-items-center p-5">
            <h3 class="text-center text-gray-400 dark:text-white">Login sebagai admin</h3>
            <form class="max-w-md mx-auto my-5" action="../../api/auth/auth.php" method="post">
                <div class="mb-5 space-y-2">
                    <label for="email" class="text-secondary text-gray-400 dark:text-white">Email</label>
                    <input type="email" class="input" id="email" name="email" required>
                </div>
                <div class="mb-5 space-y-2">
                    <label for="password" class="text-secondary text-gray-400 dark:text-white">Password</label>
                    <input type="password" class="input" id="password" name="password" required>
                </div>
                <hr>
                <div class="flex flex-col gap-5 pt-4">
                    <input type="hidden" name="role" value="admin">
                    <button type="submit" class="btn w-full bg-amber-500 dark:bg-amber-500 dark:text-white" name="login">Login</button>
                    <a href="../../index.php" class="btn text-center">Kembali</a>
                </div>
            </form>
            <p class="text-center text-gray-400 dark:text-white">Untuk mendaftar silahkan ajukan pendaftaran <span class="text-amber-500">onsite</span></p>
        </div>
    </div>
</div>

<?php include_once('../template/footer.php') ?>