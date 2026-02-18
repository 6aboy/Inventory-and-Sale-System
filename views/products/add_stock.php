<?php
require_once '../../config/Database.php';
require_once '../../models/Product.php';
require '../../layout/header.php';

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

$db = (new Database())->getConnection();
Product::setConnection($db);

$id = $_GET['id'] ?? null;
$product = Product::find($id);
if (!$product) exit('Product not found.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = max(0, (int)$_POST['quantity']);
    if ($qty <= 0) {
        echo "<script>Swal.fire('Invalid', 'Enter a positive number', 'error')</script>";
    } else {
        if ($product->addStock($qty)) {
            echo "<script>
            Swal.fire({icon:'success', title:'Stock Added', timer:1200, showConfirmButton:false})
            .then(()=> window.location.href = 'index.php?added=1');
            </script>";
            exit;
        } else {
            echo "<script>Swal.fire('Error','Failed to add stock','error')</script>";
        }
    }
}
?>

<div class="d-flex justify-content-center align-items-center py-5 bg-light" style="font-family: 'Poppins', sans-serif;">
    <div class="p-5 bg-white rounded-5 shadow-lg border border-light w-100" style="max-width: 750px;">
        <h2 class="text-center mb-5 fw-bold" style="font-size:2.2rem; color:#112D4E;">
            ADD STOCK: <span class="text-dark fw-bold"><?= htmlspecialchars($product->name) ?></span>
        </h2>

        <div class="mb-4" style="font-size:1.20rem;">
            <label class="form-label fw-semibold" style="color:#112D4E;">Category:</label>
            <p class="form-control-plaintext fw-medium text-dark mb-3">
                <?= htmlspecialchars($product->category) ?>
            </p>

            <label class="form-label fw-semibold" style="color:#112D4E;">Current Stock:</label>
            <p class="form-control-plaintext fw-medium text-dark mb-3">
                <?= htmlspecialchars($product->quantity_in_stock) ?> <?= htmlspecialchars($product->unit) ?>
            </p>
        </div>

        <form method="POST" style="font-size:1.20rem;">
            <div class="mb-4">
                <label for="quantity" class="form-label fw-semibold" style="color:#112D4E;">Quantity to Add:</label>
                <input type="number" name="quantity" id="quantity" class="form-control form-control-lg" min="1" required>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-5">
                <button type="submit" class="btn btn-lg px-5 fw-semibold" style="background-color:#112D4E; color:white; border:none;">
                    Add Stock
                </button>
                <a href="index.php" class="btn btn-lg px-5 fw-semibold" style="background-color:white; color:black; border:2px solid #112D4E;">
                    Cancel
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
            text: "Are you sure you want to add this quantity to the current stock?",
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
