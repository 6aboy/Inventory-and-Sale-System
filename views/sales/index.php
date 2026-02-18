<?php
require_once '../../config/Database.php';
require_once '../../models/Sales.php';
require_once '../../models/Product.php';
require_once '../../layout/header.php';


$db = new Database();
$conn = $db->getConnection();
Sale::setConnection($conn);
Product::setConnection($conn);

$sales = Sale::all() ?: [];
$userRole = $_SESSION['role'] ?? 'guest'; // get user role


$query = "SELECT 
              SUM(quantity_sold) AS total_quantity,
              SUM(total_price) AS total_price, 
              SUM(total_capital) AS total_capital, 
              SUM(profit) AS total_profit 
          FROM sales";

$stmt = $conn->prepare($query);
$stmt->execute();
$totals = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="container mt-5">
    <h2 class="text-center fw-bold mb-4" style="color: #112D4E;">Sales Records</h2>

    <div class="p-4 bg-white rounded-4 shadow-lg border border-light">
        <table id="salesTable" class="table table-hover table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity Sold</th>
                    <th>Total Price (₱)</th>
                    <th>Total Capital (₱)</th>
                    <th>Profit (₱)</th>
                    <th>Date Sold</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($sales)): ?>
                    <?php foreach ($sales as $sale):
                        $product = Product::find($sale->product_id);
                        ?>
                        <tr>
                            <td><?= $sale->id ?></td>
                            <td><?= htmlspecialchars($product->name ?? 'Unknown') ?></td>
                            <td><?= $sale->quantity_sold ?></td>
                            <td><?= number_format($sale->total_price, 2) ?></td>
                            <td><?= number_format($sale->total_capital, 2) ?></td>
                            <td class="fw-bold text-success"><?= number_format($sale->profit, 2) ?></td>
                            <td><?= date('M d, Y h:i A', strtotime($sale->date_sold)) ?></td>
                            <td> 
                                <?php if ($userRole === 'superadmin'): ?>
                                    <a href="destroy.php?id=<?= $sale->id ?>" class="btn btn-sm text-white delete"
                                        style="background-color: #ff0000ff;" onmouseover="this.style.backgroundColor='#ff4d4d'"
                                        onmouseout="this.style.backgroundColor='#ff0000ff'">Delete
                                    </a>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No sales recorded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td colspan="2" class="text-end fw-bold">TOTAL:</td>
                    <td class="fw-bold text-center"><?= number_format($totals['total_quantity'] ?? 0) ?></td>
                    <td class="fw-bold text-center">₱<?= number_format($totals['total_price'] ?? 0, 2) ?></td>
                    <td class="fw-bold text-center">₱<?= number_format($totals['total_capital'] ?? 0, 2) ?></td>
                    <td class="fw-bold text-success text-center">₱<?= number_format($totals['total_profit'] ?? 0, 2) ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


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
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Proceed',
            cancelButtonText: 'Cancel'
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
</script>

<script>
$(document).ready(function () {
    $('#salesTable').DataTable({
  
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


<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sold = urlParams.get('sold');

    if (sold === '1') {
        Swal.fire({
            icon: 'success',
            title: 'Sale Successful!',
            text: 'The product was sold and stock updated successfully.',
            showConfirmButton: false,
            timer: 2000
        });

       
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, '', cleanUrl);
    }
});
</script>


<?php require '../../layout/footer.php'; ?>