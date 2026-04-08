<?php
// app/models/Article.php

class Article extends Model {
    protected string $table = 'articles';

    public function getWithRelations(int $id) {
        return $this->db->fetch("
            SELECT a.*, u.name as author_name, u.avatar as author_avatar, u.bio as author_bio,
                   c.name as category_name, c.slug as category_slug, c.color as category_color
            FROM articles a
            JOIN users u ON a.user_id = u.id
            JOIN categories c ON a.category_id = c.id
            WHERE a.id = ?", [$id]);
    }

    public function getBySlug(string $slug) {
        return $this->db->fetch("
            SELECT a.*, u.name as author_name, u.avatar as author_avatar, u.bio as author_bio,
                   c.name as category_name, c.slug as category_slug, c.color as category_color
            FROM articles a
            JOIN users u ON a.user_id = u.id
            JOIN categories c ON a.category_id = c.id
            WHERE a.slug = ? AND a.status = 'published'", [$slug]);
    }

    public function getPublished(int $limit = 10, int $offset = 0): array {
        return $this->db->fetchAll("
            SELECT a.*, u.name as author_name, c.name as category_name, 
                   c.slug as category_slug, c.color as category_color
            FROM articles a
            JOIN users u ON a.user_id = u.id
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT ? OFFSET ?", [$limit, $offset]);
    }

    public function getFeatured(int $limit = 5): array {
        return $this->db->fetchAll("
            SELECT a.*, u.name as author_name, c.name as category_name,
                   c.slug as category_slug, c.color as category_color
            FROM articles a
            JOIN users u ON a.user_id = u.id
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND a.is_featured = 1
            ORDER BY a.published_at DESC
            LIMIT ?", [$limit]);
    }

    public function getBreaking(int $limit = 5): array {
        return $this->db->fetchAll("
            SELECT a.*, c.name as category_name, c.color as category_color
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND a.is_breaking = 1
            ORDER BY a.published_at DESC
            LIMIT ?", [$limit]);
    }

    public function getByCategory(int $categoryId, int $limit = 10, int $offset = 0): array {
        return $this->db->fetchAll("
            SELECT a.*, u.name as author_name, c.name as category_name,
                   c.slug as category_slug, c.color as category_color
            FROM articles a
            JOIN users u ON a.user_id = u.id
            JOIN categories c ON a.category_id = c.id
            WHERE a.category_id = ? AND a.status = 'published'
            ORDER BY a.published_at DESC
            LIMIT ? OFFSET ?", [$categoryId, $limit, $offset]);
    }

    public function getPopular(int $limit = 5): array {
        return $this->db->fetchAll("
            SELECT a.*, c.name as category_name, c.color as category_color
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published'
            ORDER BY a.views DESC
            LIMIT ?", [$limit]);
    }

    public function getRelated(int $categoryId, int $excludeId, int $limit = 4): array {
        return $this->db->fetchAll("
            SELECT a.*, c.name as category_name, c.color as category_color
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            WHERE a.category_id = ? AND a.id != ? AND a.status = 'published'
            ORDER BY a.published_at DESC LIMIT ?", [$categoryId, $excludeId, $limit]);
    }

    public function search(string $q, int $limit = 10, int $offset = 0): array {
        $q = "%{$q}%";
        return $this->db->fetchAll("
            SELECT a.*, u.name as author_name, c.name as category_name,
                   c.slug as category_slug, c.color as category_color
            FROM articles a
            JOIN users u ON a.user_id = u.id
            JOIN categories c ON a.category_id = c.id
            WHERE a.status = 'published' AND (a.title LIKE ? OR a.excerpt LIKE ? OR a.content LIKE ?)
            ORDER BY a.published_at DESC
            LIMIT ? OFFSET ?", [$q, $q, $q, $limit, $offset]);
    }

    public function countSearch(string $q): int {
        $q = "%{$q}%";
        $row = $this->db->fetch("SELECT COUNT(*) as cnt FROM articles WHERE status = 'published' AND (title LIKE ? OR excerpt LIKE ? OR content LIKE ?)", [$q, $q, $q]);
        return (int)($row->cnt ?? 0);
    }

    public function incrementViews(int $id): void {
        $this->db->query("UPDATE articles SET views = views + 1 WHERE id = ?", [$id]);
    }

    public function getAllAdmin(int $limit = 20, int $offset = 0, string $status = '', string $search = ''): array {
        $where = '1=1';
        $params = [];
        if ($status) { $where .= " AND a.status = ?"; $params[] = $status; }
        if ($search) { $where .= " AND (a.title LIKE ? OR a.excerpt LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
        $params[] = $limit; $params[] = $offset;
        return $this->db->fetchAll("
            SELECT a.*, u.name as author_name, c.name as category_name
            FROM articles a JOIN users u ON a.user_id = u.id JOIN categories c ON a.category_id = c.id
            WHERE {$where} ORDER BY a.created_at DESC LIMIT ? OFFSET ?", $params);
    }

    public function countAdmin(string $status = '', string $search = ''): int {
        $where = '1=1'; $params = [];
        if ($status) { $where .= " AND status = ?"; $params[] = $status; }
        if ($search) { $where .= " AND (title LIKE ? OR excerpt LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
        return $this->db->count('articles', $where, $params);
    }

    public function getTags(int $articleId): array {
        return $this->db->fetchAll("SELECT t.* FROM tags t JOIN article_tags at ON t.id = at.tag_id WHERE at.article_id = ?", [$articleId]);
    }

    public function syncTags(int $articleId, array $tagIds): void {
        $this->db->delete('article_tags', 'article_id = ?', [$articleId]);
        foreach ($tagIds as $tid) {
            $this->db->query("INSERT IGNORE INTO article_tags (article_id, tag_id) VALUES (?, ?)", [$articleId, $tid]);
        }
    }

    public function countByCategory(): array {
        return $this->db->fetchAll("SELECT c.name, COUNT(a.id) as cnt FROM categories c LEFT JOIN articles a ON c.id = a.category_id AND a.status = 'published' GROUP BY c.id ORDER BY cnt DESC");
    }
}
