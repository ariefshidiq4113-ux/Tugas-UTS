<?php
// app/controllers/ArticleController.php

class ArticleController extends Controller {
    private Article $article;
    private Category $category;
    private Comment $comment;

    public function __construct() {
        $this->article  = new Article();
        $this->category = new Category();
        $this->comment  = new Comment();
    }

    public function show(string $slug): void {
        $article = $this->article->getBySlug($slug);
        if (!$article) { http_response_code(404); $this->view('errors.404', []); return; }
        $this->article->incrementViews($article->id);
        $tags     = $this->article->getTags($article->id);
        $related  = $this->article->getRelated($article->category_id, $article->id, 4);
        $comments = $this->comment->getApproved($article->id);
        $popular  = $this->article->getPopular(5);
        $categories = $this->category->getActive();
        $flash    = $this->getFlash();
        $this->view('articles.show', compact('article', 'tags', 'related', 'comments', 'popular', 'categories', 'flash'), 'public');
    }

    public function category(string $slug): void {
        $cat = $this->category->getBySlug($slug);
        if (!$cat) { http_response_code(404); $this->view('errors.404', []); return; }
        $page    = max(1, (int)$this->get('page', 1));
        $perPage = 12;
        $offset  = ($page - 1) * $perPage;
        $articles  = $this->article->getByCategory($cat->id, $perPage, $offset);
        $total     = $this->article->count('category_id = ? AND status = ?', [$cat->id, 'published']);
        $lastPage  = max(1, (int)ceil($total / $perPage));
        $popular   = $this->article->getPopular(5);
        $categories = $this->category->getActive();
        $this->view('articles.category', compact('cat', 'articles', 'total', 'page', 'lastPage', 'popular', 'categories'), 'public');
    }

    public function postComment(string $slug): void {
        $article = $this->article->getBySlug($slug);
        if (!$article) { $this->redirect('/'); return; }
        $content = trim($this->post('content', ''));
        if (strlen($content) < 3) {
            $this->flash('error', 'Komentar terlalu pendek.');
            $this->redirect('/artikel/' . $slug);
            return;
        }
        $data = [
            'article_id'   => $article->id,
            'user_id'      => $_SESSION['user_id'] ?? null,
            'parent_id'    => $this->post('parent_id') ?: null,
            'author_name'  => $_SESSION['user_name'] ?? $this->sanitize($this->post('author_name', 'Anonim')),
            'author_email' => $_SESSION['user_email'] ?? $this->sanitize($this->post('author_email', '')),
            'content'      => $this->sanitize($content),
            'status'       => $this->isLoggedIn() ? 'approved' : 'pending',
            'ip_address'   => $_SERVER['REMOTE_ADDR'] ?? '',
        ];
        $this->comment->create($data);
        $msg = $this->isLoggedIn() ? 'Komentar berhasil dikirim.' : 'Komentar menunggu persetujuan moderator.';
        $this->flash('success', $msg);
        $this->redirect('/artikel/' . $slug);
    }
}
