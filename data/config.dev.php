<?php
return [
  'posts_file'      => __DIR__ . './posts.json',
  'posts_hmac_file' => __DIR__ . './posts.hmac',
  'secret_key'      => '0123456789abcdef0123456789abcdef',
  'admin_user'      => 'admin',
  'admin_pass_hash' => password_hash('DevPass123!', PASSWORD_DEFAULT),
];
