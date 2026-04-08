<?php $pageTitle = 'Beranda'; ?>
<!-- Hero / Featured Articles -->
<?php if (!empty($featured)): ?>
<section class="hero-section py-4 bg-light border-bottom">
    <div class="container">
        <div class="row g-3">
            <!-- Main Featured -->
            <div class="col-lg-7">
                <?php $main = $featured[0]; ?>
                <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden h-100 position-relative">
                    <img src="<?= $main->thumbnail ? BASE_URL.'/uploads/'.$main->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                         class="card-img h-100 object-fit-cover" style="max-height:420px" alt="">
                    <div class="card-img-overlay d-flex flex-column justify-content-end hero-overlay p-4">
                        <span class="badge mb-2 d-inline-block" style="background:<?= $main->category_color ?>; width:fit-content">
                            <?= htmlspecialchars($main->category_name) ?>
                        </span>
                        <a href="<?= BASE_URL ?>/artikel/<?= $main->slug ?>" class="text-white text-decoration-none">
                            <h2 class="fw-bold display-title lh-sm mb-2"><?= htmlspecialchars($main->title) ?></h2>
                        </a>
                        <p class="text-white-75 small mb-2 d-none d-md-block"><?= htmlspecialchars($main->excerpt ?? '') ?></p>
                        <div class="d-flex align-items-center gap-2 text-white-50 small">
                            <i class="bi bi-person-circle"></i><?= htmlspecialchars($main->author_name) ?>
                            <i class="bi bi-clock ms-2"></i><?= date('d M Y', strtotime($main->published_at)) ?>
                            <i class="bi bi-eye ms-2"></i><?= number_format($main->views) ?>
                        </div>
                    </div>
                    <?php if($main->is_breaking): ?>
                    <span class="position-absolute top-0 start-0 m-3 badge bg-danger">BREAKING</span>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Sub Featured -->
            <div class="col-lg-5">
                <div class="row g-3 h-100">
                    <?php foreach(array_slice($featured, 1, 3) as $feat): ?>
                    <div class="col-12">
                        <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="row g-0 align-items-center">
                                <div class="col-5">
                                    <img src="<?= $feat->thumbnail ? BASE_URL.'/uploads/'.$feat->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                                         class="img-fluid rounded-start object-fit-cover" style="height:110px;width:100%" alt="">
                                </div>
                                <div class="col-7 p-3">
                                    <span class="badge badge-sm mb-1" style="background:<?= $feat->category_color ?>;font-size:.7rem">
                                        <?= htmlspecialchars($feat->category_name) ?>
                                    </span>
                                    <a href="<?= BASE_URL ?>/artikel/<?= $feat->slug ?>" class="text-decoration-none">
                                        <h6 class="fw-semibold text-dark lh-sm mb-1 line-clamp-2"><?= htmlspecialchars($feat->title) ?></h6>
                                    </a>
                                    <small class="text-muted"><?= date('d M Y', strtotime($feat->published_at)) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Main Content Area -->
<div class="container py-4">
    <div class="row g-4">
        <!-- Left: News Feed -->
        <div class="col-lg-8">
            <!-- Latest News Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-title fw-bold mb-0"><span>Berita</span> Terbaru</h5>
                <span class="text-muted small"><?= count($latest) ?> artikel</span>
            </div>

            <!-- Category Quick Nav -->
            <div class="mb-4 overflow-auto">
                <div class="d-flex gap-2 flex-nowrap pb-1">
                    <a href="<?= BASE_URL ?>" class="btn btn-sm btn-primary flex-shrink-0">Semua</a>
                    <?php foreach($categories as $cat): ?>
                    <a href="<?= BASE_URL ?>/kategori/<?= $cat->slug ?>" class="btn btn-sm btn-outline-secondary flex-shrink-0">
                        <i class="<?= $cat->icon ?>"></i> <?= htmlspecialchars($cat->name) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Article Grid -->
            <div class="row g-3">
                <?php foreach($latest as $art): ?>
                <div class="col-md-6">
                    <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden h-100">
                        <div class="position-relative">
                            <img src="<?= $art->thumbnail ? BASE_URL.'/uploads/'.$art->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                                 class="card-img-top object-fit-cover" style="height:180px" alt="">
                            <span class="position-absolute top-0 start-0 m-2 badge" style="background:<?= $art->category_color ?>">
                                <?= htmlspecialchars($art->category_name) ?>
                            </span>
                            <?php if($art->is_breaking): ?>
                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">BREAKING</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-3">
                            <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" class="text-decoration-none">
                                <h6 class="card-title fw-semibold text-dark lh-sm mb-2 line-clamp-2">
                                    <?= htmlspecialchars($art->title) ?>
                                </h6>
                            </a>
                            <p class="card-text text-muted small line-clamp-2 mb-2"><?= htmlspecialchars($art->excerpt ?? '') ?></p>
                            <div class="d-flex justify-content-between align-items-center text-muted" style="font-size:.75rem">
                                <span><i class="bi bi-person me-1"></i><?= htmlspecialchars($art->author_name) ?></span>
                                <span><i class="bi bi-eye me-1"></i><?= number_format($art->views) ?></span>
                                <span><?= date('d M', strtotime($art->published_at)) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Per Category Sections -->
            <?php foreach($categorized as $catId => $catData): ?>
            <?php if(empty($catData['articles'])) continue; ?>
            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="section-title fw-bold mb-0">
                        <span class="category-bar" style="background:<?= $catData['info']->color ?>"></span>
                        <i class="<?= $catData['info']->icon ?> me-2" style="color:<?= $catData['info']->color ?>"></i>
                        <?= htmlspecialchars($catData['info']->name) ?>
                    </h5>
                    <a href="<?= BASE_URL ?>/kategori/<?= $catData['info']->slug ?>" class="btn btn-sm btn-outline-secondary">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="row g-3">
                    <?php foreach($catData['articles'] as $i => $art): ?>
                    <?php if($i === 0): ?>
                    <div class="col-md-7">
                        <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden h-100">
                            <img src="<?= $art->thumbnail ? BASE_URL.'/uploads/'.$art->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                                 class="card-img-top object-fit-cover" style="height:220px" alt="">
                            <div class="card-body p-3">
                                <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" class="text-decoration-none">
                                    <h5 class="card-title fw-bold text-dark lh-sm mb-2"><?= htmlspecialchars($art->title) ?></h5>
                                </a>
                                <p class="text-muted small mb-2 line-clamp-2"><?= htmlspecialchars($art->excerpt ?? '') ?></p>
                                <small class="text-muted"><?= date('d M Y', strtotime($art->published_at)) ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex flex-column gap-2 h-100">
                    <?php else: ?>
                        <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="row g-0 align-items-center">
                                <div class="col-4">
                                    <img src="<?= $art->thumbnail ? BASE_URL.'/uploads/'.$art->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                                         class="img-fluid rounded-start object-fit-cover" style="height:90px;width:100%" alt="">
                                </div>
                                <div class="col-8 p-2">
                                    <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" class="text-decoration-none">
                                        <p class="fw-semibold text-dark small lh-sm mb-1 line-clamp-2"><?= htmlspecialchars($art->title) ?></p>
                                    </a>
                                    <small class="text-muted"><?= date('d M Y', strtotime($art->published_at)) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php if($i === count($catData['articles'])-1): ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Right: Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top:70px">
                <!-- Popular -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="fw-bold mb-0"><i class="bi bi-fire text-danger me-2"></i>Berita Populer</h6>
                    </div>
                    <div class="card-body p-3">
                        <?php foreach($popular as $i => $pop): ?>
                        <div class="d-flex gap-3 mb-3 pb-3 <?= $i < count($popular)-1 ? 'border-bottom' : '' ?>">
                            <span class="popular-rank"><?= $i+1 ?></span>
                            <div>
                                <a href="<?= BASE_URL ?>/artikel/<?= $pop->slug ?>" class="text-decoration-none">
                                    <p class="fw-semibold text-dark small lh-sm mb-1 line-clamp-2"><?= htmlspecialchars($pop->title) ?></p>
                                </a>
                                <small class="text-muted"><i class="bi bi-eye me-1"></i><?= number_format($pop->views) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Categories with Count -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Kategori</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <?php foreach($categories as $cat): ?>
                            <div class="col-6">
                                <a href="<?= BASE_URL ?>/kategori/<?= $cat->slug ?>" class="category-chip text-decoration-none">
                                    <span class="category-dot" style="background:<?= $cat->color ?>"></span>
                                    <span class="small"><?= htmlspecialchars($cat->name) ?></span>
                                    <span class="badge bg-light text-muted ms-auto"><?= $cat->article_count ?? 0 ?></span>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Promo / Ad space -->
                <div class="card border-0 shadow-sm rounded-3 mb-4 bg-gradient-primary text-white">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-bell-fill fs-2 mb-2"></i>
                        <h6 class="fw-bold">Dapatkan Notifikasi Berita</h6>
                        <p class="small opacity-75 mb-3">Jangan lewatkan berita terkini</p>
                        <?php if(!isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/auth/register" class="btn btn-sm btn-light fw-semibold">Daftar Sekarang</a>
                        <?php else: ?>
                        <button class="btn btn-sm btn-light fw-semibold">Aktifkan Notifikasi</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
