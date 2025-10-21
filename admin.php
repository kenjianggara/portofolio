<?php
// --- bootstrap ---
session_start();
require __DIR__ . '/data/posts.php'; // sudah load $config + fungsi load/save

// helper
function is_admin()
{
    return !empty($_SESSION['is_admin']);
}
function csrf()
{
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24));
    return $_SESSION['csrf'];
}
function require_csrf()
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Bad CSRF');
    }
}

// LOGIN
if (($_POST['action'] ?? '') === 'login') {
    $u = trim($_POST['user'] ?? '');
    $p = $_POST['pass'] ?? '';
    if ($u === ($config['admin_user'] ?? '') && password_verify($p, $config['admin_pass_hash'] ?? '')) {
        $_SESSION['is_admin'] = true;
        csrf();
        header('Location: admin.php');
        exit;
    }
    $login_error = 'Login gagal';
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// if not logged in -> show login
if (!is_admin()): ?>
    <!doctype html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="p-4">
        <div class="container" style="max-width:420px">
            <h3 class="mb-3">Login Admin</h3>
            <?php if (!empty($login_error)): ?><div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div><?php endif; ?>
            <form method="post">
                <input type="hidden" name="action" value="login">
                <div class="mb-3"><label class="form-label">Username</label><input class="form-control" name="user" required></div>
                <div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="pass" required></div>
                <button class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </body>

    </html>
<?php exit;
endif;

// --- logged in area ---
$mode = $_GET['mode'] ?? 'list';
$posts = load_posts();

// find by id
function find_index($id, $arr)
{
    foreach ($arr as $i => $x) if (($x['id'] ?? '') === $id) return $i;
    return -1;
}

// handle POST (save/delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] !== 'login') {
    require_csrf();
    if ($_POST['action'] === 'save') {
        $title = trim($_POST['title'] ?? '');
        $summary = trim($_POST['summary'] ?? '');
        $content = $_POST['content'] ?? '';
        $date = $_POST['date'] ?: date('Y-m-d');
        $readMinutes = max(1, (int)($_POST['readMinutes'] ?? 3));
        $tags = array_values(array_filter(array_map('trim', explode(',', $_POST['tags'] ?? ''))));

        // buat ID otomatis kalau kosong
        $id = $_POST['id'] ?? '';
        if ($id === '') {
            // slug dari judul (huruf kecil, spasi jadi '-')
            $slug = strtolower(trim(preg_replace('~[^a-z0-9]+~', '-', $title), '-'));
            $base = $slug ?: 'post';
            $id = $base;
            $i = 1;
            while (find_index($id, $posts) >= 0) {
                $id = $base . '-' . $i++;
            }
        }

        $item = compact('id', 'title', 'summary', 'content', 'date', 'readMinutes', 'tags');

        if ($title === '') {
            $err = 'Judul wajib diisi';
        } else {
            $idx = find_index($id, $posts);
            if ($idx >= 0) $posts[$idx] = $item;
            else $posts[] = $item;
            if (!save_posts($posts)) $err = 'Gagal simpan';
            else {
                header('Location: admin.php?msg=saved');
                exit;
            }
        }
    }
    if ($_POST['action'] === 'delete') {
        $id = (string)($_POST['id'] ?? '');
        $idx = ctype_digit($id) ? (int)$id : find_index_by_id($id, $posts);
        if (isset($posts[$idx])) {
            unset($posts[$idx]);
            save_all_posts($posts);
        }
        header('Location: admin.php?msg=deleted');
        exit;
    }
}

// current edit data
$edit = [
    'id' => '',
    'title' => '',
    'summary' => '',
    'content' => '',
    'date' => date('Y-m-d'),
    'readMinutes' => 3,
    'tags' => []
];
if ($mode === 'edit' && ($eid = $_GET['id'] ?? '')) {
    $edit = get_post($eid) ?: $edit;
}

// UI
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
</head>

<body class="admin-page">
    <?php include('./partials/header.php'); ?>
    <div class="container admin-wrap">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Admin Blog</h3>
            <div>
                <a class="btn btn-secondary btn-sm" href="blog.php">Lihat Blog</a>
                <a class="btn btn-danger btn-sm" href="?logout=1">Logout</a>
            </div>
        </div>

        <?php if (!empty($_GET['msg'])): ?>
            <div class="alert alert-success">Berhasil <?= htmlspecialchars($_GET['msg']) ?>.</div>
        <?php endif; ?>
        <?php if (!empty($err)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
        <?php endif; ?>

        <?php if ($mode === 'list'): ?>
            <div class="d-flex justify-content-between mb-2">
                <a class="btn btn-primary btn-sm" href="post_editor.php">✏️ Buat Post Baru</a>
                <a class="btn btn-primary btn-sm" href="?mode=edit">+ Tambah</a>
            </div>
            <div class="table-responsive admin-table-wrap">
                <table class="table table-sm align-middle admin-table">
                    <thead>
                        <tr>
                            <th style="width:140px">ID</th>
                            <th>Judul</th>
                            <th style="width:130px">Tanggal</th>
                            <th style="width:160px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $p): ?>
                            <?php
                            $id     = isset($p['id']) && $p['id'] !== '' ? (string)$p['id'] : (string)$i; // fallback index
                            $title  = htmlspecialchars((string)($p['title'] ?? 'Tanpa Judul'), ENT_QUOTES, 'UTF-8');
                            $date   = htmlspecialchars((string)($p['date']  ?? ''), ENT_QUOTES, 'UTF-8');
                            $idHtml = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <td><code><?= $idHtml ?></code></td>
                                <td><?= $title ?></td>
                                <td><?= $date ?></td>
                                <td class="text-end">
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="?mode=edit&id=<?= urlencode($p['id']) ?>">Edit</a>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Hapus post ini?')">
                                        <input type="hidden" name="csrf" value="<?= csrf() ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($mode === 'edit'): ?>
            <form method="post">
                <input type="hidden" name="csrf" value="<?= csrf() ?>">
                <input type="hidden" name="action" value="save">

                <div class="row g-3">
                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)($edit['id'] ?? ($_GET['id'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                    <div class="col-md-8">
                        <label class="form-label">Judul</label>
                        <input name="title" class="form-control" required value="<?= htmlspecialchars($edit['title']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($edit['date']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Menit baca</label>
                        <input type="number" name="readMinutes" class="form-control" value="<?= (int)$edit['readMinutes'] ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tags (pisah koma)</label>
                        <input name="tags" class="form-control" value="<?= htmlspecialchars(implode(',', $edit['tags'])) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ringkasan</label>
                        <textarea name="summary" class="form-control" rows="2"><?= htmlspecialchars($edit['summary']) ?></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Konten (HTML/teks)</label>
                        <textarea name="content" class="form-control" rows="10"><?= htmlspecialchars($edit['content']) ?></textarea>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Simpan</button>
                    <a class="btn btn-secondary" href="admin.php">Batal</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <script src="./script.js"></script>
    <script>
        (function() {
            // autosize textarea konten
            const content = document.getElementById('content');
            const autosize = el => {
                el.style.height = 'auto';
                el.style.height = (el.scrollHeight + 2) + 'px';
            };
            if (content) {
                autosize(content);
                content.addEventListener('input', () => autosize(content));
            }

            // counter ringkasan
            const sum = document.getElementById('summary'),
                out = document.getElementById('sumCount');
            if (sum && out) {
                const upd = () => out.textContent = String(sum.value.length);
                upd();
                sum.addEventListener('input', upd);
            }

            // preview tags
            const tagsIn = document.getElementById('tagsInput'),
                preview = document.getElementById('tagsPreview');
            const drawTags = () => {
                if (!tagsIn || !preview) return;
                const tags = tagsIn.value.split(',').map(s => s.trim()).filter(Boolean);
                preview.innerHTML = tags.map(t => `<span class="badge bg-secondary badge-tag">#${t}</span>`).join('');
            };
            if (tagsIn) {
                drawTags();
                tagsIn.addEventListener('input', drawTags);
            }
        })();
    </script>
    <?php include('./partials/footer.php'); ?>
</body>

</html>