<?php
// Ambil config dari luar repo (prod) atau fallback ke config.dev.php (dev)
$config = is_file('../etc/config.php')
  ? require '../etc/config.php'
  : require __DIR__ . './config.dev.php';

define('POSTS_FILE', $config['posts_file']);
define('POSTS_HMAC', $config['posts_hmac_file']);
define('SECRET_KEY', $config['secret_key']);

function load_posts(): array {
  if (!file_exists(POSTS_FILE)) return [];
  $raw = file_get_contents(POSTS_FILE);
  if (file_exists(POSTS_HMAC)) {
    $calc = hash_hmac('sha256', $raw, SECRET_KEY);
    $expected = trim(file_get_contents(POSTS_HMAC));
    if (!hash_equals($calc, $expected)) {
      error_log('WARNING: HMAC mismatch — possible tampering.');
    }
  }
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function save_posts(array $posts): bool {
  usort($posts, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));
  $json = json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  if ($json === false) return false;
  @copy(POSTS_FILE, POSTS_FILE . '.bak.' . time());
  $ok = file_put_contents(POSTS_FILE, $json, LOCK_EX) !== false;
  if (!$ok) return false;
  $hmac = hash_hmac('sha256', $json, SECRET_KEY);
  file_put_contents(POSTS_HMAC, $hmac, LOCK_EX);
  return true;
}

function get_post_by_id(string $id): ?array {
  foreach (load_posts() as $p) {
    if (($p['id'] ?? '') === $id) return $p;
  }
  return null;
}
