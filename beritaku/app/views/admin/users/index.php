<?php // app/views/admin/users/index.php
$pageTitle = 'Kelola Pengguna'; $breadcrumb=[['label'=>'Pengguna','active'=>true]]; ?>
<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><h4 class="fw-bold mb-1">Kelola Pengguna</h4>
        <p class="text-muted small mb-0"><?= $total ?> pengguna terdaftar</p></div>
    </div>
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th class="ps-3">Pengguna</th><th>Email</th><th>Role</th><th>Status</th><th>Bergabung</th><th class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= $u->avatar && $u->avatar !== 'default.png' ? BASE_URL.'/uploads/avatars/'.$u->avatar : BASE_URL.'/img/avatar.png' ?>"
                                     class="rounded-circle" width="36" height="36" style="object-fit:cover" onerror="this.src='<?= BASE_URL ?>/img/avatar.png'">
                                <div>
                                    <div class="fw-semibold small"><?= htmlspecialchars($u->name) ?></div>
                                    <small class="text-muted">@<?= htmlspecialchars($u->username) ?></small>
                                </div>
                            </div>
                        </td>
                        <td><small class="text-muted"><?= htmlspecialchars($u->email) ?></small></td>
                        <td>
                            <?php $rc=['admin'=>'danger','editor'=>'primary','user'=>'secondary']; ?>
                            <span class="badge bg-<?= $rc[$u->role] ?? 'secondary' ?>"><?= ucfirst($u->role) ?></span>
                        </td>
                        <td><span class="badge bg-<?= $u->is_active ? 'success' : 'secondary' ?>"><?= $u->is_active ? 'Aktif' : 'Nonaktif' ?></span></td>
                        <td><small class="text-muted"><?= date('d M Y', strtotime($u->created_at)) ?></small></td>
                        <td class="text-center">
                            <?php if($u->id != $_SESSION['user_id']): ?>
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-xs btn-outline-primary p-1" onclick="editUser(<?= $u->id ?>, '<?= $u->role ?>', <?= $u->is_active ?>)">
                                    <i class="bi bi-pencil small"></i>
                                </button>
                                <a href="<?= BASE_URL ?>/admin/users/<?= $u->id ?>/delete" class="btn btn-xs btn-outline-danger p-1"
                                   onclick="return confirm('Hapus user ini?')"><i class="bi bi-trash small"></i></a>
                            </div>
                            <?php else: ?>
                            <span class="badge bg-light text-muted border">Anda</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form id="editUserForm" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Edit Pengguna</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Role</label>
                        <select name="role" id="editUserRole" class="form-select form-select-sm">
                            <option value="user">User</option>
                            <option value="editor">Editor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="editUserActive" value="1">
                        <label class="form-check-label small" for="editUserActive">Akun Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function editUser(id, role, isActive) {
    document.getElementById('editUserForm').action = '<?= BASE_URL ?>/admin/users/' + id + '/update';
    document.getElementById('editUserRole').value = role;
    document.getElementById('editUserActive').checked = isActive == 1;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
</script>
