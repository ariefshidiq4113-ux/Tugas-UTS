<?php $pageTitle = 'Profil Saya'; ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4"><i class="bi bi-person-circle me-2 text-primary"></i>Profil Saya</h4>
                    <form action="<?= BASE_URL ?>/profile/update" method="POST" enctype="multipart/form-data">
                        <!-- Avatar -->
                        <div class="text-center mb-4">
                            <img src="<?= $user->avatar && $user->avatar !== 'default.png' ? BASE_URL.'/uploads/avatars/'.$user->avatar : BASE_URL.'/img/avatar.png' ?>"
                                 class="rounded-circle border mb-3" width="100" height="100" style="object-fit:cover" id="avatarPreview">
                            <div>
                                <label class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-camera me-1"></i>Ganti Foto
                                    <input type="file" name="avatar" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user->name) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user->email) ?>" readonly>
                            <div class="form-text">Email tidak bisa diubah</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user->username) ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Bio</label>
                            <textarea name="bio" class="form-control" rows="3" placeholder="Ceritakan sedikit tentang diri Anda..."><?= htmlspecialchars($user->bio ?? '') ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                            <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Account Info -->
            <div class="card border-0 shadow-sm rounded-4 mt-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Akun</h6>
                    <div class="row g-2 text-muted small">
                        <div class="col-6"><strong>Role:</strong> <span class="badge bg-primary text-capitalize"><?= $user->role ?></span></div>
                        <div class="col-6"><strong>Status:</strong> <span class="badge bg-success">Aktif</span></div>
                        <div class="col-6"><strong>Bergabung:</strong> <?= date('d F Y', strtotime($user->created_at)) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
