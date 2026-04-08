<?php // ============ CATEGORIES ============
// app/views/admin/categories/index.php
$pageTitle = 'Kelola Kategori'; $breadcrumb=[['label'=>'Kategori','active'=>true]]; ?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola Kategori</h4>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCatModal">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
        </button>
    </div>
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="ps-3">Nama</th><th>Slug</th><th>Warna</th><th>Artikel</th><th>Status</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach($categories as $cat): ?>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle d-inline-block" style="width:12px;height:12px;background:<?= $cat->color ?>"></span>
                                <i class="<?= $cat->icon ?>" style="color:<?= $cat->color ?>"></i>
                                <span class="fw-semibold"><?= htmlspecialchars($cat->name) ?></span>
                            </div>
                        </td>
                        <td><code class="small"><?= htmlspecialchars($cat->slug) ?></code></td>
                        <td><span class="badge" style="background:<?= $cat->color ?>"><?= $cat->color ?></span></td>
                        <td><span class="badge bg-light text-dark border"><?= $cat->article_count ?? 0 ?></span></td>
                        <td><span class="badge bg-<?= $cat->is_active ? 'success' : 'secondary' ?>"><?= $cat->is_active ? 'Aktif' : 'Nonaktif' ?></span></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-xs btn-outline-primary p-1" onclick="editCategory(<?= htmlspecialchars(json_encode($cat)) ?>)"><i class="bi bi-pencil small"></i></button>
                                <?php if(!$cat->article_count): ?>
                                <a href="<?= BASE_URL ?>/admin/categories/<?= $cat->id ?>/delete" class="btn btn-xs btn-outline-danger p-1"
                                   onclick="return confirm('Hapus kategori ini?')"><i class="bi bi-trash small"></i></a>
                                <?php endif; ?>
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

<!-- Add Modal -->
<div class="modal fade" id="addCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form action="<?= BASE_URL ?>/admin/categories/store" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-8"><label class="form-label small fw-semibold">Nama *</label>
                        <input type="text" name="name" class="form-control" required></div>
                        <div class="col-4"><label class="form-label small fw-semibold">Warna</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="#0d6efd"></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control" placeholder="otomatis jika kosong"></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Icon (Bootstrap Icons)</label>
                        <input type="text" name="icon" class="form-control" placeholder="bi-newspaper" value="bi-newspaper"></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form id="editCatForm" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-8"><label class="form-label small fw-semibold">Nama *</label>
                        <input type="text" name="name" id="editName" class="form-control" required></div>
                        <div class="col-4"><label class="form-label small fw-semibold">Warna</label>
                        <input type="color" name="color" id="editColor" class="form-control form-control-color w-100"></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Icon</label>
                        <input type="text" name="icon" id="editIcon" class="form-control"></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Deskripsi</label>
                        <textarea name="description" id="editDesc" class="form-control" rows="2"></textarea></div>
                        <div class="col-12"><div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="editActive" value="1">
                            <label class="form-check-label small" for="editActive">Aktif</label>
                        </div></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function editCategory(cat) {
    document.getElementById('editCatForm').action = '<?= BASE_URL ?>/admin/categories/' + cat.id + '/update';
    document.getElementById('editName').value = cat.name;
    document.getElementById('editColor').value = cat.color;
    document.getElementById('editIcon').value = cat.icon;
    document.getElementById('editDesc').value = cat.description || '';
    document.getElementById('editActive').checked = cat.is_active == 1;
    new bootstrap.Modal(document.getElementById('editCatModal')).show();
}
</script>
