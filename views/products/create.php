<?php
require_once '../../config/Database.php';
require_once '../../models/Product.php';
include '../../layout/header.php';

$database = new Database();
$db = $database->getConnection();
Product::setConnection($db);

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

$errors = [];
$old = [
    'name' => '',
    'unit' => '',
    'category' => '',
    'quantity_in_stock' => 0,
    'reorder_level' => 0,
    'capital_price' => 0.00,
    'price' => 0.00
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = trim($_POST['name'] ?? '');
    $old['unit'] = trim($_POST['unit'] ?? '');
    $old['category'] = trim($_POST['category'] ?? '');
    $old['quantity_in_stock'] = (int) ($_POST['quantity_in_stock'] ?? 0);
    $old['reorder_level'] = (int) ($_POST['reorder_level'] ?? 0);
    $old['capital_price'] = (float) ($_POST['capital_price'] ?? 0);
    $old['price'] = (float) ($_POST['price'] ?? 0);


    if ($old['name'] === '')
        $errors[] = 'Product name is required.';
    if (!in_array($old['unit'], ['kg', 'Sack', 'Pack'], true))
        $errors[] = 'Invalid unit selected.';
    if ($old['capital_price'] <= 0)
        $errors[] = 'Capital price must be greater than 0.';
    if ($old['price'] <= 0)
        $errors[] = 'Selling price must be greater than 0.';


    if (empty($errors)) {
        try {
            $product = Product::createProduct([
                'name' => $old['name'],
                'unit' => $old['unit'],
                'category' => $old['category'],
                'quantity_in_stock' => $old['quantity_in_stock'],
                'reorder_level' => $old['reorder_level'],
                'capital_price' => $old['capital_price'],
                'price' => $old['price'],
            ]);

            if ($product) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Created!',
                        text: 'The product was added successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => window.location.href = 'index.php');
                </script>";
                exit;
            } else {
                $errors[] = 'Failed to create product.';
            }
        } catch (Exception $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<div class="d-flex justify-content-center align-items-start bg-light"
    style="font-family: 'Poppins', sans-serif; min-height:100vh; padding-top:40px;">

    <div class="p-4 bg-white rounded-5 shadow-lg border border-light w-100"
        style="max-width:720px; padding-top:1.5rem !important; padding-bottom:1.5rem !important;">

        <h2 class="text-center mb-4 fw-bold" style="font-size:2.2rem; color:#112D4E;">CREATE PRODUCT</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" style="font-size:1.1rem;">
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold" style="color:#112D4E;">Product Name</label>
                <input type="text" class="form-control form-control-lg" id="name" name="name" required
                    value="<?= htmlspecialchars($old['name']) ?>">
            </div>

            <div class="mb-3">
                <label for="unit" class="form-label fw-semibold" style="color:#112D4E;">Unit</label>
                <select class="form-select form-select-lg" id="unit" name="unit" required>
                    <option value="" disabled <?= $old['unit'] === '' ? 'selected' : '' ?>>Select the unit...</option>

                    <?php
                    $units = ['kg' => 'kg', 'Sack' => 'Sack', 'Pack' => 'Pack'];
                    foreach ($units as $k => $v) {
                        $sel = ($old['unit'] === $k) ? 'selected' : '';
                        echo "<option value=\"$k\" $sel>$v</option>";
                    }
                    ?>
                </select>

            </div>

            <div class="mb-3">
                <label for="category" class="form-label fw-semibold" style="color:#112D4E;">Category</label>
                <select class="form-select form-select-lg" id="category" name="category" required>
                    <option value="" disabled <?= $old['category'] === '' ? 'selected' : '' ?>>Select a Category...
                    </option>
                    <option value="Livestock" <?= $old['category'] === 'Livestock' ? 'selected' : '' ?>>Livestock (Chicken,
                        Pig, Cow)</option>
                    <option value="Dog Food" <?= $old['category'] === 'Dog Food' ? 'selected' : '' ?>>Dog Food</option>
                    <option value="Cat Food" <?= $old['category'] === 'Cat Food' ? 'selected' : '' ?>>Cat Food</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="capital_price" class="form-label fw-semibold" style="color:#112D4E;">Capital Price
                        (₱)</label>
                    <input type="number" step="0.01" min="0" class="form-control form-control-lg" id="capital_price"
                        name="capital_price" value="<?= htmlspecialchars($old['capital_price']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label fw-semibold" style="color:#112D4E;">Selling Price (₱)</label>
                    <input type="number" step="0.01" min="0" class="form-control form-control-lg" id="price"
                        name="price" value="<?= htmlspecialchars($old['price']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="quantity_in_stock" class="form-label fw-semibold" style="color:#112D4E;">Quantity In
                        Stock</label>
                    <input type="number" min="0" class="form-control form-control-lg" id="quantity_in_stock"
                        name="quantity_in_stock" value="<?= (int) $old['quantity_in_stock'] ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="reorder_level" class="form-label fw-semibold" style="color:#112D4E;">Reorder
                        Level</label>
                    <input type="number" min="0" class="form-control form-control-lg" id="reorder_level"
                        name="reorder_level" value="<?= (int) $old['reorder_level'] ?>">
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-lg px-5 fw-semibold"
                    style="background-color:#112D4E; color:white; border:none;">
                    Create
                </button>
                <a href="index.php" class="btn btn-lg px-5 fw-semibold"
                    style="background-color:white; color:black; border:2px solid #112D4E;">
                    Back
                </a>
            </div>
        </form>
    </div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");

    form.addEventListener("submit", function(e) {
        e.preventDefault(); 

        Swal.fire({
            title: 'Would you like to continue?',
            text: "You are about to add a new product to the inventory.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Proceed',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

const style = document.createElement('style');
style.innerHTML = `
    .swal-confirm-btn {
        background-color: #112D4E !important;
        color: white !important;
        border: none !important;
        font-weight: 600 !important;
        padding: 10px 24px !important;
        border-radius: 8px !important;
        font-family: 'Poppins', sans-serif !important;
        margin: 0 6px !important; /* spacing between buttons */
    }
    .swal-confirm-btn:hover {
        background-color: #0d2340 !important;
    }
    .swal-cancel-btn {
        background-color: white !important;
        color: #112D4E !important;
        border: 2px solid #112D4E !important;
        font-weight: 600 !important;
        padding: 10px 24px !important;
        border-radius: 8px !important;
        font-family: 'Poppins', sans-serif !important;
        margin: 0 6px !important; /* spacing between buttons */
    }
    .swal-cancel-btn:hover {
        background-color: #f1f1f1 !important;
    }
`;
document.head.appendChild(style);
</script>


<?php include '../../layout/footer.php'; ?>