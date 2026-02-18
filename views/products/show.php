<?php
require_once '../../config/Database.php';
require_once '../../models/Product.php';
include '../../layout/header.php';

$database = new Database();
$db = $database->getConnection();
Product::setConnection($db);

$id = $_GET['id'] ?? null;
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
//     echo "<script>
//         Swal.fire({
//             icon: 'error',
//             title: 'Access Denied',
//             text: 'You do not have permission to view this page.'
//         }).then(() => window.location.href = '../../index.php');
//     </script>";
//     exit;
// }

$product = Product::find($id);
if (!$product) {
    echo "<script>
        Swal.fire({ icon: 'error', title: 'Product Not Found', text: 'The record no longer exists.' })
        .then(() => window.location.href='index.php');
    </script>";
    exit;
}
?>

<div class="d-flex justify-content-center align-items-start py-5 bg-light" style="font-family: 'Poppins', sans-serif;">
    <div class="p-5 bg-white rounded-5 shadow-lg border border-light w-100" style="max-width: 950px;">
        <h2 class="text-center mb-5 fw-bold" style="font-size:2.4rem; color:#112D4E;">
            PRODUCT DETAILS
        </h2>

        <div class="row g-4" style="font-size:1.20rem;">
            <div class="col-md-6">
                <table class="table table-borderless align-middle mb-4">
                    <tbody>
                        <tr>
                            <th class="text-end fw-semibold" style="width:45%; color:#112D4E;">Product ID:</th>
                            <td><?= htmlspecialchars($product->id) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Name:</th>
                            <td><?= htmlspecialchars($product->name) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Category:</th>
                            <td><?= htmlspecialchars($product->category) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Unit:</th>
                            <td><?= htmlspecialchars($product->unit) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Status:</th>
                            <td>
                                <?php
                                $statusClass = match ($product->status) {
                                    'In Stock' => 'badge bg-success',
                                    'Low Stock' => 'badge bg-warning text-dark',
                                    'Out of Stock' => 'badge bg-danger',
                                    default => 'badge bg-secondary'
                                };
                                ?>
                                <span class="<?= $statusClass ?>"><?= htmlspecialchars($product->status) ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-borderless align-middle mb-4">
                    <tbody>
                        <tr>
                            <th class="text-end fw-semibold" style="width:45%; color:#112D4E;">Quantity in Stock:</th>
                            <td><?= htmlspecialchars($product->quantity_in_stock) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Reorder Level:</th>
                            <td><?= htmlspecialchars($product->reorder_level) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Price:</th>
                            <td>₱<?= number_format($product->price, 2) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Capital Price:</th>
                            <td>₱<?= number_format($product->capital_price, 2) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Profit Earned:</th>
                            <td>₱<?= number_format(($product->total_income ?? 0) - ($product->total_capital ?? 0), 2) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="my-5">

        <div class="row g-4" style="font-size:1.20rem;">
            <div class="col-md-6">
                <table class="table table-borderless align-middle mb-4">
                    <tbody>
                        <tr>
                            <th class="text-end fw-semibold" style="width:45%; color:#112D4E;">Total Capital:</th>
                            <td>₱<?= number_format($product->total_capital ?? 0, 2) ?></td>
                        </tr>
                        <tr>
                            <th class="text-end fw-semibold" style="color:#112D4E;">Created At:</th>
                            <td>
                                <?= $product->created_at
                                    ? date('F j, Y · g:i A', strtotime($product->created_at))
                                    : '—' ?>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless align-middle mb-4">
                    <tbody>
                        <tr>
                            <th class="text-end fw-semibold" style="width:45%; color:#112D4E;">Total Income:</th>
                            <td>₱<?= number_format($product->total_income ?? 0, 2) ?></td>
                        </tr>
                        <th class="text-end fw-semibold" style="color:#112D4E;">Updated At:</th>
                        <td>
                            <?= $product->updated_at
                                ? date('F j, Y · g:i A', strtotime($product->updated_at))
                                : '—' ?>
                        </td>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-5">
            <!-- <a href="edit.php?id=<?= $product->id ?>" 
               class="btn btn-lg px-5 fw-semibold" 
               style="background-color:#112D4E; color:white; border:none;">
                Edit -->
            </a>
            <a href="index.php" class="btn btn-lg px-5 fw-semibold"
                style="background-color:white; color:black; border:2px solid #112D4E;">
                Back
            </a>
        </div>
    </div>
</div>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<?php include '../../layout/footer.php'; ?>