<?php
// core/Model.php

abstract class Model {
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findById(int $id) {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?", [$id]);
    }

    public function findAll(string $where = '1', array $params = [], string $order = 'id DESC', int $limit = 0, int $offset = 0): array {
        $sql = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$order}";
        if ($limit > 0) $sql .= " LIMIT {$limit} OFFSET {$offset}";
        return $this->db->fetchAll($sql, $params);
    }

    public function create(array $data): int {
        return $this->db->insert($this->table, $data);
    }

    public function update(array $data, int $id): int {
        return $this->db->update($this->table, $data, "{$this->primaryKey} = ?", [$id]);
    }

    public function delete(int $id): int {
        return $this->db->delete($this->table, "{$this->primaryKey} = ?", [$id]);
    }

    public function count(string $where = '1', array $params = []): int {
        return $this->db->count($this->table, $where, $params);
    }

    protected function paginate(string $sql, array $params, int $perPage, int $page): array {
        $countSql = preg_replace('/SELECT .+ FROM/i', 'SELECT COUNT(*) as cnt FROM', $sql);
        $countSql = preg_replace('/ORDER BY .+$/i', '', $countSql);
        $total = (int)($this->db->fetch($countSql, $params)->cnt ?? 0);
        $offset = ($page - 1) * $perPage;
        $rows = $this->db->fetchAll($sql . " LIMIT {$perPage} OFFSET {$offset}", $params);
        return [
            'data'         => $rows,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int)ceil($total / $perPage),
        ];
    }
}
