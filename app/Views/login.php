<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Kuliner Kampus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            background: url('/img/bg.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay {
            backdrop-filter: blur(6px);
            background: rgba(255,255,255,0.6);
            padding: 30px;
            border-radius: 20px;
            width: 380px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .title {
            text-align: center;
            color: #FF5722;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            padding-left: 40px;
        }

        .input-group-text {
            background: transparent;
            border: none;
            position: absolute;
            z-index: 10;
            top: 8px;
            left: 10px;
        }

        .btn-orange {
            background: #FF5722;
            color: white;
            border-radius: 10px;
        }

        .btn-orange:hover {
            background: #e64a19;
        }

        .role-toggle span {
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
        }

        .active-role {
            background: #FF5722;
            color: white;
        }
    </style>
</head>

<body>

<div class="overlay">

    <h3 class="title">Kuliner Kampus</h3>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/login/process">

        <!-- USERNAME / EMAIL -->
        <div class="mb-3 position-relative">
            <span class="input-group-text"></span>
            <input type="text" name="login" class="form-control" placeholder="Username / Email" required>
        </div>

        <!-- PASSWORD -->
        <div class="mb-3 position-relative">
            <span class="input-group-text"></span>
            <input type="password" id="password" name="password" class="form-control" placeholder="Kata Sandi" required>
            <span onclick="togglePassword()" style="position:absolute; right:10px; top:10px; cursor:pointer;"></span>
        </div>

        <!-- ROLE -->
        <div class="text-center mb-3 role-toggle">
            Masuk sebagai:
            <span id="userRole" class="active-role" onclick="setRole('user')">Mahasiswa / User</span>
            |
            <span id="adminRole" onclick="setRole('admin')">Admin</span>
            <input type="hidden" name="role" id="role" value="user">
        </div>

        <!-- BUTTON -->
        <button class="btn btn-orange w-100">Masuk</button>
        <a href="/auth/google" class="btn w-100 mt-3 d-flex align-items-center justify-content-center gap-2" style="background:#fff; border:1px solid #ddd;">
    <img src="https://developers.google.com/identity/images/g-logo.png" width="20">
    <span style="color:#444; font-weight:500;">Masuk dengan Google</span>
        </a>

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

function setRole(role) {
    document.getElementById("role").value = role;

    document.getElementById("userRole").classList.remove("active-role");
    document.getElementById("adminRole").classList.remove("active-role");

    if(role === 'user') {
        document.getElementById("userRole").classList.add("active-role");
    } else {
        document.getElementById("adminRole").classList.add("active-role");
    }
}
</script>

</body>
</html>