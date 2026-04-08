<?php
// app/controllers/HomeController.php

class HomeController extends Controller {
    private Article $article;
    private Category $category;

    public function __construct() {
        $this->article  = new Article();
        $this->category = new Category();
    }

    public function index(): void {
        $featured   = $this->article->getFeatured(4);
        $latest     = $this->article->getPublished(12);
        $popular    = $this->article->getPopular(5);
        $breaking   = $this->article->getBreaking(5);
        $categories = $this->category->getWithCount();
        $categorized = [];
        foreach ($categories as $cat) {
            if ($cat->article_count > 0) {
                $categorized[$cat->id] = [
                    'info'     => $cat,
                    'articles' => $this->article->getByCategory($cat->id, 4),
                ];
            }
        }
        $this->view('home.index', compact('featured', 'latest', 'popular', 'breaking', 'categories', 'categorized'), 'public');
    }

    public function search(): void {
        $q      = trim($this->get('q', ''));
        $page   = max(1, (int)$this->get('page', 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $articles = $q ? $this->article->search($q, $perPage, $offset) : [];
        $total    = $q ? $this->article->countSearch($q) : 0;
        $lastPage = $total > 0 ? ceil($total / $perPage) : 1;
        $categories = $this->category->getActive();
        $this->view('home.search', compact('q', 'articles', 'total', 'page', 'lastPage', 'categories'), 'public');
    }
}
