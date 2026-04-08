<?php
// app/models/Category.php
class Category extends Model {
    protected string $table = 'categories';

    public function getActive(): array {
        return $this->findAll('is_active = 1', [], 'sort_order ASC');
    }

    public function getBySlug(string $slug) {
        return $this->db->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }

    public function getWithCount(): array {
        return $this->db->fetchAll("
            SELECT c.*, COUNT(a.id) as article_count
            FROM categories c
            LEFT JOIN articles a ON c.id = a.category_id AND a.status = 'published'
            GROUP BY c.id ORDER BY c.sort_order ASC");
    }
}

// app/models/User.php
class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email) {
        return $this->db->fetch("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function findByUsername(string $username) {
        return $this->db->fetch("SELECT * FROM users WHERE username = ?", [$username]);
    }

    public function getPublicProfile(int $id) {
        return $this->db->fetch("SELECT id, name, username, avatar, bio, role, created_at FROM users WHERE id = ?", [$id]);
    }

    public function emailExists(string $email, int $excludeId = 0): bool {
        $row = $this->db->fetch("SELECT id FROM users WHERE email = ? AND id != ?", [$email, $excludeId]);
        return (bool)$row;
    }

    public function updateProfile(int $id, array $data): void {
        $this->update($data, $id);
    }
}

// app/models/Comment.php
class Comment extends Model {
    protected string $table = 'comments';

    public function getApproved(int $articleId): array {
        return $this->db->fetchAll("
            SELECT c.*, u.name as user_name, u.avatar as user_avatar
            FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.article_id = ? AND c.status = 'approved' AND c.parent_id IS NULL
            ORDER BY c.created_at ASC", [$articleId]);
    }

    public function getReplies(int $parentId): array {
        return $this->db->fetchAll("
            SELECT c.*, u.name as user_name, u.avatar as user_avatar
            FROM comments c LEFT JOIN users u ON c.user_id = u.id
            WHERE c.parent_id = ? AND c.status = 'approved'
            ORDER BY c.created_at ASC", [$parentId]);
    }

    public function getAllAdmin(int $limit = 20, int $offset = 0, string $status = ''): array {
        $where = $status ? "c.status = '{$status}'" : '1=1';
        return $this->db->fetchAll("
            SELECT c.*, a.title as article_title, u.name as user_name
            FROM comments c
            LEFT JOIN articles a ON c.article_id = a.id
            LEFT JOIN users u ON c.user_id = u.id
            WHERE {$where} ORDER BY c.created_at DESC LIMIT ? OFFSET ?", [$limit, $offset]);
    }
}

// app/models/Tag.php
class Tag extends Model {
    protected string $table = 'tags';

    public function getAll(): array { return $this->findAll('1', [], 'name ASC'); }

    public function getBySlug(string $slug) {
        return $this->db->fetch("SELECT * FROM tags WHERE slug = ?", [$slug]);
    }
}

// app/models/Setting.php
class Setting extends Model {
    protected string $table = 'settings';
    private static array $cache = [];

    public static function get(string $key, string $default = ''): string {
        if (isset(self::$cache[$key])) return self::$cache[$key];
        $db = Database::getInstance();
        $row = $db->fetch("SELECT value FROM settings WHERE `key` = ?", [$key]);
        self::$cache[$key] = $row ? $row->value : $default;
        return self::$cache[$key];
    }

    public static function set(string $key, string $value): void {
        $db = Database::getInstance();
        $db->query("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?", [$key, $value, $value]);
        self::$cache[$key] = $value;
    }

    public function getAll(): array {
        $rows = $this->db->fetchAll("SELECT * FROM settings");
        $result = [];
        foreach ($rows as $r) $result[$r->key] = $r->value;
        return $result;
    }
}
