<?php
require_once '../../config/Database.php';
require_once '../../models/Product.php';

session_start([
    'cookie_lifetime' => 86400,
]);

// optional auth guard
// if (!isset($_SESSION['email'])) {
//     header('Location: ../authentication/login.php');
//     exit();
// }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Access Denied',
            text: 'You do not have permission to view this page.'
        }).then(() => window.location.href = '../../index.php');
    </script>";
    exit;
}

$id = intval($_GET['id']);

$database = new Database();
$db = $database->getConnection();

Product::setConnection($db);

$product = Product::find($id);

if (empty($product)) {
    header('Location: index.php?deleted=0&reason=notfound');
    exit();
}

try {
    // attempt delete (ignore delete() return value)
    $product->delete();
} catch (Exception $e) {
    error_log('destroy.php exception: ' . $e->getMessage());
    header('Location: index.php?deleted=0');
    exit();
}

// verify deletion by checking DB state
$still = Product::find($id);

if (empty($still)) {
    header('Location: index.php?deleted=1');
    exit();
} else {
    // still exists => failure
    header('Location: index.php?deleted=0');
    exit();
}
?>