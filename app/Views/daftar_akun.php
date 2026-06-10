<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Baru - Kuliner Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin: 0; height: 100vh; background: url('<?= base_url('img/bg.jpg') ?>') no-repeat center center; background-size: cover; display: flex; justify-content: center; align-items: center; }
        .overlay { backdrop-filter: blur(6px); background: rgba(255, 255, 255, 0.5); padding: 30px; border-radius: 20px; width: 380px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .title { text-align: center; color: #FF5722; font-weight: bold; margin-bottom: 20px; }
        .form-control { border-radius: 10px; padding-left: 15px; }
        .btn-orange { background: #FF5722; color: white; border-radius: 6px; border: none; padding: 7px; }
        .btn-orange:hover { background: #e64a19; color: white; }
        .role-options { display: flex; gap: 15px; justify-content: center; margin: 15px 0; }
        .role-option { flex: 1; }
        .role-option input[type="radio"] { display: none; }
        .role-option label { display: block; padding: 8px; text-align: center; border: 2px solid #ddd; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.3s; }
        .role-option input[type="radio"]:checked + label { background: #FF5722; color: white; border-color: #FF5722; }
    </style>
</head>
<body>

<div class="overlay">
    <h3 class="title">Daftar Akun Baru</h3>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" style="font-size: 13px;">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success" style="font-size: 13px;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('auth/prosesDaftar') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email / Gmail" required>
        </div>

        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="mb-3 position-relative">
            <input type="password" id="password" name="password" class="form-control" placeholder="Kata Sandi" required>
            <span onclick="togglePassword()" style="position:absolute; right:10px; top:8px; cursor:pointer;"></span>
        </div>

        <div class="role-options">
            <div class="role-option">
                <input type="radio" id="pengguna" name="role" value="user" required checked>
                <label for="pengguna">Pengguna</label>
            </div>
            <div class="role-option">
                <input type="radio" id="pengelolah" name="role" value="merchant" required>
                <label for="pengelolah">Pengelolah</label>
            </div>
        </div>

        <button type="submit" class="btn btn-orange w-100">Daftar</button>

        <a href="<?= base_url('auth/googleLogin') ?>" class="btn w-100 mt-3 d-flex align-items-center justify-content-center gap-2" style="background:#fff; border:1px solid #ddd; border-radius: 10px;">
            <img src="https://developers.google.com/identity/images/g-logo.png" width="20">
            <span style="color:#444; font-weight:500;">Daftar dengan Google</span>
        </a>

        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="<?= base_url('login') ?>" style="color: #FF5722; text-decoration: none; font-weight: 500;">Masuk di sini</a></small>
        </div>
    </form>

    <div class="text-center mt-3">
        <small>Universitas Dian Nuswantoro</small>
    </div>
</div>

<script>
function togglePassword() {
    let x = document.getElementById("password");
    x.type = x.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
