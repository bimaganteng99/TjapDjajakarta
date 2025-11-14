<!DOCTYPE html>
<html lang="id">

<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>
    <div class="auth-container">
        <form action="index.php?action=handleRegister" method="POST">
            <h2>Register Akun Pelanggan</h2>

            <?php if (isset($error)): ?>
                <p class="error"><?= $error; ?></p>
            <?php endif; ?>

            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <button type="submit">Daftar</button>

            <p class="bottom-text">
                Sudah punya akun? <a href="index.php?action=login">Login di sini</a>
            </p>
        </form>
    </div>
</body>

</html>