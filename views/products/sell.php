<?php

require_once '../../config/Database.php';
require_once '../../models/Product.php';
require_once '../../models/Sales.php';
require '../../layout/header.php';

$db = (new Database())->getConnection();
Product::setConnection($db);
Sale::setConnection($db);

$id = $_GET['id'] ?? null;
$product = Product::find($id);


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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity_sold = (int) $_POST['quantity_sold'];

    if ($quantity_sold > 0 && $quantity_sold <= $product->quantity_in_stock) {
        $ok = $product->sell($quantity_sold);

        if ($ok) {
            header("Location: ../sales/index.php?sold=1");
            exit;
        } else {
            $error = "Failed to process sale.";
        }
    } else {
        $error = "Invalid quantity.";
    }
}

?>

<div class="container mt-5" style="font-family: 'Poppins', sans-serif;">
  <div class="bg-white p-5 rounded-4 shadow-lg border border-light">

    <h2 class="fw-bold mb-4 text-center" style="color:#112D4E; font-size:2rem;">SELL PRODUCT</h2>


    <div class="mb-4 p-3 rounded-3 bg-light border">
      <h5 class="fw-bold mb-3" style="color:#112D4E; font-size:1.4rem;">Product Details</h5>
      <div class="row g-3" style="font-size:1.2rem;">
        <div class="col-md-6">
          <p><strong style="color:#112D4E;">Name:</strong> <span class="text-dark"><?= htmlspecialchars($product->name) ?></span></p>
          <p><strong style="color:#112D4E;">Category:</strong> <span class="text-dark"><?= htmlspecialchars($product->category) ?></span></p>
          <p><strong style="color:#112D4E;">Unit:</strong> <span class="text-dark"><?= htmlspecialchars($product->unit) ?></span></p>
        </div>
        <div class="col-md-6">
          <p><strong style="color:#112D4E;">Price:</strong> <span class="text-dark">₱<?= number_format($product->price, 2) ?></span></p>
          <p><strong style="color:#112D4E;">Capital Price:</strong> <span class="text-dark">₱<?= number_format($product->capital_price, 2) ?></span></p>
          <p><strong style="color:#112D4E;">In Stock:</strong> <span class="text-dark"><?= $product->quantity_in_stock ?> <?= htmlspecialchars($product->unit) ?></span></p>
        </div>
      </div>
    </div>


    <form method="POST" class="p-4 rounded-3 border border-light bg-white shadow-sm" style="font-size:1.2rem;">
      <div class="mb-3">
        <label for="quantity_sold" class="form-label fw-bold" style="color:#112D4E;">Quantity to Sell:</label>
        <input type="number" name="quantity_sold" id="quantity_sold" class="form-control"
               min="1" max="<?= $product->quantity_in_stock ?>" required style="font-size:1.2rem;">
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="d-flex justify-content-center gap-3 mt-5">
        <button type="submit" 
            class="btn btn-lg px-5 fw-semibold" 
            style="background-color:#112D4E; color:white; border:none;">
            Confirm Sale
        </button>

        <a href="index.php" 
            class="btn btn-lg px-5 fw-semibold" 
            style="background-color:white; color:black; border:2px solid #112D4E;">
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

<?php require '../../layout/footer.php'; ?>
