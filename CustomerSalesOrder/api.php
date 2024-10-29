<?php
require 'data.php';

header('Content-Type: application/json');

$db = new DBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_products') {
    echo json_encode($db->getProducts());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderData'])) {
    $orderData = json_decode($_POST['orderData'], true);
    $success = $db->saveOrder($orderData);
    echo json_encode(['success' => $success]);
}
?>
