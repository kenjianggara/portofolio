<?php
require __DIR__ . '/data/posts.php';
$POSTS = load_posts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dokumentasi Proyek</title>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="container py-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Project, Labs, and Writeup</h2>
      <p class="text-dark">Dokumentasi singkat dari proyek dan eksperimen yang telah saya buat.</p>
    </div>

    <div class="row g-4">
      <?php foreach ($POSTS as $post): ?>
        <div class="col-md-4">
          <div class="card blog-card h-100">
            <div class="card-body">
              <h5 class="card-title text-light mb-2"><?= htmlspecialchars($post['title']) ?></h5>
              <p class="text-secondary text-light mb-3"><?= htmlspecialchars($post['summary']) ?></p>
              <a href="post.php?id=<?= urlencode($post['id']) ?>" class="btn btn-outline-light">Read</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
