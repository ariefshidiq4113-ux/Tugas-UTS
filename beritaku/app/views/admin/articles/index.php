<?php $pageTitle = 'Kelola Artikel'; $breadcrumb=[['label'=>'Artikel','active'=>true]]; ?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Artikel</h4>
            <p class="text-muted small mb-0"><?= $total ?> total artikel</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/articles/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Artikel Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3">
            <form action="<?= BASE_URL ?>/admin/articles" method="GET" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold mb-1">Cari Artikel</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" name="search" class="form-control" placeholder="Judul atau ringkasan..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="published" <?= $status==='published'?'selected':'' ?>>Published</option>
                        <option value="draft" <?= $status==='draft'?'selected':'' ?>>Draft</option>
                        <option value="archived" <?= $status==='archived'?'selected':'' ?>>Archived</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= BASE_URL ?>/admin/articles" class="btn btn-sm btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width:40%">Artikel</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(empty($articles)): ?>
                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>Tidak ada artikel ditemukan</td></tr>
                    <?php else: foreach($articles as $art): ?>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-3">
                                <?php if($art->thumbnail): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= $art->thumbnail ?>" class="rounded" width="56" height="40" style="object-fit:cover;flex-shrink:0" alt="">
                                <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center flex-shrink-0" style="width:56px;height:40px">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-semibold small line-clamp-2" style="max-width:250px"><?= htmlspecialchars($art->title) ?></div>
                                    <?php if($art->is_featured): ?><span class="badge bg-warning text-dark me-1" style="font-size:.6rem">Featured</span><?php endif; ?>
                                    <?php if($art->is_breaking): ?><span class="badge bg-danger" style="font-size:.6rem">Breaking</span><?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($art->category_name) ?></span></td>
                        <td><small class="text-muted"><?= htmlspecialchars($art->author_name) ?></small></td>
                        <td>
                            <?php $sc = ['published'=>'success','draft'=>'secondary','archived'=>'warning']; ?>
                            <span class="badge bg-<?= $sc[$art->status] ?? 'secondary' ?>"><?= ucfirst($art->status) ?></span>
                        </td>
                        <td><small><?= number_format($art->views) ?></small></td>
                        <td><small class="text-muted"><?= date('d/m/Y', strtotime($art->created_at)) ?></small></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="<?= BASE_URL ?>/artikel/<?= $art->slug ?>" target="_blank" class="btn btn-xs btn-outline-secondary p-1" title="Lihat"><i class="bi bi-eye small"></i></a>
                                <a href="<?= BASE_URL ?>/admin/articles/<?= $art->id ?>/edit" class="btn btn-xs btn-outline-primary p-1" title="Edit"><i class="bi bi-pencil small"></i></a>
                                <button type="button" class="btn btn-xs btn-outline-danger p-1" title="Hapus"
                                    onclick="confirmDelete('<?= BASE_URL ?>/admin/articles/<?= $art->id ?>/delete', '<?= addslashes($art->title) ?>')">
                                    <i class="bi bi-trash small"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pagination -->
        <?php if($lastPage > 1): ?>
        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-2 px-3">
            <small class="text-muted">Halaman <?= $page ?> dari <?= $lastPage ?></small>
            <nav><ul class="pagination pagination-sm mb-0">
                <?php if($page>1): ?><li class="page-item"><a class="page-link" href="?page=<?=$page-1?>&status=<?=$status?>&search=<?=urlencode($search)?>"><i class="bi bi-chevron-left"></i></a></li><?php endif; ?>
                <?php for($i=max(1,$page-2);$i<=min($lastPage,$page+2);$i++): ?>
                <li class="page-item <?=$i==$page?'active':''?>"><a class="page-link" href="?page=<?=$i?>&status=<?=$status?>&search=<?=urlencode($search)?>"><?=$i?></a></li>
                <?php endfor; ?>
                <?php if($page<$lastPage): ?><li class="page-item"><a class="page-link" href="?page=<?=$page+1?>&status=<?=$status?>&search=<?=urlencode($search)?>"><i class="bi bi-chevron-right"></i></a></li><?php endif; ?>
            </ul></nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-body text-center p-4">
                <i class="bi bi-trash-fill text-danger fs-2 mb-3 d-block"></i>
                <h6 class="fw-bold mb-2">Hapus Artikel?</h6>
                <p class="text-muted small mb-3" id="deleteModalTitle"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="deleteConfirmBtn" class="btn btn-sm btn-danger">Ya, Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function confirmDelete(url, title) {
    document.getElementById('deleteModalTitle').textContent = title;
    document.getElementById('deleteConfirmBtn').href = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
