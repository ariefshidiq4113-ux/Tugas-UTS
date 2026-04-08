<?php
// app/controllers/admin/AdminController.php

class AdminController extends Controller {
    private Article  $article;
    private Category $category;
    private User     $user;
    private Comment  $comment;

    public function __construct() {
        $this->requireAdmin();
        $this->article  = new Article();
        $this->category = new Category();
        $this->user     = new User();
        $this->comment  = new Comment();
    }

    /* ===================== DASHBOARD ===================== */
    public function dashboard(): void {
        $stats = [
            'articles'   => $this->article->count(),
            'published'  => $this->article->count('status = ?', ['published']),
            'draft'      => $this->article->count('status = ?', ['draft']),
            'users'      => $this->user->count(),
            'comments'   => $this->comment->count(),
            'pending'    => $this->comment->count('status = ?', ['pending']),
            'categories' => $this->category->count(),
            'views'      => $this->getTotalViews(),
        ];
        $latestArticles = $this->article->getAllAdmin(8, 0);
        $topArticles    = $this->article->getPopular(5);
        $latestComments = $this->comment->getAllAdmin(5, 0, 'pending');
        $catStats       = $this->article->countByCategory();
        $flash = $this->getFlash();
        $this->view('admin.dashboard', compact('stats', 'latestArticles', 'topArticles', 'latestComments', 'catStats', 'flash'), 'admin');
    }

    private function getTotalViews(): int {
        $row = Database::getInstance()->fetch("SELECT SUM(views) as total FROM articles");
        return (int)($row->total ?? 0);
    }

    /* ===================== ARTICLES ===================== */
    public function articles(): void {
        $page    = max(1, (int)($this->get('page', 1)));
        $perPage = 15;
        $offset  = ($page - 1) * $perPage;
        $status  = $this->get('status', '');
        $search  = $this->get('search', '');
        $articles = $this->article->getAllAdmin($perPage, $offset, $status, $search);
        $total    = $this->article->countAdmin($status, $search);
        $lastPage = max(1, (int)ceil($total / $perPage));
        $flash = $this->getFlash();
        $this->view('admin.articles.index', compact('articles', 'total', 'page', 'lastPage', 'status', 'search', 'flash'), 'admin');
    }

    public function createArticle(): void {
        $categories = $this->category->getActive();
        $tags       = (new Tag())->getAll();
        $flash      = $this->getFlash();
        $this->view('admin.articles.form', compact('categories', 'tags', 'flash') + ['article' => null, 'selectedTags' => []], 'admin');
    }

    public function storeArticle(): void {
        $data = [
            'user_id'     => $_SESSION['user_id'],
            'category_id' => (int)$this->post('category_id'),
            'title'       => $this->sanitize($this->post('title', '')),
            'slug'        => $this->generateSlug($this->post('title', '')),
            'excerpt'     => $this->sanitize($this->post('excerpt', '')),
            'content'     => $this->post('content', ''),
            'status'      => $this->post('status', 'draft'),
            'is_featured' => (int)(bool)$this->post('is_featured'),
            'is_breaking' => (int)(bool)$this->post('is_breaking'),
            'published_at'=> $this->post('status') === 'published' ? date('Y-m-d H:i:s') : null,
        ];
        if (!$data['title'] || !$data['content'] || !$data['category_id']) {
            $this->flash('error', 'Judul, konten, dan kategori wajib diisi.');
            $this->redirect('/admin/articles/create'); return;
        }
        $thumb = $this->uploadImage('thumbnail', 'articles');
        if ($thumb) $data['thumbnail'] = $thumb;
        $id = $this->article->create($data);
        $tagIds = array_filter(array_map('intval', (array)$this->post('tags', [])));
        if ($tagIds) $this->article->syncTags($id, $tagIds);
        $this->flash('success', 'Artikel berhasil dibuat.');
        $this->redirect('/admin/articles');
    }

    public function editArticle(int $id): void {
        $article = $this->article->findById($id);
        if (!$article) { $this->redirect('/admin/articles'); return; }
        $categories   = $this->category->getActive();
        $tags         = (new Tag())->getAll();
        $selectedTags = array_column($this->article->getTags($id), 'id');
        $flash = $this->getFlash();
        $this->view('admin.articles.form', compact('article', 'categories', 'tags', 'selectedTags', 'flash'), 'admin');
    }

    public function updateArticle(int $id): void {
        $article = $this->article->findById($id);
        if (!$article) { $this->redirect('/admin/articles'); return; }
        $data = [
            'category_id' => (int)$this->post('category_id'),
            'title'       => $this->sanitize($this->post('title', '')),
            'excerpt'     => $this->sanitize($this->post('excerpt', '')),
            'content'     => $this->post('content', ''),
            'status'      => $this->post('status', 'draft'),
            'is_featured' => (int)(bool)$this->post('is_featured'),
            'is_breaking' => (int)(bool)$this->post('is_breaking'),
        ];
        if ($data['status'] === 'published' && $article->status !== 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        $thumb = $this->uploadImage('thumbnail', 'articles');
        if ($thumb) $data['thumbnail'] = $thumb;
        $this->article->update($data, $id);
        $tagIds = array_filter(array_map('intval', (array)$this->post('tags', [])));
        $this->article->syncTags($id, $tagIds);
        $this->flash('success', 'Artikel berhasil diperbarui.');
        $this->redirect('/admin/articles');
    }

    public function deleteArticle(int $id): void {
        $this->article->delete($id);
        $this->flash('success', 'Artikel berhasil dihapus.');
        $this->redirect('/admin/articles');
    }

    /* ===================== CATEGORIES ===================== */
    public function categories(): void {
        $categories = $this->category->getWithCount();
        $flash = $this->getFlash();
        $this->view('admin.categories.index', compact('categories', 'flash'), 'admin');
    }

    public function storeCategory(): void {
        $name  = $this->sanitize($this->post('name', ''));
        $slug  = $this->sanitize($this->post('slug', '')) ?: $this->generateSlug($name);
        if (!$name) { $this->flash('error', 'Nama kategori wajib diisi.'); $this->redirect('/admin/categories'); return; }
        $this->category->create([
            'name' => $name, 'slug' => $slug,
            'description' => $this->sanitize($this->post('description', '')),
            'color' => $this->post('color', '#0d6efd'),
            'icon'  => $this->sanitize($this->post('icon', 'bi-newspaper')),
        ]);
        $this->flash('success', 'Kategori berhasil ditambahkan.');
        $this->redirect('/admin/categories');
    }

    public function updateCategory(int $id): void {
        $this->category->update([
            'name'        => $this->sanitize($this->post('name', '')),
            'description' => $this->sanitize($this->post('description', '')),
            'color'       => $this->post('color', '#0d6efd'),
            'icon'        => $this->sanitize($this->post('icon', 'bi-newspaper')),
            'is_active'   => (int)(bool)$this->post('is_active'),
        ], $id);
        $this->flash('success', 'Kategori berhasil diperbarui.');
        $this->redirect('/admin/categories');
    }

    public function deleteCategory(int $id): void {
        $this->category->delete($id);
        $this->flash('success', 'Kategori berhasil dihapus.');
        $this->redirect('/admin/categories');
    }

    /* ===================== USERS ===================== */
    public function users(): void {
        $page   = max(1, (int)$this->get('page', 1));
        $perPage = 15; $offset = ($page-1)*$perPage;
        $users  = $this->user->findAll('1', [], 'created_at DESC', $perPage, $offset);
        $total  = $this->user->count();
        $lastPage = max(1, (int)ceil($total/$perPage));
        $flash = $this->getFlash();
        $this->view('admin.users.index', compact('users', 'total', 'page', 'lastPage', 'flash'), 'admin');
    }

    public function updateUser(int $id): void {
        $this->user->update(['role' => $this->post('role', 'user'), 'is_active' => (int)(bool)$this->post('is_active')], $id);
        $this->flash('success', 'User berhasil diperbarui.');
        $this->redirect('/admin/users');
    }

    public function deleteUser(int $id): void {
        if ($id === (int)$_SESSION['user_id']) { $this->flash('error', 'Tidak bisa menghapus akun sendiri.'); $this->redirect('/admin/users'); return; }
        $this->user->delete($id);
        $this->flash('success', 'User berhasil dihapus.');
        $this->redirect('/admin/users');
    }

    /* ===================== COMMENTS ===================== */
    public function comments(): void {
        $page = max(1, (int)$this->get('page', 1));
        $perPage = 20; $offset = ($page-1)*$perPage;
        $status   = $this->get('status', '');
        $comments = $this->comment->getAllAdmin($perPage, $offset, $status);
        $total    = $this->comment->count($status ? "status = '{$status}'" : '1');
        $lastPage = max(1, (int)ceil($total/$perPage));
        $flash = $this->getFlash();
        $this->view('admin.comments.index', compact('comments', 'total', 'page', 'lastPage', 'status', 'flash'), 'admin');
    }

    public function approveComment(int $id): void {
        $this->comment->update(['status' => 'approved'], $id);
        $this->flash('success', 'Komentar disetujui.');
        $this->redirectBack();
    }

    public function deleteComment(int $id): void {
        $this->comment->delete($id);
        $this->flash('success', 'Komentar dihapus.');
        $this->redirectBack();
    }

    /* ===================== SETTINGS ===================== */
    public function settings(): void {
        $settings = (new Setting())->getAll();
        $flash = $this->getFlash();
        $this->view('admin.settings', compact('settings', 'flash'), 'admin');
    }

    public function saveSettings(): void {
        $keys = ['site_name','site_tagline','site_description','site_email','site_phone','articles_per_page','comment_moderation','breaking_news'];
        foreach ($keys as $key) {
            Setting::set($key, $this->post($key, ''));
        }
        $this->flash('success', 'Pengaturan berhasil disimpan.');
        $this->redirect('/admin/settings');
    }
}
