<?php
    require '../news.php'; // Koneksi ke database

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = getDB(); 
        $collection = $db->posts; 
        $insertResult = $collection->insertOne([
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'summary' => $_POST['summary'],
            'author' => $_POST['author'],   
            'category' => $_POST['category'],
            'image' => new MongoDB\BSON\Binary(file_get_contents($_FILES['image']['tmp_name']), MongoDB\BSON\Binary::TYPE_GENERIC),
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime(), 
        ]);
        header("Location: adminpanel.php"); 
        exit();
    }
    ?>
  <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Postingan Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #000;
            color: white;
            padding: 15px;
            font-weight: bold;
            font-size: 24px;
        }
        .form-container {
            margin-top: 20px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<header>NEWS.ID</header>

<div class="container mt-4">
    <div class="form-container">
        <form method="post" enctype="multipart/form-data">
            <h2 class="mb-4">Input Berita</h2>
            
            <!-- Judul -->
            <div class="mb-3" >
                <label for="title" class="form-label">Judul *</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Masukkan Judul Berita" required>
            </div>
            
            <!-- Konten -->
            <div class="mb-3">
                <label for="content" class="form-label">Konten *</label>
                <textarea class="form-control" id="content" name="content" rows="3" placeholder="Isi Konten Berita" required></textarea>
            </div>
            
            <!-- Ringkasan -->
            <div class="mb-3">
                <label for="summary" class="form-label">Ringkasan *</label>
                <input type="text" class="form-control" id="summary" name="summary" placeholder="Ringkasan Berita" required>
            </div>
            
            <!-- Kategori dan Penulis -->
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="category" class="form-label">Kategori *</label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Politik">Politik</option>
                        <option value="Olahraga">Olahraga</option>
                        <option value="Ekonomi">Ekonomi</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Kesehatan">Kesehatan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="author" class="form-label">Penulis *</label>
                    <input type="text" id="author" name="author" class="form-control" placeholder="Nama Penulis" required>
                </div>
            </div>
            
            <!-- Upload Gambar -->
            <div class="mb-3 mt-3">
                <label for="image" class="form-label">Image *</label>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png" >
            </div>

            <!-- Tombol -->
            <div class="d-flex justify">
                <a href="adminpanel.php" class="btn btn-secondary">Batal</a>
                <div class="ms-2">
                <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>