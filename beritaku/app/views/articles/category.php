<?php // app/views/articles/category.php
$pageTitle = $cat->name; ?>
<div class="container py-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="p-4 rounded-3 mb-4 text-white" style="background:<?= $cat->color ?>">
                <h1 class="fw-bold mb-1"><i class="<?= $cat->icon ?> me-2"></i><?= htmlspecialchars($cat->name) ?></h1>
                <p class="mb-0 opacity-75"><?= htmlspecialchars($cat->description ?? '') ?> • <?= $total ?> artikel</p>
            </div>

            <?php if(empty($articles)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-newspaper fs-1 opacity-25 d-block mb-3"></i>
                <p>Belum ada artikel di kategori ini.</p>
            </div>
            <?php else: ?>
            <div class="row g-3">
                <?php foreach($articles as $art): ?>
                <div class="col-md-6">
                    <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden h-100">
                        <img src="<?= $art->thumbnail ? BASE_URL.'/uploads/'.$art->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                             class="card-img-top object-fit-cover" style="height:180px" alt="">
                        <div class="card-body p-3">
                            <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" class="text-decoration-none">
                                <h6 class="fw-semibold text-dark lh-sm mb-2 line-clamp-2"><?= htmlspecialchars($art->title) ?></h6>
                            </a>
                            <p class="text-muted small mb-2 line-clamp-2"><?= htmlspecialchars($art->excerpt ?? '') ?></p>
                            <div class="d-flex justify-content-between text-muted" style="font-size:.75rem">
                                <span><?= htmlspecialchars($art->author_name) ?></span>
                                <span><?= date('d M Y', strtotime($art->published_at)) ?></span>
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
                    <li class="page-item"><a class="page-link" href="?page=<?= $page-1 ?>"><i class="bi bi-chevron-left"></i></a></li>
                    <?php endif; ?>
                    <?php for($i=max(1,$page-2); $i<=min($lastPage,$page+2); $i++): ?>
                    <li class="page-item <?= $i==$page?'active':'' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if($page < $lastPage): ?>
                    <li class="page-item"><a class="page-link" href="?page=<?= $page+1 ?>"><i class="bi bi-chevron-right"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0"><h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Semua Kategori</h6></div>
                <div class="list-group list-group-flush rounded-3">
                    <?php foreach($categories as $c): ?>
                    <a href="<?= BASE_URL ?>/kategori/<?= $c->slug ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-2 <?= $c->id==$cat->id?'active':'' ?>">
                        <i class="<?= $c->icon ?>" style="<?= $c->id!=$cat->id?'color:'.$c->color:'' ?>"></i>
                        <span class="small"><?= htmlspecialchars($c->name) ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
