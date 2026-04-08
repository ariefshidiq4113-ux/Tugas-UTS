<?php $pageTitle = 'Daftar Akun'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-black"><span class="text-dark">berita</span><span class="text-danger">ku</span></h3>
                        <p class="text-muted">Buat akun baru Anda</p>
                    </div>
                    <form action="<?= BASE_URL ?>/auth/register" method="POST">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small">Nama Lengkap *</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small">Username *</label>
                                <input type="text" name="username" class="form-control" placeholder="username" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Email *</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small">Password *</label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-semibold small">Konfirmasi Password *</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label small" for="terms">
                                        Saya setuju dengan <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan Privasi</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Daftar Sekarang</button>
                            </div>
                        </div>
                    </form>
                    <hr class="my-4">
                    <p class="text-center small text-muted mb-0">Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login" class="fw-semibold">Masuk di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
