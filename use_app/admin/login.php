<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <!-- Link Bootstrap -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" 
        rel="stylesheet">

    <style>
        /* Header */
header {
    background-color: #000000;
    color: white;
    padding: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.Inews{
    text-align: center;
    padding: 80px;
}
    </style>
</head>

<body class="bg-light">
    <header> 

    </header>
    <div class="Inews">
    <h3>NEWS.ID</h3>
    </div>
    <div class="container d-flex justify-content-center" style="height: 40vh;" >
        <div class="card shadow-sm p-4" style="width: 350px;">
        
        <form id="loginForm" onsubmit="redirectToCreate(event)">
    <!-- Username Field -->
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" placeholder="Masukkan username">
    </div>
    <!-- Password Field -->
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Masukkan password">
    </div>
    <!-- Submit Button -->
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>

<script>
   function redirectToCreate(event) {
        event.preventDefault(); // Mencegah pengiriman form default

            // Ambil nilai input
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            
            // Validasi username
            if (!username) {
                alert('Username harus diisi!');
                return;
            } else if (username !== 'admin') {
                alert('Login ditolak!');
                return;
            }

            // Validasi password
            if (password.length > 6) {
                alert('Password tidak boleh lebih dari 6 karakter!');
                return;
            }

        window.location.href = 'adminpanel.php'; // Mengarahkan ke halaman create.php
    }
</script>

           
        </div>
    </div>

    <!-- Link Bootstrap JS -->
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
    </script>
    
    
</body>
</html>
