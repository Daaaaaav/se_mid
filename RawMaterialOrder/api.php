<?php
include_once 'data.php';

$db = new DBConnection();
$conn = $db->getConnection();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$request_uri = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$table = isset($request_uri[0]) ? $request_uri[0] : null;

switch ($method) {
    case 'GET':
        if ($table === 'material') {
            $stmt = $conn->prepare("SELECT * FROM material");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        } elseif ($table === 'work_order') {
            $stmt = $conn->prepare("SELECT * FROM work_order");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if ($table === 'work_order') {
            try {
                $conn->beginTransaction();
                $stmt = $conn->prepare("INSERT INTO orders (order_no, client, work_date) VALUES (?, ?, ?)");
                $stmt->execute([$input['order_no'], $input['client'], $input['work_date']]);
                $workOrderId = $conn->lastInsertId();
                
                $stmt = $conn->prepare("INSERT INTO order_materials (order_id, material_id, material_name, days, percentage, finish_date) VALUES (?, ?, ?, ?, ?, ?)");
                foreach ($input['materials'] as $material) {
                    $materialName = $material['name'];
                    $finish_date = date('Y-m-d', strtotime($input['work_date'] . " + {$material['days']} days"));
                    $stmt->execute([
                        $workOrderId,
                        $material['id'],
                        $materialName,
                        $material['days'],
                        $material['percentage'],
                        $finish_date
                    ]);
                }
                
                $conn->commit();
                echo json_encode(['id' => $workOrderId]);
            } catch (Exception $e) {
                $conn->rollBack();
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
        break;

    case 'DELETE':
        $materialId = $request_uri[1] ?? null;
        if ($table === 'material' && $materialId) {
            $stmt = $conn->prepare("DELETE FROM material WHERE id = ?");
            $stmt->execute([$materialId]);
            echo json_encode(['message' => 'Material deleted']);
        }
        break;

    default:
        echo json_encode(['message' => 'Method not supported']);
        break;
}
?>