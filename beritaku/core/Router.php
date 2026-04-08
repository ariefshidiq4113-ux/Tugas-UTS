<?php
// core/Router.php

class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void {
        $this->routes[] = ['method' => $method, 'path' => $path, 'handler' => $handler];
    }

    public function dispatch(string $method, string $uri): void {
        // Strip query string
        $uri = strtok($uri, '?');
        // Remove base path prefix
        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . ltrim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controllerClass, $action] = $route['handler'];

                // PERBAIKAN: pakai findControllerFile() bukan hardcode path
                $controllerFile = $this->findControllerFile($controllerClass);
                if ($controllerFile === null) {
                    $this->notFound();
                    return;
                }
                require_once $controllerFile;
                $controller = new $controllerClass();
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }
        $this->notFound();
    }

    // METHOD BARU — cari file controller di root & semua subfolder
    private function findControllerFile(string $controllerClass): ?string {
        $baseDir = BASE_PATH . '/app/controllers/';

        // Langkah 1: cek langsung di root controllers/
        $direct = $baseDir . $controllerClass . '.php';
        if (file_exists($direct)) {
            return $direct;
        }

        // Langkah 2: scan rekursif ke subfolder (admin/, dll)
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($baseDir)
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getFilename() === $controllerClass . '.php') {
                return $file->getPathname();
            }
        }

        return null; // tidak ditemukan
    }

    private function notFound(): void {
        http_response_code(404);
        require BASE_PATH . '/app/views/errors/404.php';
    }
}
