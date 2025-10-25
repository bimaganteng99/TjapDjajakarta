<!DOCTYPE html>

<head>
    <title>Login</title>
</head>

<body>
    <form action="index.php?action=handleLogin" method="POST">
        <h2>Login sek</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['status']) && $_GET['status'] == 'reg_success'): ?>
            <p class="success">Registrasi berhasil! Silakan login.</p>
        <?php endif; ?>

        <div>
            <label for="login_identifier">Email/Username</label>
            <input type="text" id="login_identifier" name="login_identifier" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
        <p>Belum punya akun? <a href="index.php?action=register">Daftar di sini</a></p>
    </form>
</body>