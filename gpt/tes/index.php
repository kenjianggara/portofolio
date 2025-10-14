<?php
require __DIR__ . '/data/posts.php';
$allTags = all_tags($POSTS);

// Query params
$q    = isset($_GET['q'])   ? trim($_GET['q']) : '';
$tag  = isset($_GET['tag']) ? trim($_GET['tag']) : '';
switch ($_GET['sort'] ?? 'newest') {
  case 'oldest': $sort = 'oldest'; break;
  case 'title':  $sort = 'title';  break;
  default:       $sort = 'newest';
}
$page = max(1, intval($_GET['page'] ?? 1));
$PAGE_SIZE = 6;

// Filter
$filtered = array_filter($POSTS, function($p) use ($q, $tag) {
  $qok = $q === '' || str_contains(mb_strtolower($p['title'].$p['summary'].implode(' ',$p['tags'])), mb_strtolower($q));
  $tok = $tag === '' || in_array($tag, $p['tags']);
  return $qok && $tok;
});

// Sort
usort($filtered, function($a,$b) use ($sort){
  if ($sort==='title') return strcasecmp($a['title'],$b['title']);
  if ($sort==='oldest') return strcmp($a['date'],$b['date']);
  return strcmp($b['date'],$a['date']); // newest
});

$total = count($filtered);
$totalPages = max(1, (int)ceil($total / $PAGE_SIZE));
$page = min($page, $totalPages);
$start = ($page-1)*$PAGE_SIZE;
$items = array_slice($filtered, $start, $PAGE_SIZE);

include __DIR__ . '/partials/header.php';
?>

<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between gap-3 mb-3">
  <div>
    <h1 class="h3 mb-1">Dokumentasi Proyek</h1>
    <div class="text-secondary">Catatan teknis, rilis fitur, dan write‑up lab — format blog.</div>
  </div>
  <form class="d-flex gap-2" role="search">
    <input type="text" class="form-control" name="q" placeholder="Cari (judul, ringkas, tag)" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>" />
    <select class="form-select" name="sort">
      <option value="newest" <?php if($sort==='newest') echo 'selected'; ?>>Newest</option>
      <option value="oldest" <?php if($sort==='oldest') echo 'selected'; ?>>Oldest</option>
      <option value="title"  <?php if($sort==='title')  echo 'selected'; ?>>Title (A→Z)</option>
    </select>
    <?php if ($tag!==''): ?><input type="hidden" name="tag" value="<?php echo htmlspecialchars($tag, ENT_QUOTES); ?>"><?php endif; ?>
    <button class="btn btn-primary" type="submit">Search</button>
  </form>
</div>

<!-- Tag bar -->
<div class="d-flex flex-wrap gap-2 mb-3">
  <?php foreach ($allTags as $t): ?>
    <?php $active = ($t === $tag); ?>
    <a class="btn btn-sm <?php echo $active ? 'btn-dark' : 'btn-outline-secondary'; ?> rounded-pill" href="?<?php
      $qp = $_GET; $qp['tag']=$t; $qp['page']=1; echo http_build_query($qp);
    ?>">#<?php echo htmlspecialchars($t); ?></a>
  <?php endforeach; ?>
  <?php if ($tag!==''): ?>
    <a class="btn btn-sm btn-outline-danger rounded-pill" href="?<?php $qp = $_GET; unset($qp['tag']); $qp['page']=1; echo http_build_query($qp); ?>">Reset tag</a>
  <?php endif; ?>
</div>

<div class="text-secondary small mb-3">Menampilkan <span class="fw-medium"><?php echo $total; ?></span> dokumen</div>

<!-- List -->
<div class="row g-3">
  <?php if (empty($items)): ?>
    <div class="col-12 text-secondary">Tidak ada hasil. Coba ubah pencarian atau tag.</div>
  <?php endif; ?>

  <?php foreach ($items as $p): ?>
    <div class="col-12 col-md-6 col-lg-4">
      <article class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-start justify-content-between">
            <h2 class="h6 mb-1"><a class="stretched-link text-decoration-none" href="/post.php?id=<?php echo urlencode($p['id']); ?>"><?php echo htmlspecialchars($p['title']); ?></a></h2>
            <span class="badge rounded-pill bg-<?php echo $p['status']==='Published'?'success':'warning'; ?>-subtle text-<?php echo $p['status']==='Published'?'success':'warning'; ?>-emphasis border border-<?php echo $p['status']==='Published'?'success':'warning'; ?>-subtle"><?php echo htmlspecialchars($p['status']); ?></span>
          </div>
          <div class="text-secondary small mb-2">
            <?php echo date('d M Y', strtotime($p['date'])); ?> • <?php echo (int)$p['readMinutes']; ?> min read
          </div>
          <p class="mb-3 flex-grow-1"><?php echo htmlspecialchars($p['summary']); ?></p>
          <div class="d-flex flex-wrap gap-1">
            <?php foreach ($p['tags'] as $t): ?>
              <a class="badge text-bg-light border" href="?<?php $qp=$_GET; $qp['tag']=$t; $qp['page']=1; echo http_build_query($qp); ?>">#<?php echo htmlspecialchars($t); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      </article>
    </div>
  <?php endforeach; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<nav class="mt-4" aria-label="Pagination">
  <ul class="pagination justify-content-center">
    <li class="page-item <?php if($page<=1) echo 'disabled'; ?>">
      <a class="page-link" href="?<?php $qp=$_GET; $qp['page']=max(1,$page-1); echo http_build_query($qp); ?>">Prev</a>
    </li>
    <li class="page-item disabled"><span class="page-link">Page <?php echo $page; ?> / <?php echo $totalPages; ?></span></li>
    <li class="page-item <?php if($page>=$totalPages) echo 'disabled'; ?>">
      <a class="page-link" href="?<?php $qp=$_GET; $qp['page']=min($totalPages,$page+1); echo http_build_query($qp); ?>">Next</a>
    </li>
  </ul>
</nav>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>