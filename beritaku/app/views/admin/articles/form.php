<?php 
$isEdit = !is_null($article ?? null);
$pageTitle = $isEdit ? 'Edit Artikel' : 'Artikel Baru';
$breadcrumb=[['label'=>'Artikel','url'=>BASE_URL.'/admin/articles'],['label'=>$pageTitle,'active'=>true]];
?>
<div class="p-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= BASE_URL ?>/admin/articles" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
        <div>
            <h4 class="fw-bold mb-0"><?= $pageTitle ?></h4>
            <?php if($isEdit): ?><small class="text-muted">ID: <?= $article->id ?> · Dibuat: <?= date('d M Y', strtotime($article->created_at)) ?></small><?php endif; ?>
        </div>
    </div>

    <form action="<?= BASE_URL ?>/admin/articles/<?= $isEdit ? $article->id.'/update' : 'store' ?>" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <!-- Main content -->
            <div class="col-lg-8">
                <!-- Title -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Judul Artikel <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="Tulis judul artikel yang menarik..." 
                                   value="<?= htmlspecialchars($article->title ?? '') ?>" required>
                        </div>
                        <div>
                            <label class="form-label fw-semibold">Ringkasan / Excerpt</label>
                            <textarea name="excerpt" class="form-control" rows="2" placeholder="Ringkasan singkat artikel (opsional)..."><?= htmlspecialchars($article->excerpt ?? '') ?></textarea>
                            <div class="form-text">Tampil di halaman daftar berita. Kosongkan untuk otomatis dari konten.</div>
                        </div>
                    </div>
                </div>

                <!-- Content Editor -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-white border-0 pt-3 pb-0">
                        <label class="form-label fw-semibold">Konten Artikel <span class="text-danger">*</span></label>
                    </div>
                    <div class="card-body pt-2 p-3">
                        <textarea name="content" id="articleContent" class="form-control" rows="15"><?= $isEdit ? $article->content : '' ?></textarea>
                        <div class="form-text mt-1"><i class="bi bi-info-circle me-1"></i>Editor WYSIWYG akan dimuat otomatis. Anda bisa menggunakan HTML jika editor tidak tersedia.</div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Publish Settings -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-white fw-semibold py-3 border-0">
                        <i class="bi bi-send me-2 text-primary"></i>Pengaturan Publish
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="draft" <?= ($article->status ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= ($article->status ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                                <option value="archived" <?= ($article->status ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1"
                                    <?= ($article->is_featured ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="isFeatured">
                                    <strong>Artikel Unggulan</strong><br>
                                    <span class="text-muted">Tampil di hero section</span>
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_breaking" id="isBreaking" value="1"
                                    <?= ($article->is_breaking ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label small" for="isBreaking">
                                    <strong>Breaking News</strong><br>
                                    <span class="text-muted">Tampil di ticker atas</span>
                                </label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 pt-2 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i><?= $isEdit ? 'Perbarui Artikel' : 'Simpan Artikel' ?>
                            </button>
                            <?php if($isEdit && $article->status === 'published'): ?>
                            <a href="<?= BASE_URL ?>/artikel/<?= $article->slug ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye me-1"></i>Lihat di Website
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-white fw-semibold py-3 border-0">
                        <i class="bi bi-tags me-2 text-primary"></i>Kategori & Tags
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= ($article->category_id ?? 0) == $cat->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label small fw-semibold">Tags</label>
                            <div class="tags-checkbox">
                                <?php foreach($tags as $tag): ?>
                                <div class="form-check form-check-inline mb-1">
                                    <input class="form-check-input" type="checkbox" name="tags[]" 
                                           value="<?= $tag->id ?>" id="tag_<?= $tag->id ?>"
                                           <?= in_array($tag->id, $selectedTags ?? []) ? 'checked' : '' ?>>
                                    <label class="form-check-label small" for="tag_<?= $tag->id ?>"><?= htmlspecialchars($tag->name) ?></label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white fw-semibold py-3 border-0">
                        <i class="bi bi-image me-2 text-primary"></i>Gambar Thumbnail
                    </div>
                    <div class="card-body p-3">
                        <?php if($isEdit && $article->thumbnail): ?>
                        <div class="mb-2">
                            <img src="<?= BASE_URL ?>/uploads/<?= $article->thumbnail ?>" class="img-fluid rounded w-100" style="max-height:150px;object-fit:cover" id="thumbPreview">
                        </div>
                        <?php else: ?>
                        <div class="border rounded bg-light d-flex align-items-center justify-content-center mb-2 d-none" style="height:130px" id="thumbPreviewWrap">
                            <img src="" id="thumbPreview" class="img-fluid rounded" style="max-height:130px">
                        </div>
                        <?php endif; ?>
                        <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*" onchange="previewThumb(this)">
                        <div class="form-text">JPG, PNG, WebP. Maks 2MB. Ukuran ideal: 1200×630px.</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewThumb(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const prev = document.getElementById('thumbPreview');
            const wrap = document.getElementById('thumbPreviewWrap');
            prev.src = e.target.result;
            if (wrap) wrap.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
// Init TinyMCE if available
document.addEventListener('DOMContentLoaded', function() {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#articleContent',
            height: 450,
            plugins: 'autolink lists link image charmap preview searchreplace fullscreen media table code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code fullscreen',
            menubar: false,
            branding: false,
            statusbar: false,
            content_style: 'body { font-family: Source Sans 3, sans-serif; font-size: 16px; line-height: 1.6; }'
        });
    }
});
</script>
