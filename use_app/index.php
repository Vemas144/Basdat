<?php
require 'news.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->Berita->posts;

// Ambil kategori dari URL
$category = isset($_GET['category']) ? $_GET['category'] : null;

$searchQuery = [];
if ($category) {
    $searchQuery = [
        'category' => ['$regex' => '^' . preg_quote($category) . '$', '$options' => 'i'] // Filter kategori (case-insensitive)
    ];
}

// Ambil data berita berdasarkan kategori (jika ada)
$newsList = $collection->find($searchQuery, ['sort' => ['created_at' => -1]]);
?>


<!DOCTYPE html>
<html lang="id">
    <head>
        <style>
            .nav-link {
                font-size: 14px;
                padding: 8px 12px;
                transition: background-color 0.3s, color 0.3s;
            }

            .nav-link:hover {
                background-color: #555;
                color: #fff;
            }

            .nav-link.active {
                font-weight: bold;
                border-bottom: 3px solid white;
                background-color: #333;
            }

        </style>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>News.ID</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .hidden {
                display: none !important;
            }

            .nav-link {
                color: #fff;
                font-size: 14px;
                padding: 8px 12px;
            }

            .nav-link.active {
                font-weight: bold;
                border-bottom: 3px solid white;
            }

            .col-md-4 {
                margin-bottom: 16px;
            }
        </style>
    </head>
    <body>
        <header class="bg-dark text-white p-3">
            <div class="container d-flex justify-content-between align-items-center">
                <h1 class="mb-0">NEWS.ID</h1>
                <!-- Form Pencarian -->
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control w-100" placeholder="Cari berita">
                    <a href="admin/login.php" class="btn btn-success">Login</a>
                </div>
            </div>
            <nav class="container mt-3">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo empty($category) ? 'active' : ''; ?>" href="index.php">Semua
                            Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $category == 'Politik' ? 'active' : ''; ?>"
                           href="index.php?category=Politik">Politik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $category == 'Olahraga' ? 'active' : ''; ?>"
                           href="index.php?category=Olahraga">Olahraga</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $category == 'Ekonomi' ? 'active' : ''; ?>"
                           href="index.php?category=Ekonomi">Ekonomi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $category == 'Teknologi' ? 'active' : ''; ?>"
                           href="index.php?category=Teknologi">Teknologi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $category == 'Kesehatan' ? 'active' : ''; ?>"
                           href="index.php?category=Kesehatan">Kesehatan</a>
                    </li>
                </ul>
            </nav>

        </header>

        <main class="container my-4">
            <div class="row">
                <?php if ($newsList->isDead()): ?>
                    <p class="text-center">Tidak ada berita ditemukan untuk kategori
                        "<?php echo is_null($category) ? 'ini' : htmlspecialchars($category); ?>".</p>
                <?php else: ?>
                    <?php foreach ($newsList as $news): ?>
                        <div class="col-md-4 searchable-card">
                            <div class="card">

<!--                            <div style="aspect-ratio: 1/1; width: 100%; overflow: hidden; position: relative">-->
<!--                               <img src="--><?php
//                                  if (isset($news['image']) && $news['image'] instanceof MongoDB\BSON\Binary) {
//                                      echo 'data:image/jpeg;base64,' . base64_encode($news['image']->getData());
//                                  } else {
//                                      echo 'default.jpg'; // Fallback image if binary data is not found
//                                  }?><!--"  class="card-img-top" style="width: 100%; height: 100%; object-fit: cover; position: absolute;" alt="Gambar Berita">-->
<!--                            </div>-->

                                <img src="<?php if (isset($news['image']) && $news['image'] instanceof MongoDB\BSON\Binary) {
                                    echo 'data:image/jpeg;base64,' . base64_encode($news['image']->getData());
                                } else {
                                    echo 'default.jpg';
                                } ?>"
                                 class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;" alt="Gambar Berita">

                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="detail.php?berita=<?php echo $news['_id']; ?>"
                                           class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($news['title']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($news['summary'], 0, 100)) . '...'; ?></p>
                                    <p class="text-muted small">
                                        <?php echo date('d-m-Y H:i:s', $news['created_at']->toDateTime()->getTimestamp()); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>

        <script>
            const searchInput = document.getElementById("searchInput");
            const cards = document.querySelectorAll(".searchable-card");

            searchInput.addEventListener("input", function () {
                const keyword = searchInput.value.toLowerCase();
                cards.forEach(card => {
                    const title = card.querySelector(".card-title").textContent.toLowerCase();
                    const text = card.querySelector(".card-text").textContent.toLowerCase();
                    // Sembunyikan jika tidak mengandung kata kunci
                    card.closest(".col-md-4").classList.toggle("hidden", !(title.includes(keyword) || text.includes(keyword)));
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
