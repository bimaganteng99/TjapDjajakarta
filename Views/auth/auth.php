<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Auth</title>
    <link rel="stylesheet" href="assets/css/authstyle.css">
</head>

<body>

    <div class="container" id="container">

        <!-- REGISTER (SIGN UP) -->
        <div class="form-container sign-up-container">
            <form action="index.php?action=handleRegister" method="POST">
                <h1>Daftar Akun</h1>

                <?php if (isset($error)): ?>
                    <p class="error"><?= $error; ?></p>
                <?php endif; ?>

                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Daftar</button>
            </form>
        </div>

        <!-- LOGIN (SIGN IN) -->
        <div class="form-container sign-in-container">
            <form action="index.php?action=handleLogin" method="POST">
                <h1>Login</h1>

                <?php if (isset($error)): ?>
                    <p class="error"><?= $error; ?></p>
                <?php endif; ?>

                <?php if (isset($_GET['status']) && $_GET['status'] == 'reg_success'): ?>
                    <p class="success">Registrasi berhasil! Silakan login.</p>
                <?php endif; ?>

                <input type="text" name="login_identifier" placeholder="Email / Username" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit">Login</button>
            </form>
        </div>

        <!-- OVERLAY -->
        <div class="overlay-container">
            <div class="overlay">

                <!-- SWITCH KE LOGIN -->
                <div class="overlay-panel overlay-left">
                    <h1>Sudah Punya Akun?</h1>
                    <p>Login menggunakan akunmu di sini</p>
                    <button class="ghost" id="signIn">Login</button>
                </div>

                <!-- SWITCH KE REGISTER -->
                <div class="overlay-panel overlay-right">
                    <h1>Belum Punya Akun?</h1>
                    <p>Buat akun baru untuk mulai menggunakan sistem</p>
                    <button class="ghost" id="signUp">Daftar</button>
                </div>

            </div>
        </div>

    </div>

    <!-- JS UNTUK SLIDER -->
    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
    </script>

</body>

</html>