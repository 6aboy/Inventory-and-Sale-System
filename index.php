<?php
require_once 'config/Database.php';
require_once 'models/Sales.php';
require_once 'models/Product.php';
require_once 'permission.php';
include 'layout/header.php';

$database = new Database();
$db = $database->getConnection();
Sale::setConnection($db);


$userRole = $_SESSION['role'] ?? 'guest';


$stmt = $db->query("
    SELECT 
        SUM(quantity_sold) AS total_items_sold,
        SUM(total_price) AS total_sales,
        SUM(total_capital) AS total_capital,
        SUM(profit) AS total_profit
    FROM sales
    WHERE DATE(date_sold) = CURDATE()
");
$daily = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="text-center fw-bold mb-4" style="color: #112D4E;">Daily Sales Report</h2>

    <div class="row g-4 text-center">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="fw-semibold">Total Sales</h5>
                <p class="fs-4 fw-bold" style="color: #198754;">₱<?= number_format($daily['total_sales'] ?? 0, 2) ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="fw-semibold">Total Capital</h5>
                <p class="fs-4 fw-bold" style="color: #ff0000;">₱<?= number_format($daily['total_capital'] ?? 0, 2) ?>
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="fw-semibold">Profit</h5>
                <p class="fs-4 fw-bold" style="color: #112D4E;">₱<?= number_format($daily['total_profit'] ?? 0, 2) ?>
                </p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="fw-semibold">Items Sold</h5>
                <p class="fs-4 fw-bold" style="color: #456882;"><?= $daily['total_items_sold'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <hr class="my-5">

<div class="container mt-5">
    <h2 class="text-center fw-bold mb-4" style="color: #112D4E;">Today's Transactions</h2>


    <div class="p-4 bg-white rounded-4 shadow-lg border border-light" style="overflow-x:auto;">
        <table id="salesTable" class="table table-hover table-bordered align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Quantity Sold</th>
                    <th>Total Price</th>
                    <th>Total Capital</th>
                    <th>Profit</th>
                    <th>Date Sold</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $db->query("SELECT * FROM sales WHERE DATE(date_sold) = CURDATE() ORDER BY date_sold DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    $product = Product::find($row['product_id']);
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($product->name ?? 'Unknown') ?></td>
                        <td><?= $row['quantity_sold'] ?></td>
                        <td>₱<?= number_format($row['total_price'], 2) ?></td>
                        <td>₱<?= number_format($row['total_capital'], 2) ?></td>
                        <td class="fw-bold text-success">₱<?= number_format($row['profit'], 2) ?></td>
                        <td><?= date('M d, Y h:i A', strtotime($row['date_sold'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>


            <tfoot class="table-light">
                <tr>
                    <td colspan="2" class="text-end fw-bold">TOTAL:</td>
                    <td class="fw-bold text-center"><?= number_format($daily['total_items_sold'] ?? 0) ?></td>
                    <td class="fw-bold text-center">₱<?= number_format($daily['total_sales'] ?? 0, 2) ?></td>
                    <td class="fw-bold text-center">₱<?= number_format($daily['total_capital'] ?? 0, 2) ?></td>
                    <td class="fw-bold text-success text-center">₱<?= number_format($daily['total_profit'] ?? 0, 2) ?></td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

<?php include 'layout/footer.php'; ?>