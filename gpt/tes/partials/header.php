<?php
  $BASE_URL = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
  if ($BASE_URL === '.') $BASE_URL = '';
?>
<!doctype html>
<html lang="id" data-bs-theme="light">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dokumentasi Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg border-bottom bg-body">
      <div class="container">
        <a class="navbar-brand fw-bold" href="/index.php">Project Docs</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
          <ul class="navbar-nav ms-auto align-items-lg-center">
            <li class="nav-item me-2">
              <a href="/index.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
              <button id="themeToggle" class="btn btn-outline-secondary btn-sm">🌙 Dark</button>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <main class="py-4">
      <div class="container">
