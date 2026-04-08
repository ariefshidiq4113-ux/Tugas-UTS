<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?><?= APP_NAME ?></title>
    <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Portal berita terpercaya Indonesia' ?>">
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>

<!-- Breaking News Ticker -->
<?php $breaking = $breaking ?? []; if (!empty($breaking)): ?>
<div class="breaking-ticker bg-danger text-white py-2">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <span class="badge bg-white text-danger me-3 flex-shrink-0 fw-bold">BREAKING</span>
            <div class="ticker-wrap flex-grow-1 overflow-hidden">
                <div class="ticker-items d-flex gap-5">
                    <?php foreach($breaking as $b): ?>
                    <a href="<?= BASE_URL ?>/artikel/<?= htmlspecialchars($b->slug) ?>" class="text-white text-decoration-none text-nowrap">
                        <?= htmlspecialchars($b->title) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top bg-white border-bottom shadow-sm">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-black fs-2 text-primary" href="<?= BASE_URL ?>">
            <span class="text-dark">berita</span><span class="text-danger">ku</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <!-- Categories Nav -->
            <ul class="navbar-nav me-auto">
                <?php 
                $navCats = $categories ?? [];
                foreach(array_slice($navCats, 0, 7) as $cat): ?>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="<?= BASE_URL ?>/kategori/<?= $cat->slug ?>"><?= htmlspecialchars($cat->name) ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
            <!-- Search + Auth -->
            <div class="d-flex align-items-center gap-2">
                <form action="<?= BASE_URL ?>/search" method="GET" class="d-flex">
                    <div class="input-group input-group-sm">
                        <input type="search" name="q" class="form-control rounded-start" placeholder="Cari berita..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
                <?php if(isset($_SESSION['user_id'])): ?>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1" data-bs-toggle="dropdown">
                        <img src="<?= BASE_URL ?>/uploads/avatars/<?= $_SESSION['user_avatar'] ?? 'default.png' ?>" class="rounded-circle" width="24" height="24" onerror="this.src='<?= BASE_URL ?>/img/avatar.png'">
                        <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/profile"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                        <?php if(in_array($_SESSION['user_role'] ?? '', ['admin','editor'])): ?>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/admin/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-sm btn-outline-primary">Masuk</a>
                <a href="<?= BASE_URL ?>/auth/register" class="btn btn-sm btn-primary">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php 
$flashData = $flash ?? [];
if (!empty($flashData)): ?>
<div class="container mt-3">
    <?php foreach($flashData as $type => $msg): ?>
    <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
        <i class="bi bi-<?= $type === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
        <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- MAIN CONTENT -->
<main>
<?= $content ?>
</main>

<!-- Footer -->
<footer class="bg-dark text-white mt-5 pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="fw-black mb-3"><span>berita</span><span class="text-danger">ku</span></h4>
                <p class="text-white-50 small">Portal berita online terpercaya di Indonesia. Menyajikan informasi terkini, akurat, dan mendalam dari seluruh penjuru dunia.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="fw-bold mb-3">Kategori</h6>
                <ul class="list-unstyled">
                    <?php foreach(array_slice($categories ?? [], 0, 5) as $cat): ?>
                    <li class="mb-1"><a href="<?= BASE_URL ?>/kategori/<?= $cat->slug ?>" class="text-white-50 text-decoration-none small"><?= htmlspecialchars($cat->name) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-lg-2 col-6">
                <h6 class="fw-bold mb-3">Tentang</h6>
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none small">Tentang Kami</a></li>
                    <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none small">Redaksi</a></li>
                    <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none small">Kebijakan Privasi</a></li>
                    <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none small">Syarat & Ketentuan</a></li>
                    <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none small">Kontak</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="fw-bold mb-3">Newsletter</h6>
                <p class="text-white-50 small">Dapatkan berita terkini langsung di email Anda.</p>
                <div class="input-group mt-2">
                    <input type="email" class="form-control form-control-sm" placeholder="Email Anda...">
                    <button class="btn btn-danger btn-sm">Daftar</button>
                </div>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <p class="text-white-50 small mb-0">&copy; <?= date('Y') ?> BeritaKu. Hak cipta dilindungi.</p>
            <p class="text-white-50 small mb-0">Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> di Indonesia</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/js/main.js"></script>
</body>
</html>
