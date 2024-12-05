<?php
require 'news.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->Berita->posts;

// Ambil ID berita dari parameter query string
$beritaId = $_GET['berita'] ?? null;

$news = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($beritaId)]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Berita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <header class="bg-dark text-white p-3">
    <div class="container">
      <h1 class="mb-0">NEWS.ID</h1>
    </div>
  </header>

  <main class="container my-4">
    <?php if ($news): ?>
      <h1 class="mb-3"><?php echo htmlspecialchars($news['title']); ?></h1>
      <p class="text-muted"><?php echo date('d-m-Y H:i:s', $news['created_at']->toDateTime()->getTimestamp()); ?></p>
      <img src="<?php
      if (isset($news['image']) && $news['image'] instanceof MongoDB\BSON\Binary) {
          echo 'data:image/jpeg;base64,' . base64_encode($news['image']->getData());
      } else {
          echo 'default.jpg'; // Fallback image if binary data is not found
      }
      ?>" class="img-fluid mb-4" alt="Gambar Berita">
      <p class="lead"><?php echo htmlspecialchars($news['summary']); ?></p>
      <div><?php echo $news['content']; ?></div>
    <?php else: ?>
      <h1>Berita tidak ditemukan</h1>
    <?php endif; ?>
    <a href="index.php" class="btn btn-primary mt-4">Kembali ke Beranda</a>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>