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
      <?php foreach ($POSTS as $i => $post): ?>
        <?php
        $id      = isset($post['id']) && $post['id'] !== '' ? (string)$post['id'] : (string)$i;
        $title   = htmlspecialchars((string)($post['title'] ?? 'Tanpa Judul'), ENT_QUOTES, 'UTF-8');
        $summary = (string)($post['summary'] ?? '');
        if ($summary === '') {
          $text = ($post['format'] ?? 'plain') === 'html' ? strip_tags((string)($post['content'] ?? '')) : (string)($post['content'] ?? '');
          $text = trim(preg_replace('/\s+/', ' ', $text));
          $summary = mb_strlen($text) > 180 ? (mb_substr($text, 0, 179) . '…') : $text;
        }
        ?>
        <div class="col-md-4">
          <div class="card blog-card h-100">
            <div class="card-body">
              <h5 class="card-title text-light mb-2"><?= $title ?></h5>
              <p class="text-secondary text-light mb-3"><?= htmlspecialchars($summary, ENT_QUOTES, 'UTF-8') ?></p>
              <a href="post.php?id=<?= urlencode($id) ?>" class="btn btn-outline-light">Read</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>