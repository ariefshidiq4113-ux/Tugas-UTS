<?php // app/views/admin/comments/index.php
$pageTitle = 'Kelola Komentar'; $breadcrumb=[['label'=>'Komentar','active'=>true]]; ?>
<div class="p-4">
    <h4 class="fw-bold mb-4">Kelola Komentar</h4>
    <!-- Filter -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3">
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/admin/comments" class="btn btn-sm <?= !$status ? 'btn-primary' : 'btn-outline-secondary' ?>">Semua</a>
                <a href="<?= BASE_URL ?>/admin/comments?status=pending" class="btn btn-sm <?= $status==='pending' ? 'btn-warning' : 'btn-outline-secondary' ?>">Pending</a>
                <a href="<?= BASE_URL ?>/admin/comments?status=approved" class="btn btn-sm <?= $status==='approved' ? 'btn-success' : 'btn-outline-secondary' ?>">Disetujui</a>
                <a href="<?= BASE_URL ?>/admin/comments?status=spam" class="btn btn-sm <?= $status==='spam' ? 'btn-danger' : 'btn-outline-secondary' ?>">Spam</a>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="ps-3">Pengirim</th><th>Komentar</th><th>Artikel</th><th>Status</th><th>Waktu</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                    <?php if(empty($comments)): ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted"><i class="bi bi-chat-square-dots fs-2 d-block mb-2 opacity-25"></i>Tidak ada komentar</td></tr>
                    <?php else: foreach($comments as $cmt): ?>
                    <tr>
                        <td class="ps-3">
                            <div class="fw-semibold small"><?= htmlspecialchars($cmt->user_name ?? $cmt->author_name ?? 'Anonim') ?></div>
                            <small class="text-muted"><?= htmlspecialchars($cmt->author_email ?? '') ?></small>
                        </td>
                        <td><p class="small mb-0 line-clamp-2" style="max-width:250px"><?= htmlspecialchars($cmt->content) ?></p></td>
                        <td><small class="text-muted line-clamp-1" style="max-width:150px"><?= htmlspecialchars($cmt->article_title ?? '') ?></small></td>
                        <td>
                            <?php $sc=['approved'=>'success','pending'=>'warning','spam'=>'danger']; ?>
                            <span class="badge bg-<?= $sc[$cmt->status] ?? 'secondary' ?>"><?= ucfirst($cmt->status) ?></span>
                        </td>
                        <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($cmt->created_at)) ?></small></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <?php if($cmt->status !== 'approved'): ?>
                                <a href="<?= BASE_URL ?>/admin/comments/<?= $cmt->id ?>/approve" class="btn btn-xs btn-success p-1" title="Setujui"><i class="bi bi-check small"></i></a>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/admin/comments/<?= $cmt->id ?>/delete" class="btn btn-xs btn-outline-danger p-1"
                                   onclick="return confirm('Hapus komentar ini?')" title="Hapus"><i class="bi bi-trash small"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($lastPage > 1): ?>
        <div class="card-footer bg-white border-top py-2 px-3 d-flex justify-content-end">
            <nav><ul class="pagination pagination-sm mb-0">
                <?php for($i=1;$i<=$lastPage;$i++): ?>
                <li class="page-item <?=$i==$page?'active':''?>"><a class="page-link" href="?page=<?=$i?>&status=<?=$status?>"><?=$i?></a></li>
                <?php endfor; ?>
            </ul></nav>
        </div>
        <?php endif; ?>
    </div>
</div>
