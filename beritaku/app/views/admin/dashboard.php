<?php $pageTitle = 'Dashboard'; $breadcrumb = [['label'=>'Dashboard','active'=>true]]; ?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Dashboard</h4>
            <p class="text-muted small mb-0">Selamat datang kembali, <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>!</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/articles/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Artikel Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card bg-primary text-white rounded-3 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= number_format($stats['articles']) ?></div>
                        <div class="stat-label">Total Artikel</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div>
                </div>
                <div class="mt-2 small opacity-75"><?= $stats['published'] ?> dipublish · <?= $stats['draft'] ?> draft</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card bg-success text-white rounded-3 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= number_format($stats['users']) ?></div>
                        <div class="stat-label">Pengguna</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-people"></i></div>
                </div>
                <div class="mt-2 small opacity-75">Terdaftar</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card bg-warning text-white rounded-3 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= number_format($stats['comments']) ?></div>
                        <div class="stat-label">Komentar</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-chat-dots"></i></div>
                </div>
                <div class="mt-2 small opacity-75"><?= $stats['pending'] ?> menunggu persetujuan</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card bg-info text-white rounded-3 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= number_format($stats['views']) ?></div>
                        <div class="stat-label">Total Views</div>
                    </div>
                    <div class="stat-icon"><i class="bi bi-eye"></i></div>
                </div>
                <div class="mt-2 small opacity-75"><?= $stats['categories'] ?> kategori aktif</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Latest Articles -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Artikel Terbaru</h6>
                    <a href="<?= BASE_URL ?>/admin/articles" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($latestArticles as $art): ?>
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold small line-clamp-1" style="max-width:200px"><?= htmlspecialchars($art->title) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($art->author_name) ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border small"><?= htmlspecialchars($art->category_name) ?></span></td>
                                <td>
                                    <?php $sc = ['published'=>'success','draft'=>'secondary','archived'=>'warning']; ?>
                                    <span class="badge bg-<?= $sc[$art->status] ?? 'secondary' ?>"><?= ucfirst($art->status) ?></span>
                                </td>
                                <td><small><?= number_format($art->views) ?></small></td>
                                <td><small class="text-muted"><?= date('d/m/Y', strtotime($art->created_at)) ?></small></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= BASE_URL ?>/admin/articles/<?= $art->id ?>/edit" class="btn btn-xs btn-outline-primary p-1"><i class="bi bi-pencil small"></i></a>
                                        <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" target="_blank" class="btn btn-xs btn-outline-secondary p-1"><i class="bi bi-eye small"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="col-lg-4">
            <!-- Top Articles -->
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-fire text-danger me-2"></i>Artikel Terpopuler</h6>
                </div>
                <div class="card-body p-3">
                    <?php foreach($topArticles as $i => $art): ?>
                    <div class="d-flex gap-2 mb-3 <?= $i < count($topArticles)-1 ? 'pb-3 border-bottom' : '' ?>">
                        <span class="badge bg-danger rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center" style="width:24px;height:24px;font-size:.7rem"><?= $i+1 ?></span>
                        <div>
                            <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" target="_blank" class="text-decoration-none">
                                <p class="small fw-semibold text-dark lh-sm mb-0 line-clamp-2"><?= htmlspecialchars($art->title) ?></p>
                            </a>
                            <small class="text-muted"><i class="bi bi-eye me-1"></i><?= number_format($art->views) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Pending Comments -->
            <?php if(!empty($latestComments)): ?>
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-chat-dots text-warning me-2"></i>Komentar Pending</h6>
                    <a href="<?= BASE_URL ?>/admin/comments?status=pending" class="btn btn-sm btn-outline-warning">Kelola</a>
                </div>
                <div class="card-body p-3">
                    <?php foreach($latestComments as $cmt): ?>
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold"><?= htmlspecialchars($cmt->author_name ?? $cmt->user_name ?? 'Anonim') ?></span>
                            <small class="text-muted"><?= date('d/m', strtotime($cmt->created_at)) ?></small>
                        </div>
                        <p class="small text-muted mb-2 line-clamp-2"><?= htmlspecialchars($cmt->content) ?></p>
                        <div class="d-flex gap-1">
                            <a href="<?= BASE_URL ?>/admin/comments/<?= $cmt->id ?>/approve" class="btn btn-xs btn-success py-0 px-2" style="font-size:.7rem"><i class="bi bi-check me-1"></i>Setujui</a>
                            <a href="<?= BASE_URL ?>/admin/comments/<?= $cmt->id ?>/delete" class="btn btn-xs btn-outline-danger py-0 px-2" style="font-size:.7rem"><i class="bi bi-trash me-1"></i>Hapus</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Category Stats -->
    <div class="card border-0 shadow-sm rounded-3 mt-3">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Artikel per Kategori</h6>
        </div>
        <div class="card-body p-3">
            <div class="row g-3">
                <?php
                $maxCount = max(array_column($catStats, 'cnt') ?: [1]);
                foreach($catStats as $cs): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-semibold"><?= htmlspecialchars($cs->name) ?></span>
                        <span class="text-muted"><?= $cs->cnt ?> artikel</span>
                    </div>
                    <div class="progress" style="height:8px">
                        <div class="progress-bar bg-primary" style="width:<?= $maxCount > 0 ? round(($cs->cnt/$maxCount)*100) : 0 ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
