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
$productId = $_GET['id'] ?? null;
if (!$productId) {
  echo "<script>
        Swal.fire({ icon: 'error', title: 'Invalid Product ID', text: 'Please try again.' })
        .then(() => window.location.href='index.php');
    </script>";
  exit;
}

$product = Product::find($productId);
if (!$product) {
  echo "<script>
        Swal.fire({ icon: 'error', title: 'Product Not Found', text: 'Record does not exist.' })
        .then(() => window.location.href='index.php');
    </script>";
  exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['name'] ?? '');
    $unit = trim($_POST['unit'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity_in_stock = intval($_POST['quantity_in_stock'] ?? 0);
    $reorder_level = intval($_POST['reorder_level'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $capital_price = floatval($_POST['capital_price'] ?? 0);


    $errors = [];
    if ($product_name === '') $errors[] = 'Product name is required.';
    if (!in_array($unit, ['kg', 'Sack', 'Pack'])) $errors[] = 'Invalid unit.';

    if (empty($errors)) {
        $updated = $product->update([
            'name' => $product_name,
            'unit' => $unit,
            'category' => $category,
            'quantity_in_stock' => $quantity_in_stock,
            'reorder_level' => $reorder_level,
            'price' => $price,
            'capital_price' => $capital_price
        ]);

        if ($updated) {
           
            $product->refreshStatus();

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Product Updated!',
                    text: 'The product details were updated successfully.',
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => window.location.href='index.php');
            </script>";
            exit;
        } else {
            echo "<script>
                Swal.fire({ icon: 'error', title: 'Update Failed', text: 'No changes were made.' });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                html: '" . implode('<br>', array_map('htmlspecialchars', $errors)) . "'
            });
        </script>";
    }
}

?>

<div class="d-flex justify-content-center align-items-start bg-light"
  style="font-family: 'Poppins', sans-serif; min-height:100vh; padding-top:40px;">
  <div class="p-4 bg-white rounded-5 shadow-lg border border-light"
    style="overflow-x:auto; max-width:520px; padding-top:2rem !important; padding-bottom:2rem !important;">

    <h2 class="text-center mb-4 fw-bold" style="font-size: 2.2rem; color:#112D4E;">
      EDIT PRODUCT
    </h2>

    <form method="POST" action="">
      <div class="mb-3">
        <label for="name" class="form-label fw-semibold" style="font-size:1.1rem; color:#112D4E;">Product Name</label>
        <input type="text" id="name" name="name" class="form-control form-control-lg" required
          value="<?= htmlspecialchars($product->name) ?>">
      </div>

      <div class="mb-3">
        <label for="unit" class="form-label fw-semibold" style="font-size:1.1rem; color:#112D4E;">Unit</label>
        <select id="unit" name="unit" class="form-select form-select-lg" required>
          <option value="" disabled>Select the unit...</option>
          <?php
          $units = ['kg', 'Sack', 'Pack'];
          foreach ($units as $u) {
            $sel = ($product->unit === $u) ? 'selected' : '';
            echo "<option value='$u' $sel>$u</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="category" class="form-label fw-semibold" style="font-size:1.1rem; color:#112D4E;">Category</label>
        <select id="category" name="category" class="form-select form-select-lg" required>
          <?php
          $categories = ['Livestock', 'Dog Food', 'Cat Food'];
          foreach ($categories as $c) {
            $sel = ($product->category === $c) ? 'selected' : '';
            echo "<option value='$c' $sel>$c</option>";
          }
          ?>
        </select>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="price" class="form-label fw-semibold" style="font-size:1.1rem; color:#112D4E;">Price</label>
          <input type="number" step="0.01" id="price" name="price" class="form-control form-control-lg"
            value="<?= htmlspecialchars($product->price) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="capital_price" class="form-label fw-semibold" style="font-size:1.1rem; color:#112D4E;">Capital Price</label>
          <input type="number" step="0.01" id="capital_price" name="capital_price" class="form-control form-control-lg"
            value="<?= htmlspecialchars($product->capital_price) ?>" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="quantity_in_stock" class="form-label fw-semibold" style="font-size:1.1rem; color:#112D4E;">Quantity in Stock</label>
          <input type="number" id="quantity_in_stock" name="quantity_in_stock" class="form-control form-control-lg"
            value="<?= htmlspecialchars($product->quantity_in_stock) ?>" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="reorder_level" class="form-label fw-semibold" style="font-size:1.1rem; color:#112D4E;">Reorder Level</label>
          <input type="number" id="reorder_level" name="reorder_level" class="form-control form-control-lg"
            value="<?= htmlspecialchars($product->reorder_level) ?>" required>
        </div>
      </div>

      <div class="d-flex justify-content-center gap-3 mt-4">
        <button type="submit" class="btn btn-lg px-5 fw-semibold"
          style="background-color:#112D4E; color:white; border:none;">
          Update
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
            text: "Are you sure you want to save these changes?",
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
