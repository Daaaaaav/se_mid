<?php
class DBConnection {
    private $host = 'localhost';
    private $db = 'se_mid';
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
    public function getProducts() {
        $stmt = $this->getConnection()->prepare("SELECT * FROM product");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function saveOrder($orderData) {
        $pdo = $this->getConnection();
        $pdo->beginTransaction();
        
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (cust_ref, order_date, total) VALUES (?, ?, ?)");
            $stmt->execute([$orderData['customer_ref'], $orderData['order_date'], $orderData['total']]);
            $orderId = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO order_products (order_id, product_id, qty, discount, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($orderData['items'] as $item) {
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['qty'],
                    $item['discount'],
                    $item['subtotal']
                ]);
            }
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage()); 
            $pdo->rollBack();
            return false;
        }
    }
}
?>
