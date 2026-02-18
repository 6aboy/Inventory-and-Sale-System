<?php
require_once '../../config/Database.php';
require '../../layout/header.php';

$db = new Database();
$conn = $db->getConnection();
require_once '../../models/Product.php';
Product::setConnection($conn);

$products = Product::all() ?: [];
$userRole = $_SESSION['role'] ?? 'guest';
?>

<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold" style="color: #112D4E;">Manage Products</h2>

    <div class="p-4 bg-white rounded-4 shadow-lg border border-light" style="overflow-x:auto;">
        
        <?php if ($userRole === 'superadmin'): ?>
            <div class="d-flex justify-content-end mb-3">
                <a href="create.php" class="btn btn-primary shadow-sm" id="addProductBtn">+ Add Product</a>
            </div>
        <?php endif; ?>

        <table id="productsTable" class="table table-hover table-bordered align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Quantity in Stock</th>
                    <th>Price</th>
                    <th>Capital Price</th>
                    <th>Capital</th>
                    <th>Sales</th>
                    <th>Income</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?php
                        $rowClass = match($product->status) {
                            'Out of Stock' => 'table-danger',
                            'Low Stock' => 'table-warning',
                            default => 'table-success'
                        };

                        $totalCapital = $product->quantity_in_stock * $product->capital_price;
                        $totalSales = $product->quantity_in_stock * $product->price;
                        $income = $totalSales - $totalCapital;
                        ?>
                        <tr class="<?= $rowClass ?>">
                            <td><?= $product->id ?></td>
                            <td><?= htmlspecialchars($product->name) ?></td>
                            <td><?= htmlspecialchars($product->category) ?></td>
                            <td><?= htmlspecialchars($product->unit) ?></td>
                            <td><?= $product->quantity_in_stock ?></td>
                            <td><?= number_format($product->price, 2) ?></td>
                            <td><?= number_format($product->capital_price, 2) ?></td>
                            <td><?= number_format($totalCapital, 2) ?></td>
                            <td><?= number_format($totalSales, 2) ?></td>
                            <td><?= number_format($income, 2) ?></td>
                            <td><?= htmlspecialchars($product->status) ?></td>
                            <td class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="show.php?id=<?= $product->id ?>" 
                                class="btn btn-sm text-white" 
                                style="background-color: #198754;" 
                                onmouseover="this.style.backgroundColor='#239b69'" 
                                onmouseout="this.style.backgroundColor='#198754'">View</a>

                            <?php if ($userRole === 'superadmin'): ?>
                                <a href="edit.php?id=<?= $product->id ?>" 
                                    class="btn btn-sm text-white" 
                                    style="background-color: #ffc107;" 
                                    onmouseover="this.style.backgroundColor='#ffd75a'" 
                                    onmouseout="this.style.backgroundColor='#ffc107'">Edit</a>

                                <a href="destroy.php?id=<?= $product->id ?>" 
                                    class="btn btn-sm text-white delete" 
                                    style="background-color: #ff0000ff;" 
                                    onmouseover="this.style.backgroundColor='#ff4d4d'" 
                                    onmouseout="this.style.backgroundColor='#ff0000ff'">Delete</a>

                                <a href="add_stock.php?id=<?= $product->id ?>" 
                                    class="btn btn-sm text-white" 
                                    style="background-color: #112D4E;" 
                                    onmouseover="this.style.backgroundColor='#163d68'" 
                                    onmouseout="this.style.backgroundColor='#112D4E'">Add Stock</a>

                                <a href="sell.php?id=<?= $product->id ?>" 
                                    class="btn btn-sm text-white" 
                                    style="background-color: #498cc0ff;" 
                                    onmouseover="this.style.backgroundColor='#6da7d4'" 
                                    onmouseout="this.style.backgroundColor='#498cc0ff'">Sell</a>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="12">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).on('click', '.delete', function (e) {
    e.preventDefault();
    const href = $(this).attr('href');

    Swal.fire({
        title: 'Are you sure?',
        text: "This record will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Proceed',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'swal-delete-btn',
            cancelButton: 'swal-cancel-btn'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Product has been deleted successfully.',
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                window.location.href = href;
            }, 1500);
        }
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
        margin: 0 6px !important;
    }
    .swal-confirm-btn:hover {
        background-color: #0d2340 !important;
    }

    .swal-delete-btn {
        background-color: #d33 !important;
        color: white !important;
        border: none !important;
        font-weight: 600 !important;
        padding: 10px 24px !important;
        border-radius: 8px !important;
        font-family: 'Poppins', sans-serif !important;
        margin: 0 6px !important;
    }
    .swal-delete-btn:hover {
        background-color: #b52a2a !important;
    }

    .swal-cancel-btn {
        background-color: white !important;
        color: #112D4E !important;
        border: 2px solid #112D4E !important;
        font-weight: 600 !important;
        padding: 10px 24px !important;
        border-radius: 8px !important;
        font-family: 'Poppins', sans-serif !important;
        margin: 0 6px !important;
    }
    .swal-cancel-btn:hover {
        background-color: #f1f1f1 !important;
    }
`;
document.head.appendChild(style);
</script>


<script>
$(document).ready(function () {
  $('#productsTable').DataTable({
    dom: '<"d-flex justify-content-between align-items-center mb-3"lf<?php echo ($userRole === "superadmin") ? "B" : "" ?>>rtip',
    <?php if ($userRole === 'superadmin'): ?>
    buttons: [
      { extend: 'copy', text: 'Copy', className: 'btn btn-warning btn-sm text-white' },
      { extend: 'csv', text: 'CSV', className: 'btn btn-success btn-sm text-white' },
      { extend: 'excel', text: 'Excel', className: 'btn btn-success btn-sm text-white' },
      { extend: 'pdf', text: 'PDF', className: 'btn btn-danger btn-sm text-white' },
      { extend: 'print', text: 'Print', className: 'btn btn-warning btn-sm text-white' }
    ],
    <?php endif; ?>
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
    pageLength: 10
  });
});
</script>


<?php require '../../layout/footer.php'; ?>
