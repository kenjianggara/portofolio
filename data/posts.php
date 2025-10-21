<?php
// Pakai config prod kalau ada, kalau tidak pakai config.dev.php (lokal)
$prod = __DIR__ . '/../etc/config.php';     // kalau nanti kamu taruh di /etc/… server, ganti path ini
$dev  = __DIR__ . '/config.dev.php';
$config = is_file($prod) ? require $prod : require $dev;

define('POSTS_FILE', $config['posts_file']);
define('POSTS_HMAC', $config['posts_hmac_file']);
define('SECRET_KEY', $config['secret_key']);

function load_posts()
{
  if (!file_exists(POSTS_FILE)) return [];
  $raw = @file_get_contents(POSTS_FILE);
  if ($raw === false) return [];

  if (file_exists(POSTS_HMAC)) {
    $expected = trim((string)@file_get_contents(POSTS_HMAC));
    $calc = hash_hmac('sha256', $raw, SECRET_KEY);
    if ($expected !== '' && !hash_equals($calc, $expected)) {
      error_log('WARNING: HMAC mismatch');
    }
  }
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

/** Cari index berdasarkan id string (slug). */
function find_index_by_id(string $id, array $posts): int
{
  foreach ($posts as $i => $p) {
    if (($p['id'] ?? '') === $id) return $i;
  }
  return -1;
}

/** Ambil post by id (slug string) ATAU index angka. */
function get_post($id)
{
  $posts = load_posts();
  // numeric index?
  if (ctype_digit((string)$id)) {
    $i = (int)$id;
    return $posts[$i] ?? null;
  }
  // slug string
  $j = find_index_by_id((string)$id, $posts);
  return $j >= 0 ? $posts[$j] : null;
}

function save_posts($posts)
{
  usort($posts, function ($a, $b) {
    return strcmp($b['date'] ?? '', $a['date'] ?? '');
  });
  $json = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  if ($json === false) return false;

  // tulis data + HMAC
  if (@file_put_contents(POSTS_FILE, $json, LOCK_EX) === false) return false;
  $hmac = hash_hmac('sha256', $json, SECRET_KEY);
  @file_put_contents(POSTS_HMAC, $hmac, LOCK_EX);
  return true;
}

/** Simpan seluruh array posts ke file. */
function save_all_posts(array $posts): bool
{
  $json = json_encode(array_values($posts), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  if (@file_put_contents(POSTS_FILE, $json, LOCK_EX) === false) return false;
  // (kalau ada HMAC di versimu, lanjutkan tulis HMAC di sini)
  return true;
}

/** Update post by id (slug) atau index angka. */
function update_post_mixed($id, string $title, string $content, string $format = 'html', ?string $summary = null, ?string $date = null, int $readMinutes = 3, array $tags = []): bool
{
  $posts = load_posts();
  $idx = ctype_digit((string)$id) ? (int)$id : find_index_by_id((string)$id, $posts);
  if (!isset($posts[$idx])) return false;

  $posts[$idx]['title']       = $title;
  $posts[$idx]['content']     = $content;
  $posts[$idx]['format']      = $format;
  $posts[$idx]['summary']     = $summary;
  if ($date !== null)         $posts[$idx]['date'] = $date;
  $posts[$idx]['readMinutes'] = max(1, $readMinutes);
  $posts[$idx]['tags']        = array_values($tags);

  return save_all_posts($posts);
}

/** Tambah post baru + auto-slug id kalau belum ada. */
function save_post_new(string $title, string $content, string $format = 'html', ?string $summary = null, ?string $date = null, int $readMinutes = 3, array $tags = []): bool
{
  $posts = load_posts();
  // buat slug id unik
  $slug  = strtolower(trim(preg_replace('~[^a-z0-9]+~', '-', $title), '-')) ?: 'post';
  $base  = $slug;
  $n = 1;
  while (find_index_by_id($slug, $posts) >= 0) $slug = $base . '-' . ($n++);

  $posts[] = [
    'id'          => $slug,
    'title'       => $title,
    'summary'     => $summary,
    'content'     => $content,
    'format'      => $format,
    'date'        => $date ?: date('Y-m-d H:i:s'),
    'readMinutes' => max(1, $readMinutes),
    'tags'        => array_values($tags),
  ];
  return save_all_posts($posts);
}