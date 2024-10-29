<?php
class DBConnection {
    private $host = 'localhost';
    private $db = 'se_mid2';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    public function getConnection() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $this->user, $this->pass, $options);
    }

    public function getMaterials() {
        $stmt = $this->getConnection()->prepare("SELECT * FROM material");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>