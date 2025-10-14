<?php
include('./data/posts.php');

$id = $_GET['id'] ?? '';
$post = null;

// cari post berdasarkan id
foreach ($POSTS as $p) {
  if ($p['id'] === $id) {
    $post = $p;
    break;
  }
}

if (!$post) {
  http_response_code(404);
  die('<div class="text-center mt-5"><h3>404 - Post not found</h3></div>');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= htmlspecialchars($post['title']) ?> | My Personal Website</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include('./partials/header.php'); ?>

  <section class="container py-5" style="max-width: 900px;">
    <article>
      <!-- Judul & Info -->
      <header class="mb-4">
        <h2 class="fw-bold"><?= htmlspecialchars($post['title']) ?></h2>
        <p class="text-secondary mb-1 text-dark">
          <?= date('d M Y', strtotime($post['date'])) ?> • <?= $post['readMinutes'] ?> min read
        </p>
        <div class="d-flex flex-wrap gap-2">
          <?php foreach ($post['tags'] as $tag): ?>
            <span class="badge bg-light text-dark border">#<?= htmlspecialchars($tag) ?></span>
          <?php endforeach; ?>
        </div>
      </header>

      <hr>

      <!-- Konten -->
      <div class="content">
        <?php
        // Isi konten utama blog
        switch ($post['id']) {
          case 'xsiam-triage':
            echo '
              <h4>Pendahuluan</h4>
              <p>
                Cortex XSIAM menyediakan modul Alert Triage yang membantu SOC Analyst
                dalam menganalisis insiden keamanan secara cepat dan efisien. Artikel ini
                menjelaskan langkah-langkah dasar untuk melakukan triage alert.
              </p>

              <h4>Langkah-langkah</h4>
              <ol>
                <li><strong>Kumpulkan konteks:</strong> Lihat host, user, dan file yang terlibat.</li>
                <li><strong>Prioritaskan alert:</strong> Fokus pada severity tinggi terlebih dahulu.</li>
                <li><strong>Lakukan pivot:</strong> Gunakan causality chain untuk melacak proses terkait.</li>
                <li><strong>Verifikasi IOC:</strong> Bandingkan dengan threat intel dari Unit42 atau sumber eksternal.</li>
                <li><strong>Dokumentasikan hasil:</strong> Tambahkan catatan di incident timeline.</li>
              </ol>

              <h4>Kesimpulan</h4>
              <p>
                Dengan mengikuti tahapan triage yang sistematis, analis dapat mengurangi waktu respon
                dan meningkatkan akurasi penanganan insiden.
              </p>
            ';
            break;

          case 'aws-n8n-lab':
            echo '
              <h4>Tujuan</h4>
              <p>Membuat workflow sederhana di <strong>n8n</strong> untuk otomatisasi harian menggunakan AWS.</p>

              <h4>Langkah Pembuatan</h4>
              <ul>
                <li>Buat instance EC2 dan instal n8n menggunakan Docker atau Node.js.</li>
                <li>Tambahkan <em>Trigger</em> “Cron” untuk menjadwalkan workflow.</li>
                <li>Gunakan node “HTTP Request” untuk mengirim data ke API eksternal.</li>
                <li>Tambahkan node “Google Sheets” untuk menyimpan hasil.</li>
              </ul>

              <h4>Hasil</h4>
              <p>
                Workflow otomatis mengirim notifikasi harian dan mencatat hasil ke spreadsheet.
                Solusi ini bisa dikembangkan untuk reminder, logging, atau integrasi cloud lainnya.
              </p>
            ';
            break;

          case 'portfolio-deploy':
            echo '
              <h4>Langkah Deploy</h4>
              <ol>
                <li>Bangun project static website menggunakan HTML/CSS/JS.</li>
                <li>Push hasil build ke branch <code>gh-pages</code>.</li>
                <li>Aktifkan GitHub Pages dari menu Settings → Pages.</li>
                <li>Tambahkan file <code>CNAME</code> jika menggunakan custom domain.</li>
              </ol>

              <h4>Tips Tambahan</h4>
              <p>
                Pastikan repository diatur sebagai public dan gunakan HTTPS untuk koneksi aman.
                Custom domain bisa diarahkan dengan DNS record <code>CNAME</code> ke <em>username.github.io</em>.
              </p>
            ';
            break;
        }
        ?>
      </div>

      <hr class="my-4">
      <a href="blog.php" class="btn btn-outline-primary">← Kembali ke Blog</a>
    </article>
  </section>

  <?php include('./partials/footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
</body>
</html>
