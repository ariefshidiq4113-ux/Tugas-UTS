<?php $pageTitle = 'Hasil Pencarian: ' . htmlspecialchars($q); ?>
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="mb-4">
                <h4 class="fw-bold">Hasil pencarian: "<span class="text-primary"><?= htmlspecialchars($q) ?></span>"</h4>
                <p class="text-muted small"><?= $total ?> artikel ditemukan</p>
            </div>
            <!-- Search bar -->
            <form action="<?= BASE_URL ?>/search" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="search" name="q" class="form-control" value="<?= htmlspecialchars($q) ?>" placeholder="Cari berita...">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i>Cari</button>
                </div>
            </form>
            <?php if(empty($articles)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-search fs-1 opacity-25 d-block mb-3"></i>
                <h5>Tidak ada hasil untuk "<?= htmlspecialchars($q) ?>"</h5>
                <p class="small">Coba kata kunci lain atau lihat kategori di bawah.</p>
            </div>
            <?php else: ?>
            <div class="row g-3">
                <?php foreach($articles as $art): ?>
                <div class="col-12">
                    <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-3 col-4">
                                <img src="<?= $art->thumbnail ? BASE_URL.'/uploads/'.$art->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                                     class="img-fluid object-fit-cover" style="height:120px;width:100%" alt="">
                            </div>
                            <div class="col-md-9 col-8 p-3">
                                <span class="badge mb-1" style="background:<?= $art->category_color ?>;font-size:.7rem"><?= htmlspecialchars($art->category_name) ?></span>
                                <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" class="text-decoration-none">
                                    <h6 class="fw-semibold text-dark lh-sm mb-1"><?= htmlspecialchars($art->title) ?></h6>
                                </a>
                                <p class="text-muted small mb-1 line-clamp-2 d-none d-md-block"><?= htmlspecialchars($art->excerpt ?? '') ?></p>
                                <div class="d-flex gap-3 text-muted" style="font-size:.75rem">
                                    <span><i class="bi bi-person me-1"></i><?= htmlspecialchars($art->author_name) ?></span>
                                    <span><?= date('d M Y', strtotime($art->published_at)) ?></span>
                                    <span><i class="bi bi-eye me-1"></i><?= number_format($art->views) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Pagination -->
            <?php if($lastPage > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if($page > 1): ?>
                    <li class="page-item"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $page-1 ?>"><i class="bi bi-chevron-left"></i></a></li>
                    <?php endif; ?>
                    <?php for($i=max(1,$page-2); $i<=min($lastPage,$page+2); $i++): ?>
                    <li class="page-item <?= $i==$page?'active':'' ?>"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $i ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if($page < $lastPage): ?>
                    <li class="page-item"><a class="page-link" href="?q=<?= urlencode($q) ?>&page=<?= $page+1 ?>"><i class="bi bi-chevron-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Kategori</h6></div>
                <div class="list-group list-group-flush rounded-3">
                    <?php foreach($categories as $cat): ?>
                    <a href="<?= BASE_URL ?>/kategori/<?= $cat->slug ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-2">
                        <i class="<?= $cat->icon ?>" style="color:<?= $cat->color ?>"></i>
                        <span class="small"><?= htmlspecialchars($cat->name) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
