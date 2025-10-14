<?php
require __DIR__ . '/data/posts.php';
$id = $_GET['id'] ?? '';
$post = null;
foreach($POSTS as $p){ if($p['id']===$id){ $post=$p; break; } }
if(!$post){ http_response_code(404); echo '<!doctype html><title>Not found</title><p>Post tidak ditemukan.</p>'; exit; }
include __DIR__ . '/partials/header.php';
?>

<article class="mx-auto" style="max-width: 860px">
  <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
    <h1 class="h3 mb-0"><?php echo htmlspecialchars($post['title']); ?></h1>
    <span class="badge rounded-pill bg-<?php echo $post['status']==='Published'?'success':'warning'; ?>-subtle text-<?php echo $post['status']==='Published'?'success':'warning'; ?>-emphasis border border-<?php echo $post['status']==='Published'?'success':'warning'; ?>-subtle"><?php echo htmlspecialchars($post['status']); ?></span>
  </div>
  <div class="text-secondary small mb-3">
    <?php echo date('d M Y', strtotime($post['date'])); ?> • <?php echo (int)$post['readMinutes']; ?> min read
  </div>

  <div class="mb-3 d-flex flex-wrap gap-1">
    <?php foreach ($post['tags'] as $t): ?>
      <a class="badge text-bg-light border" href="/index.php?tag=<?php echo urlencode($t); ?>">#<?php echo htmlspecialchars($t); ?></a>
    <?php endforeach; ?>
    <button class="btn btn-sm btn-outline-secondary ms-auto" id="copyLinkBtn" data-id="<?php echo htmlspecialchars($post['id']); ?>">Copy link</button>
  </div>

  <div class="content lead">
    <?php echo $post['content']; ?>
  </div>

  <hr class="my-4">
  <a href="/index.php" class="btn btn-outline-primary">← Kembali</a>
</article>

<?php include __DIR__ . '/partials/footer.php'; ?>