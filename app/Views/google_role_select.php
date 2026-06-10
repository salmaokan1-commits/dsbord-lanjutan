<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Role - Kuliner Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin: 0; height: 100vh; background: url('<?= base_url('img/bg.jpg') ?>') no-repeat center center; background-size: cover; display: flex; justify-content: center; align-items: center; }
        .overlay { backdrop-filter: blur(6px); background: rgba(255, 255, 255, 0.5); padding: 30px; border-radius: 20px; width: 380px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .title { text-align: center; color: #FF5722; font-weight: bold; margin-bottom: 20px; }
        .btn-orange { background: #FF5722; color: white; border-radius: 6px; border: none; padding: 7px; }
        .btn-orange:hover { background: #e64a19; color: white; }
        .role-options { display: flex; gap: 15px; justify-content: center; margin: 25px 0; }
        .role-option { flex: 1; }
        .role-option input[type="radio"] { display: none; }
        .role-option label { display: block; padding: 12px; text-align: center; border: 2px solid #ddd; border-radius: 6px; cursor: pointer; font-weight: 500; transition: all 0.3s; }
        .role-option input[type="radio"]:checked + label { background: #FF5722; color: white; border-color: #FF5722; }
    </style>
</head>
<body>

<div class="overlay">
    <h3 class="title">Pilih Tipe Akun</h3>

    <p style="text-align: center; font-size: 14px; margin-bottom: 20px;">Silahkan pilih tipe akun yang ingin Anda gunakan</p>

    <form method="post" action="<?= base_url('auth/prosesgoogleRole') ?>">
        <?= csrf_field() ?>

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

        <button type="submit" class="btn btn-orange w-100">Lanjutkan</button>
    </form>

    <div class="text-center mt-3">
        <small>Universitas Dian Nuswantoro</small>
    </div>
</div>
</body>
</html>
