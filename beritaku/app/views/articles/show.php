<?php $pageTitle = $article->title; ?>
<div class="container py-4">
    <div class="row g-4">
        <!-- Main Article -->
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Beranda</a></li>
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/kategori/<?= $article->category_slug ?>"><?= htmlspecialchars($article->category_name) ?></a>
                    </li>
                    <li class="breadcrumb-item active text-truncate" style="max-width:200px"><?= htmlspecialchars($article->title) ?></li>
                </ol>
            </nav>

            <!-- Article Card -->
            <article class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <span class="badge mb-2" style="background:<?= $article->category_color ?>"><?= htmlspecialchars($article->category_name) ?></span>
                <?php if($article->is_breaking): ?>
                <span class="badge bg-danger ms-1">BREAKING</span>
                <?php endif; ?>

                <h1 class="fw-bold lh-sm my-3 article-headline"><?= htmlspecialchars($article->title) ?></h1>

                <?php if($article->excerpt): ?>
                <p class="lead text-muted border-start border-danger border-3 ps-3 mb-3"><?= htmlspecialchars($article->excerpt) ?></p>
                <?php endif; ?>

                <!-- Meta -->
                <div class="d-flex flex-wrap gap-3 align-items-center py-3 border-top border-bottom mb-4 text-muted small">
                    <div class="d-flex align-items-center gap-2">
                        <img src="<?= BASE_URL ?>/uploads/avatars/<?= $article->author_avatar ?? 'default.png' ?>"
                             class="rounded-circle" width="32" height="32" onerror="this.src='<?= BASE_URL ?>/img/avatar.png'">
                        <div>
                            <div class="fw-semibold text-dark"><?= htmlspecialchars($article->author_name) ?></div>
                        </div>
                    </div>
                    <span><i class="bi bi-calendar3 me-1"></i><?= date('d F Y, H:i', strtotime($article->published_at)) ?> WIB</span>
                    <span><i class="bi bi-eye me-1"></i><?= number_format($article->views) ?> dibaca</span>
                    <!-- Share Buttons -->
                    <div class="ms-auto d-flex gap-1">
                        <a href="https://wa.me/?text=<?= urlencode($article->title . ' ' . BASE_URL . '/artikel/' . $article->slug) ?>" 
                           class="btn btn-sm btn-success" target="_blank" title="Share WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($article->title) ?>&url=<?= urlencode(BASE_URL . '/artikel/' . $article->slug) ?>"
                           class="btn btn-sm btn-dark" target="_blank">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/artikel/' . $article->slug) ?>"
                           class="btn btn-sm btn-primary" target="_blank">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="copyLink()" title="Salin link">
                            <i class="bi bi-link-45deg"></i>
                        </button>
                    </div>
                </div>

                <!-- Thumbnail -->
                <?php if($article->thumbnail): ?>
                <figure class="mb-4">
                    <img src="<?= BASE_URL ?>/uploads/<?= $article->thumbnail ?>" class="img-fluid rounded-3 w-100" style="max-height:450px;object-fit:cover" alt="">
                </figure>
                <?php endif; ?>

                <!-- Content -->
                <div class="article-content lh-lg">
                    <?= $article->content ?>
                </div>

                <!-- Tags -->
                <?php if(!empty($tags)): ?>
                <div class="mt-4 pt-3 border-top">
                    <span class="text-muted small me-2"><i class="bi bi-tags me-1"></i>Tags:</span>
                    <?php foreach($tags as $tag): ?>
                    <a href="<?= BASE_URL ?>/search?q=<?= urlencode($tag->name) ?>" class="badge bg-light text-dark text-decoration-none border me-1">
                        <?= htmlspecialchars($tag->name) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Author Box -->
                <div class="mt-4 p-4 bg-light rounded-3">
                    <div class="d-flex gap-3">
                        <img src="<?= BASE_URL ?>/uploads/avatars/<?= $article->author_avatar ?? 'default.png' ?>"
                             class="rounded-circle flex-shrink-0" width="64" height="64" onerror="this.src='<?= BASE_URL ?>/img/avatar.png'">
                        <div>
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($article->author_name) ?></h6>
                            <p class="text-muted small mb-0"><?= htmlspecialchars($article->author_bio ?? 'Penulis di BeritaKu') ?></p>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            <?php if(!empty($related)): ?>
            <div class="mb-4">
                <h5 class="fw-bold mb-3 section-title"><span>Berita</span> Terkait</h5>
                <div class="row g-3">
                    <?php foreach($related as $rel): ?>
                    <div class="col-md-6">
                        <div class="card card-hover border-0 shadow-sm rounded-3 overflow-hidden">
                            <div class="row g-0 align-items-center">
                                <div class="col-5">
                                    <img src="<?= $rel->thumbnail ? BASE_URL.'/uploads/'.$rel->thumbnail : BASE_URL.'/img/placeholder.jpg' ?>"
                                         class="img-fluid object-fit-cover rounded-start" style="height:100px;width:100%" alt="">
                                </div>
                                <div class="col-7 p-2">
                                    <a href="<?= BASE_URL ?>/artikel/<?= $rel->slug ?>" class="text-decoration-none">
                                        <p class="small fw-semibold text-dark lh-sm mb-1 line-clamp-3"><?= htmlspecialchars($rel->title) ?></p>
                                    </a>
                                    <small class="text-muted"><?= date('d M Y', strtotime($rel->published_at)) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Comments Section -->
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-chat-dots me-2"></i>Komentar (<?= count($comments) ?>)</h5>

                <!-- Comment Form -->
                <div class="mb-4 p-3 bg-light rounded-3">
                    <h6 class="fw-semibold mb-3">Tinggalkan Komentar</h6>
                    <form action="<?= BASE_URL ?>/artikel/<?= $article->slug ?>/komentar" method="POST">
                        <?php if(!isset($_SESSION['user_id'])): ?>
                        <div class="row g-2 mb-2">
                            <div class="col-sm-6">
                                <input type="text" name="author_name" class="form-control form-control-sm" placeholder="Nama Anda *" required>
                            </div>
                            <div class="col-sm-6">
                                <input type="email" name="author_email" class="form-control form-control-sm" placeholder="Email (tidak dipublish)">
                            </div>
                        </div>
                        <?php endif; ?>
                        <textarea name="content" class="form-control mb-2" rows="3" placeholder="Tulis komentar Anda..." required></textarea>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-send me-1"></i>Kirim Komentar
                        </button>
                        <?php if(!isset($_SESSION['user_id'])): ?>
                        <small class="text-muted ms-2">atau <a href="<?= BASE_URL ?>/auth/login">Login</a> untuk komentar lebih mudah</small>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Comments List -->
                <?php if(empty($comments)): ?>
                <p class="text-muted text-center py-3"><i class="bi bi-chat-square-dots fs-2 d-block mb-2 opacity-50"></i>Belum ada komentar. Jadilah yang pertama!</p>
                <?php else: ?>
                <div class="comments-list">
                    <?php foreach($comments as $cmt): ?>
                    <div class="comment-item d-flex gap-3 mb-3 pb-3 border-bottom">
                        <img src="<?= BASE_URL ?>/img/avatar.png" class="rounded-circle flex-shrink-0" width="40" height="40" alt="">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-semibold small"><?= htmlspecialchars($cmt->user_name ?? $cmt->author_name ?? 'Anonim') ?></span>
                                <small class="text-muted"><?= date('d M Y H:i', strtotime($cmt->created_at)) ?></small>
                            </div>
                            <p class="mb-0 small"><?= nl2br(htmlspecialchars($cmt->content)) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top:70px">
                <!-- Popular -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="fw-bold mb-0"><i class="bi bi-fire text-danger me-2"></i>Berita Populer</h6>
                    </div>
                    <div class="card-body p-3">
                        <?php foreach($popular as $i => $pop): ?>
                        <div class="d-flex gap-3 mb-3 pb-3 <?= $i < count($popular)-1 ? 'border-bottom' : '' ?>">
                            <span class="popular-rank"><?= $i+1 ?></span>
                            <a href="<?= BASE_URL ?>/artikel/<?= $pop->slug ?>" class="text-decoration-none">
                                <p class="small fw-semibold text-dark lh-sm mb-0 line-clamp-2"><?= htmlspecialchars($pop->title) ?></p>
                                <small class="text-muted"><i class="bi bi-eye me-1"></i><?= number_format($pop->views) ?></small>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Categories -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0">
                        <h6 class="fw-bold mb-0"><i class="bi bi-grid-3x3-gap me-2"></i>Kategori</h6>
                    </div>
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
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href);
    const btn = event.target.closest('button');
    btn.innerHTML = '<i class="bi bi-check2"></i>';
    setTimeout(() => btn.innerHTML = '<i class="bi bi-link-45deg"></i>', 2000);
}
</script>
