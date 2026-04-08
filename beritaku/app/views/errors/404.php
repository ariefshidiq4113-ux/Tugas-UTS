<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | BeritaKu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="display-1 fw-black text-danger mb-0">404</div>
        <h2 class="fw-bold mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-muted mb-4">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="<?= defined('BASE_URL') ? BASE_URL : '/' ?>" class="btn btn-primary"><i class="bi bi-house me-1"></i>Beranda</a>
            <button onclick="history.back()" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
        </div>
    </div>
</div>
</body>
</html>
