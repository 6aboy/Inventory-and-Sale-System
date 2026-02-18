<?php
require_once '../../config/Database.php';
require_once '../../models/Sales.php';

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

try {
    $db = new Database();
    $conn = $db->getConnection();
    Sale::setConnection($conn);


    $sale = Sale::find($id);

    if (!$sale) {
        header('Location: index.php?error=not_found');
        exit;
    }


    $sale->delete();

    header('Location: index.php?success=deleted');
    exit;

} catch (PDOException $e) {
    error_log("Delete Sale Error: " . $e->getMessage());
    header('Location: index.php?error=db_error');
    exit;
}
