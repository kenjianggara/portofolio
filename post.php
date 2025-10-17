<?php
include __DIR__ . '/data/posts.php';
$id = $_GET['id'] ?? '';
$post = get_post($id);
if (!$post) {
  http_response_code(404);
  die('<div class="text-center mt-5"><h3>404 - Post not found</h3></div>');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title><?= htmlspecialchars($post['title']) ?> | Dokumentasi</title>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <section class="container py-5" style="max-width:900px;">
    <article>
      <header class="mb-4">
        <h2 class="fw-bold"><?= htmlspecialchars($post['title']) ?></h2>
        <p class="text-muted"><?= date('d M Y', strtotime($post['date'])) ?> • <?= $post['readMinutes'] ?> min read</p>
        <?php foreach ($post['tags'] as $t): ?>
          <span class="badge bg-secondary">#<?= htmlspecialchars($t) ?></span>
        <?php endforeach; ?>
      </header>
      <hr>
      <div class="content"><?= $post['content']; ?></div>
      <hr>
      <a href="blog.php" class="btn btn-outline-primary">← Kembali</a>
    </article>
  </section>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>

</html>