<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory And Sale System</title>
</head>
<body>
<?php
require_once __DIR__ . '/config/Database.php';

$db = new Database();
$conn = $db->getConnection();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic input sanitization
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
    $quantity_in_stock = isset($_POST['quantity_in_stock']) ? (int) $_POST['quantity_in_stock'] : 0;
    $reorder_level = isset($_POST['reorder_level']) ? (int) $_POST['reorder_level'] : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if ($product_name === '') {
        $errors[] = 'Product name is required.';
    }

    $allowed_units = ['kg', 'Sack', 'Pack'];
    if ($unit === '' || !in_array($unit, $allowed_units, true)) {
        $errors[] = 'Unit is required and must be one of: ' . implode(', ', $allowed_units) . '.';
    }

    $allowed_status = ['in_stock', 'low_stock', 'sold_out'];
    if ($status === '' || !in_array($status, $allowed_status, true)) {
        $errors[] = 'Status is required and must be one of: ' . implode(', ', $allowed_status) . '.';
    }

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO products (product_name, unit, quantity_in_stock, reorder_level, status) VALUES (:product_name, :unit, :quantity_in_stock, :reorder_level, :status)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':unit', $unit);
            $stmt->bindParam(':quantity_in_stock', $quantity_in_stock, PDO::PARAM_INT);
            $stmt->bindParam(':reorder_level', $reorder_level, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status);
            $stmt->execute();

            $success = 'Product added successfully.';
            // Clear POST values to avoid resubmission display
            $_POST = [];
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<div style="max-width:700px;margin:40px auto;font-family:Arial,Helvetica,sans-serif">
    <h1>Inventory - Add Product</h1>

    <?php if (!empty($errors)): ?>
        <div style="background:#ffe6e6;border:1px solid #ffb3b3;padding:10px;margin-bottom:15px;color:#900">
            <ul style="margin:0;padding-left:18px">
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div style="background:#e6ffea;border:1px solid #b3ffcc;padding:10px;margin-bottom:15px;color:#060">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="" style="background:#f9f9f9;padding:20px;border:1px solid #eee">
        <div style="margin-bottom:12px">
            <label for="product_name">Product Name</label><br>
            <input id="product_name" name="product_name" type="text" value="<?php echo isset($_POST['product_name']) ? htmlspecialchars($_POST['product_name']) : ''; ?>" required style="width:100%;padding:8px">
        </div>

        <div style="margin-bottom:12px">
            <label for="unit">Unit</label><br>
            <select id="unit" name="unit" required style="width:100%;padding:8px">
                <?php
                $units = ['kg' => 'kg', 'Sack' => 'Sack', 'Pack' => 'Pack'];
                $selUnit = isset($_POST['unit']) ? $_POST['unit'] : '';
                foreach ($units as $k => $v) {
                    $sel = ($selUnit === $k) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($k) . "' $sel>" . htmlspecialchars($v) . "</option>";
                }
                ?>
            </select>
        </div>

        <div style="margin-bottom:12px">
            <label for="quantity_in_stock">Quantity In Stock</label><br>
            <input id="quantity_in_stock" name="quantity_in_stock" type="number" min="0" value="<?php echo isset($_POST['quantity_in_stock']) ? (int)$_POST['quantity_in_stock'] : 0; ?>" style="width:100%;padding:8px">
        </div>

        <div style="margin-bottom:12px">
            <label for="reorder_level">Reorder Level</label><br>
            <input id="reorder_level" name="reorder_level" type="number" min="0" value="<?php echo isset($_POST['reorder_level']) ? (int)$_POST['reorder_level'] : 0; ?>" style="width:100%;padding:8px">
        </div>

        <div style="margin-bottom:12px">
            <label for="status">Status</label><br>
            <select id="status" name="status" required style="width:100%;padding:8px">
                <?php
                $statuses = ['in_stock' => 'In Stock', 'low_stock' => 'Low Stock', 'sold_out' => 'Sold Out'];
                $selStatus = isset($_POST['status']) ? $_POST['status'] : '';
                foreach ($statuses as $k => $v) {
                    $sel = ($selStatus === $k) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($k) . "' $sel>" . htmlspecialchars($v) . "</option>";
                }
                ?>
            </select>
        </div>

        <div>
            <button type="submit" style="padding:10px 16px">Add Product</button>
        </div>
    </form>
</div>
</body>
</html>