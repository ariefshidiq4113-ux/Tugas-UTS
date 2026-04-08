<?php // app/views/auth/login.php
$pageTitle = 'Masuk'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-black"><span class="text-dark">berita</span><span class="text-danger">ku</span></h3>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>
                    <form action="<?= BASE_URL ?>/auth/login" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="pwdInput" class="form-control" placeholder="Password" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePwd()"><i class="bi bi-eye" id="eyeIcon"></i></button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Masuk</button>
                    </form>
                    <hr class="my-4">
                    <p class="text-center small text-muted">Belum punya akun? <a href="<?= BASE_URL ?>/auth/register" class="fw-semibold">Daftar sekarang</a></p>
                    <p class="text-center small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Demo: admin@beritaku.com / password</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function togglePwd() {
    const i = document.getElementById('pwdInput');
    const e = document.getElementById('eyeIcon');
    if (i.type==='password') { i.type='text'; e.className='bi bi-eye-slash'; }
    else { i.type='password'; e.className='bi bi-eye'; }
}
</script>
<?php // ===================== REGISTER =====================
?>
<?php // app/views/auth/register.php ?>
<?php // $pageTitle = 'Daftar'; ?>
