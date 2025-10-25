<!DOCTYPE html>
<html lang="id">
<body>
    <form action="index.php?action=handleRegister" method="POST">
        <h2>Register Akun Pelanggan</h2>
        <?php if(isset($error)): ?>
            <p class="error"><?= $error; ?></p>
        <?php endif; ?>
        
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Daftar</button>
        <p>Sudah punya akun? <a href="index.php?action=login">Login di sini</a></p>
    </form>
</body>
</html>