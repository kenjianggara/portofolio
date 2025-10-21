<?php
require __DIR__ . '/data/posts.php';

$id       = isset($_POST['id']) ? trim($_POST['id']) : '';
$title    = trim($_POST['title']   ?? '');
$content  = $_POST['content']      ?? '';
$summary  = $_POST['summary']      ?? null;
$format   = $_POST['format']       ?? 'html';
$date     = $_POST['date']         ?? null;
$readMin  = (int)($_POST['readMinutes'] ?? 3);
$tags     = array_values(array_filter(array_map('trim', explode(',', $_POST['tags'] ?? ''))));

if ($title === '' || trim($content) === '') {
  http_response_code(400);
  exit('Judul dan isi wajib diisi.');
}

if ($id !== '') {
  // UPDATE by slug OR numeric index
  if (!update_post_mixed($id, $title, $content, $format, $summary, $date, $readMin, $tags)) {
    http_response_code(404);
    exit('Post tidak ditemukan.');
  }
} else {
  // CREATE (auto slug id)
  if (!save_post_new($title, $content, $format, $summary, $date, $readMin, $tags)) {
    http_response_code(500);
    exit('Gagal menyimpan post.');
  }
}

header('Location: admin.php');
exit;
