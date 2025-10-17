<?php
// Pakai config prod kalau ada, kalau tidak pakai config.dev.php (lokal)
$prod = __DIR__ . '/../etc/config.php';     // kalau nanti kamu taruh di /etc/… server, ganti path ini
$dev  = __DIR__ . '/config.dev.php';
$config = is_file($prod) ? require $prod : require $dev;

define('POSTS_FILE', $config['posts_file']);
define('POSTS_HMAC', $config['posts_hmac_file']);
define('SECRET_KEY', $config['secret_key']);

function load_posts() {
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

function save_posts($posts) {
  usort($posts, function($a, $b) {
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

function get_post($id) {
  foreach (load_posts() as $p) {
    if (($p['id'] ?? '') === $id) return $p;
  }
  return null;
}
