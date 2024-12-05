<?php
require '../news.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->Berita->posts;

// Cek apakah ada parameter pencarian
$searchQuery = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $searchQuery = [
        '$or' => [
            ['title' => ['$regex' => $search, '$options' => 'i']], // Cari di judul
            ['summary' => ['$regex' => $search, '$options' => 'i']], // Cari di ringkasan
            ['category' => ['$regex' => $search, '$options' => 'i']] // Cari di kategori
        ]
    ];
}

// Ambil data berdasarkan pencarian
$newsList = $collection->find($searchQuery, ['sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
        .table-container {
            max-width: 90%; /* Ubah lebar container menjadi 90% dari viewport */
            margin: auto; /* Tengahkan container */
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-add {
            background-color: #28a745;
            color: white;
            font-weight: bold;
        }
        .btn-add:hover {
            background-color: #218838;
        }
        .table-striped td {
            vertical-align: middle;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
<header class="d-flex justify-content-between align-items-center">
    <span>NEWS.ID</span>
    <a href="logout.php" class="btn btn-secondary">Logout</a>
</header>
<div class="container-fluid mt-5">
    <div class="table-container">
        <div class="d-flex justify-content-between mb-3">
            <h4>List Berita</h4>
            <a href="create.php" class="btn btn-add">+ Tambahkan Berita</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    
                    <th style="width: 20%;">Judul</th>
                    <th style="width: 25%;">Konten</th>
                    <th style="width: 15%;">Ringkasan</th>
                    <th style="width: 10%;">Kategori</th>
                    <th style="width: 10%;">Penulis</th>
                    <th style="width: 15%;">Tanggal Publikasi</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($newsList as $news): ?>
                <tr>
                    <td class="text-truncate" style="max-width: 200px;">
                        <?php echo htmlspecialchars($news['title']); ?>
                    </td>
                    <td class="text-truncate" style="max-width: 300px;">
                        <?php echo htmlspecialchars(substr($news['content'], 0, 50)) . '...'; ?>
                    </td>
                    <td class="text-truncate" style="max-width: 150px;">
                        <?php echo htmlspecialchars($news['summary']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($news['category']); ?></td>
                    <td><?php echo htmlspecialchars($news['author']); ?></td>
                    <td><?php echo date('d-m-Y H:i:s', $news['created_at']->toDateTime()->getTimestamp()); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $news['_id']; ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit</a>
                        <a href="delete.php?id=<?php echo $news['_id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                            <i class="bi bi-trash"></i> Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-between mt-3">
            <select class="form-select w-auto">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select>
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">10</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>