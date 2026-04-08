<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Admin BeritaKu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/admin.css">
</head>
<body class="admin-body">

<!-- Sidebar -->
<aside class="admin-sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/admin/dashboard" class="brand-link">
            <span class="brand-icon"><i class="bi bi-newspaper"></i></span>
            <span class="brand-text">BeritaKu<small>Admin</small></span>
        </a>
        <button class="sidebar-close d-lg-none" id="sidebarClose"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="sidebar-user">
        <img src="<?= BASE_URL ?>/uploads/avatars/<?= $_SESSION['user_avatar'] ?? 'default.png' ?>" class="rounded-circle" width="40" height="40" onerror="this.src='<?= BASE_URL ?>/img/avatar.png'">
        <div class="ms-2">
            <div class="fw-semibold"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></div>
            <small class="text-muted text-capitalize"><?= $_SESSION['user_role'] ?? '' ?></small>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>
        <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/articles" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'articles') !== false ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-text"></i><span>Artikel</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/categories" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'categories') !== false ? 'active' : '' ?>">
            <i class="bi bi-tags"></i><span>Kategori</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/comments" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'comments') !== false ? 'active' : '' ?>">
            <i class="bi bi-chat-dots"></i><span>Komentar</span>
            <?php
            try {
                $pendingCnt = Database::getInstance()->count('comments', "status = 'pending'");
                if ($pendingCnt > 0) echo "<span class=\"badge bg-danger ms-auto\">{$pendingCnt}</span>";
            } catch(Exception $e) {}
            ?>
        </a>
        <div class="nav-label">Pengelolaan</div>
        <a href="<?= BASE_URL ?>/admin/users" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : '' ?>">
            <i class="bi bi-people"></i><span>Pengguna</span>
        </a>
        <a href="<?= BASE_URL ?>/admin/settings" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : '' ?>">
            <i class="bi bi-gear"></i><span>Pengaturan</span>
        </a>
        <div class="nav-label">Navigasi</div>
        <a href="<?= BASE_URL ?>" target="_blank" class="nav-item">
            <i class="bi bi-globe"></i><span>Lihat Website</span>
        </a>
        <a href="<?= BASE_URL ?>/auth/logout" class="nav-item text-danger">
            <i class="bi bi-box-arrow-left"></i><span>Keluar</span>
        </a>
    </nav>
</aside>

<!-- Main Content -->
<div class="admin-main" id="adminMain">
    <!-- Topbar -->
    <header class="admin-topbar">
        <button class="btn btn-sm btn-light me-2" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="topbar-breadcrumb d-none d-md-block">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/dashboard">Admin</a></li>
                    <?php if(isset($breadcrumb)): foreach($breadcrumb as $bc): ?>
                    <li class="breadcrumb-item <?= isset($bc['active']) ? 'active' : '' ?>">
    <?= !empty($bc['active']) ? $bc['label'] : "<a href='{$bc['url']}'>{$bc['label']}</a>" ?>
</li>
                    <?php endforeach; endif; ?>
                </ol>
            </nav>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-md-block"><?= date('d F Y') ?></span>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php $flashData = $flash ?? []; if (!empty($flashData)): ?>
    <div class="px-4 pt-3">
        <?php foreach($flashData as $type => $msg): ?>
        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show mb-0">
            <i class="bi bi-<?= $type === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Page Content -->
    <div class="admin-content">
        <?= $content ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="<?= BASE_URL ?>/js/admin.js"></script>
</body>
</html>
