<?php $pageTitle = 'Pengaturan Situs'; $breadcrumb=[['label'=>'Pengaturan','active'=>true]]; ?>
<div class="p-4">
    <h4 class="fw-bold mb-4">Pengaturan Situs</h4>
    <form action="<?= BASE_URL ?>/admin/settings/save" method="POST">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-white fw-semibold py-3 border-0">
                        <i class="bi bi-globe me-2 text-primary"></i>Informasi Situs
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nama Situs</label>
                                <input type="text" name="site_name" class="form-control" value="<?= htmlspecialchars($settings['site_name'] ?? 'BeritaKu') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Tagline</label>
                                <input type="text" name="site_tagline" class="form-control" value="<?= htmlspecialchars($settings['site_tagline'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Deskripsi</label>
                                <textarea name="site_description" class="form-control" rows="2"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email Redaksi</label>
                                <input type="email" name="site_email" class="form-control" value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Telepon</label>
                                <input type="text" name="site_phone" class="form-control" value="<?= htmlspecialchars($settings['site_phone'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-white fw-semibold py-3 border-0">
                        <i class="bi bi-newspaper me-2 text-primary"></i>Pengaturan Konten
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Artikel per Halaman</label>
                                <input type="number" name="articles_per_page" class="form-control" min="4" max="50" value="<?= htmlspecialchars($settings['articles_per_page'] ?? '12') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Moderasi Komentar</label>
                                <select name="comment_moderation" class="form-select">
                                    <option value="1" <?= ($settings['comment_moderation'] ?? '1') == '1' ? 'selected' : '' ?>>Perlu Persetujuan</option>
                                    <option value="0" <?= ($settings['comment_moderation'] ?? '1') == '0' ? 'selected' : '' ?>>Langsung Tampil</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Teks Breaking News</label>
                                <input type="text" name="breaking_news" class="form-control" value="<?= htmlspecialchars($settings['breaking_news'] ?? '') ?>" placeholder="Teks default pada ticker breaking news...">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i>Simpan Pengaturan
                </button>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 bg-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Info Sistem</h6>
                        <div class="mb-2 d-flex justify-content-between small">
                            <span class="text-muted">PHP Version</span>
                            <span class="fw-semibold"><?= phpversion() ?></span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between small">
                            <span class="text-muted">Framework</span>
                            <span class="fw-semibold">Custom MVC</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between small">
                            <span class="text-muted">Bootstrap</span>
                            <span class="fw-semibold">5.3.2</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between small">
                            <span class="text-muted">Database</span>
                            <span class="fw-semibold">MySQL 8+</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Server Time</span>
                            <span class="fw-semibold"><?= date('H:i:s') ?> WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
