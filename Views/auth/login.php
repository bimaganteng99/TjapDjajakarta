<!DOCTYPE html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>
    <div class="auth-container">
        <form action="index.php?action=handleLogin" method="POST">
            <h2>Login sek</h2>

            <?php if (isset($error)): ?>
                <p class="error"><?= $error; ?></p>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'reg_success'): ?>
                <p class="success">Registrasi berhasil! Silakan login.</p>
            <?php endif; ?>

            <input type="text" id="login_identifier" name="login_identifier" placeholder="Email/Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>

            <p class="bottom-text">
                Belum punya akun? <a href="index.php?action=register">Daftar di sini</a>
            </p>
        </form>
    </div>
</body>

</html>