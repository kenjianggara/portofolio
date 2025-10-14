<?php
// Kumpulan postingan contoh. Ganti/extend sesuai kebutuhan.
$POSTS = [
  [
    'id' => 'cortex-xsiam-triage',
    'title' => 'Cortex XSIAM – Alert Triage Playbook',
    'date' => '2025-10-12',
    'tags' => ['Security','XSIAM','Playbook'],
    'status' => 'Published',
    'readMinutes' => 6,
    'summary' => 'Langkah triage alert di XSIAM: prioritisasi, pivot, verifikasi IOC.',
    'content' => '<h2>Ringkasan</h2><p>Dokumentasi singkat tentang triage alert di XSIAM, termasuk prioritisasi severity, pivot melalui causality chain, serta verifikasi indikator menggunakan intel.</p><h3>Tahapan</h3><ol><li>Kumpulkan konteks</li><li>Cek prioritas & scope</li><li>Lakukan pivot</li><li>Verifikasi IOC</li><li>Tutup dengan evidence</li></ol>'
  ],
  [
    'id' => 'aws-lab-n8n',
    'title' => 'AWS Lab – Otomasi Harian dengan n8n',
    'date' => '2025-10-10',
    'tags' => ['AWS','Automation','n8n'],
    'status' => 'Published',
    'readMinutes' => 7,
    'summary' => 'Workflow n8n untuk reminder harian dan pencatatan ke Google Sheets.',
    'content' => '<h2>Tujuan</h2><p>Bikin workflow n8n untuk kebutuhan sehari-hari: reminder, catatan pengeluaran, dsb.</p><h3>Komponen</h3><ul><li>Trigger waktu harian</li><li>HTTP Request</li><li>Google Sheets</li></ul><h3>Hasil</h3><p>Notifikasi harian + log di sheets.</p>'
  ],
  [
    'id' => 'deploy-portfolio-github-pages',
    'title' => 'Deploy Portfolio ke GitHub Pages',
    'date' => '2025-09-28',
    'tags' => ['Web','Portfolio','DevOps'],
    'status' => 'Draft',
    'readMinutes' => 4,
    'summary' => 'Langkah ringkas deploy website statis ke GitHub Pages + custom domain.',
    'content' => '<h2>Langkah</h2><ol><li>Push build ke branch <code>gh-pages</code>.</li><li>Atur repository settings.</li><li>Tambahkan CNAME bila perlu.</li></ol>'
  ],
];

function all_tags(array $posts): array {
  $tags = [];
  foreach ($posts as $p) { foreach ($p['tags'] as $t) { $tags[$t] = true; } }
  $keys = array_keys($tags);
  sort($keys);
  return $keys;
}

?>