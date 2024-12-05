<?php
require '../news.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->Berita->posts;

$searchQuery = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $searchQuery = [
        '$or' => [
            ['title' => ['$regex' => $search, '$options' => 'i']],
            ['summary' => ['$regex' => $search, '$options' => 'i']],
            ['category' => ['$regex' => $search, '$options' => 'i']]
        ]
    ];
}

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
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .table-container {
            width: 95%;
            max-width: 1400px;
            margin: 20px auto;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        
        .btn-add {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .btn-add:hover {
            background-color: #218838;
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .table-container {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border-radius: 0;
            }
            
            .table-responsive {
                margin-bottom: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }
            
            header {
                font-size: 20px;
                padding: 10px;
            }
            
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
                gap: 5px;
            }
        }

        .table th, .table td {
            min-width: 100px;
        }
        
        .table th:last-child, .table td:last-child {
            min-width: 120px;
        }
        
        .text-truncate {
            max-width: 150px;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<header class="d-flex justify-content-between align-items-center">
    <span>NEWS.ID</span>
    <a href="logout.php" class="btn btn-secondary">Logout</a>
</header>

<div class="container-fluid">
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h4 class="mb-0">List Berita</h4>
            <a href="create.php" class="btn btn-add">+ Tambahkan Berita</a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Konten</th>
                        <th>Ringkasan</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Tanggal Publikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($newsList as $news): ?>
                    <tr>
                        <td><div class="text-truncate"><?php echo htmlspecialchars($news['title']); ?></div></td>
                        <td><div class="text-truncate"><?php echo htmlspecialchars(substr($news['content'], 0, 50)) . '...'; ?></div></td>
                        <td><div class="text-truncate"><?php echo htmlspecialchars($news['summary']); ?></div></td>
                        <td><?php echo htmlspecialchars($news['category']); ?></td>
                        <td><?php echo htmlspecialchars($news['author']); ?></td>
                        <td><?php echo date('d-m-Y H:i:s', $news['created_at']->toDateTime()->getTimestamp()); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?php echo $news['_id']; ?>" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edit</a>
                                <a href="delete.php?id=<?php echo $news['_id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                    <i class="bi bi-trash"></i> Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-3">
            <select class="form-select" style="width: auto;">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select>
            <nav>
                <ul class="pagination mb-0">
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