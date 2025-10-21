<?php
require __DIR__ . '/data/posts.php';
$POSTS = load_posts();

$editId = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$post   = $editId !== null ? ($POSTS[$editId] ?? null) : null;

$title   = $post['title']   ?? '';
$summary = $post['summary'] ?? '';
$content = $post['content'] ?? '';
$format  = $post['format']  ?? 'html';
$readMinutes = (int)($post['readMinutes'] ?? 3);
$tagsArr     = isset($post['tags']) && is_array($post['tags']) ? $post['tags'] : [];
$tagsCsv     = implode(', ', $tagsArr);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title><?= $editId !== null ? 'Edit' : 'Buat' ?> Artikel</title>
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./style.css">
</head>

<body>
  <?php include __DIR__ . '/partials/header.php'; ?>

  <main class="container py-5" style="max-width: 900px;">
    <div class="card shadow-sm border-0 rounded-4">
      <div class="card-body p-4">
        <h1 class="h3 mb-4 text-dark"><?= $editId !== null ? 'Edit' : 'Buat' ?> Artikel</h1>

        <form action="save_post.php" method="post" class="needs-validation" novalidate>
          <?php if ($editId !== null): ?>
            <input type="hidden" name="id" value="<?= (int)$editId ?>">
          <?php endif; ?>
          <input type="hidden" name="format" value="html">

          <!-- Judul -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Judul</label>
            <input type="text" name="title" class="form-control rounded-3" placeholder="Judul" required
              value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
            <div class="invalid-feedback">Judul wajib diisi.</div>
          </div>

          <!-- Ringkasan -->
          <div class="mb-3">
            <div id="d-flex justify-content-between">
              <label class="form-label fw-semibold">Ringkasan (opsional)</label>
              <small class="text-muted"><span id="sumCount">0</span>/180</small>
            </div>
            <input type="text" name="summary" id="summary" maxlength="180"
              class="form-control rounded-3" placeholder="Ringkasan singkat"
              value="<?= htmlspecialchars($summary ?? '', ENT_QUOTES, 'UTF-8') ?>">
          </div>

          <!-- Row: Waktu Baca + Tags -->
          <div class="row g-3 mb-3">
            <div class="col-sm-4">
              <label class="form-label fw-semibold">Waktu baca (menit)</label>
              <input type="number" name="readMinutes" min="1" step="1"
                class="form-control rounded-3"
                value="<?= max(1, $readMinutes) ?>" required>
              <div class="form-text text-muted">Perkiraan waktu pembaca.</div>
            </div>
            <div class="col-sm-8">
              <label class="form-label fw-semibold">Tags</label>
              <input type="text" name="tags" id="tags" class="form-control rounded-3"
                placeholder="Pisahkan dengan koma, contoh: proxmox, homelab, aws"
                value="<?= htmlspecialchars($tagsCsv, ENT_QUOTES, 'UTF-8') ?>">
              <div class="form-text">Preview: <span id="tagsPreview"></span></div>
            </div>
          </div>

          <!-- Konten -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Isi Artikel</label>
            <textarea id="content" name="content"><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>

          <!-- Tombol aksi -->
          <div class="d-flex justify-content-end gap-2">
            <a href="admin.php" class="btn btn-outline-secondary rounded-3">Batal</a>
            <button type="submit" class="btn btn-primary rounded-3">
              <?= $editId !== null ? 'Simpan Perubahan' : 'Simpan Artikel' ?>
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // CKEditor
    ClassicEditor.create(document.querySelector('#content'), {
      toolbar: [
        'undo', 'redo', '|',
        'heading', '|',
        'bold', 'italic', 'underline', 'link', '|',
        'bulletedList', 'numberedList', 'blockQuote', '|',
        'insertTable', 'codeBlock'
      ]
    }).catch(console.error);

    // Summary counter
    const sum = document.getElementById('summary');
    const sumCount = document.getElementById('sumCount');

    function updateSum() {
      sumCount.textContent = (sum.value || '').length;
    }
    sum.addEventListener('input', updateSum);
    updateSum();

    // Tags preview (badge)
    const tagsInput = document.getElementById('tags');
    const tagsPreview = document.getElementById('tagsPreview');

    function renderTags() {
      const parts = (tagsInput.value || '')
        .split(',').map(s => s.trim()).filter(Boolean).slice(0, 20);
      tagsPreview.innerHTML = parts.map(t => (
        `<span class="badge text-bg-secondary me-1 mb-1">${t.replace(/[<>&"]/g, '')}</span>`
      )).join('');
    }
    tagsInput.addEventListener('input', renderTags);
    renderTags();

    // Bootstrap validation
    (() => {
      const form = document.querySelector('.needs-validation');
      form.addEventListener('submit', (e) => {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    })();
  </script>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>