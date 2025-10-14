<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Personal Website</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('./partials/header.php');?>

<!-- blog page - project,labs, and writeup-->
    <?php include('./data/posts.php'); ?>

    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Project, Labs, and Writeup</h2>
            <p class="text-dark">Dokumentasi singkat dari proyek, eksperimen, dan catatan pembelajaran saya.</p>
        </div>

        <!-- Search and Sort -->
        <form class="row g-2 justify-content-center mb-4">
            <div class="col-md-4 col-10">
                <input type="text" name="q" class="form-control" placeholder="Cari dokumentasi..."
                    value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </div>
            <div class="col-md-2 col-10">
                <select name="sort" class="form-select">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="title">Title (A–Z)</option>
                </select>
            </div>
            <div class="col-md-1 col-6">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <!-- List Post -->
        <div class="row g-4">
            <?php
            $query = $_GET['q'] ?? '';
            $sort = $_GET['sort'] ?? 'newest';

            // Filter posts
            $filtered = array_filter($POSTS, function($p) use ($query) {
                return stripos($p['title'].$p['summary'], $query) !== false;
            });

            // Sort posts
            usort($filtered, function($a, $b) use ($sort) {
                if ($sort === 'title') return strcmp($a['title'], $b['title']);
                if ($sort === 'oldest') return strcmp($a['date'], $b['date']);
                return strcmp($b['date'], $a['date']); // newest
            });

            foreach ($filtered as $p):
            ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">
                            <a href="post.php?id=<?= urlencode($p['id']) ?>" class="stretched-link text-decoration-none">
                                <?= htmlspecialchars($p['title']) ?>
                            </a>
                        </h5>
                        <small class="text-muted"><?= date('d M Y', strtotime($p['date'])) ?> • <?= $p['readMinutes'] ?> min read</small>
                        <p class="card-text mt-2 mb-3"><?= htmlspecialchars($p['summary']) ?></p>
                        <div class="mt-auto">
                            <?php foreach ($p['tags'] as $tag): ?>
                                <span class="badge bg-light text-dark border">#<?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($filtered)): ?>
            <div class="col-12 text-center text-muted">Tidak ada hasil ditemukan.</div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include('./partials/footer.php');?>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>