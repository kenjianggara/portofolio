<?php include __DIR__ . '/data/posts.php';
$POSTS = load_posts(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dokumentasi Proyek</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include './partials/header.php'; ?>

    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Project, Labs, and Writeup</h2>
            <p class="text-light">Dokumentasi singkat dari proyek dan eksperimen yang telah saya buat.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($POSTS as $p): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="post.php?id=<?= urlencode($p['id']) ?>" class="text-decoration-none"><?= htmlspecialchars($p['title']) ?></a>
                            </h5>
                            <p class="text-muted mb-1"><?= date('d M Y', strtotime($p['date'])) ?> • <?= (int)$p['readMinutes'] ?> min read</p>
                            <p class="card-text mt-2 mb-3"><?= htmlspecialchars($p['summary']) ?></p>
                            <?php foreach ($p['tags'] as $t): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($t) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include './partials/footer.php'; ?>
</body>

</html>