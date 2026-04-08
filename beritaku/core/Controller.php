<?php
// core/Controller.php

abstract class Controller {
    protected function view(string $view, array $data = [], ?string $layout = null): void {
        extract($data);
        $viewFile = BASE_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }
        if ($layout) {
            $content = '';
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            $layoutFile = BASE_PATH . '/app/views/layouts/' . $layout . '.php';
            require $layoutFile;
        } else {
            require $viewFile;
        }
    }

    protected function redirect(string $url): void {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    protected function redirectBack(): void {
        $ref = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        header("Location: {$ref}");
        exit;
    }

    protected function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
    protected function isGet():  bool { return $_SERVER['REQUEST_METHOD'] === 'GET'; }

    protected function post(string $key, mixed $default = null): mixed {
        return $_POST[$key] ?? $default;
    }

    protected function get(string $key, mixed $default = null): mixed {
        return $_GET[$key] ?? $default;
    }

    protected function sanitize(string $value): string {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }

    protected function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth(): void {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
    }

    protected function requireAdmin(): void {
        if (!$this->isLoggedIn() || !in_array($_SESSION['user_role'] ?? '', ['admin', 'editor'])) {
            $this->redirect('/auth/login');
        }
    }

    protected function flash(string $type, string $message): void {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(): array {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    protected function generateSlug(string $text): string {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-') . '-' . time();
    }

    protected function uploadImage(string $field, string $dir = 'articles'): ?string {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES[$field]['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed)) return null;
        $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $targetDir = UPLOAD_PATH . $dir . '/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        move_uploaded_file($_FILES[$field]['tmp_name'], $targetDir . $filename);
        return $dir . '/' . $filename;
    }
}
