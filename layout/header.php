<?php ob_start(); ?>
<?php
require_once dirname(__DIR__) . '/permission.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inventory System</title>


  <link rel="stylesheet" href="/../assets\css\bootstrap.min.css">

  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

  <script src="/../assets/js/jquery-3.4.1.min.js"></script>

  <script src="/../assets/js/bootstrap.bundle.min.js"></script>

  <script src="../../assets/js/dataTables.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="../../assets/js/pdfmake.min.js"></script>
  <script src="../../assets/js/vfs_fonts.js"></script>


  <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    #productsTable thead th {
      background-color: #112D4E !important;
      color: #F9F7F7 !important;
      text-align: center;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 14px;
    }


    #addProductBtn {
      background-color: #234C6A;
      color: #F5EFE7;
      transition: all 0.3s ease;
    }

    #addProductBtn:hover {
      background-color: #456882;
      color: white;
      transform: translateY(-4px);
    }

    #salesTable thead th {
      background-color: #112D4E !important;
      color: #F9F7F7 !important;
      text-align: center;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 14px;
    }


    .navbar-custom {
      background-color: #112D4E;
      box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
    }

    .navbar-brand {
      color: #F9F7F7 !important;
      font-weight: bold;
      font-size: 20px;
      letter-spacing: 0.5px;
    }

    .navbar-nav .nav-link {
      color: #DBE2EF !important;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
      color: #1e1e5bff !important;
    }

    body {
      background-color: #F9F7F7;
    }

    .nav-icon {
      transition: 0.3s ease;
    }

    .nav-link:hover .dashboard-icon {
      content: url('/Inventory-and-Sale-System/assets/icons/dashboard-blue.png');
    }

    .nav-link:hover .products-icon {
      content: url('/Inventory-and-Sale-System/assets/icons/products-blue.png');
    }

    .nav-link:hover .sales-icon {
      content: url('/Inventory-and-Sale-System/assets/icons/sales-blue.png');
    }

    .nav-link:hover .profile-icon {
      content: url('/Inventory-and-Sale-System/assets/icons/profile-blue.png');
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-custom mb-4">
    <div class="container-fluid px-4">
      <a class="navbar-brand" href="/Inventory-and-Sale-System/index.php">

        <img src="/Inventory-and-Sale-System/assets/icons/inventory.png" alt="Products" width="20" height="20"
          class="nav-icon inventory-icon">
        Inventory Management
      </a>
      <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item mx-1">
            <a class="nav-link btn btn-outline-light px-3 py-1 rounded-pill d-flex align-items-center gap-2"
              href="/Inventory-and-Sale-System/index.php">
              <img src="/Inventory-and-Sale-System/assets/icons/dashboard.png" alt="Dashboard" width="20" height="20"
                class="nav-icon dashboard-icon">
              Dashboard
            </a>
          </li>

          <li class="nav-item mx-1">
            <a class="nav-link btn btn-outline-light px-3 py-1 rounded-pill d-flex align-items-center gap-2"
              href="/Inventory-and-Sale-System/views/products/index.php">
              <img src="/Inventory-and-Sale-System/assets/icons/products.png" alt="Products" width="20" height="20"
                class="nav-icon products-icon">
              Products
            </a>
          </li>

          <li class="nav-item mx-1">
            <a class="nav-link btn btn-outline-light px-3 py-1 rounded-pill d-flex align-items-center gap-2"
              href="/Inventory-and-Sale-System/views/sales/index.php">
              <img src="/Inventory-and-Sale-System/assets/icons/sales.png" alt="Sales" width="20" height="20"
                class="nav-icon sales-icon">
              Sales
            </a>
          </li>

          <li class="nav-item mx-1">
            <a class="nav-link btn btn-outline-light px-3 py-1 rounded-pill d-flex align-items-center gap-2"
              href="/Inventory-and-Sale-System/views/auth/index.php">
              <img src="/Inventory-and-Sale-System/assets/icons/profile.png" alt="Profile" width="20" height="20"
                class="nav-icon profile-icon">
              Profile
            </a>
          </li>
        </ul>

      </div>

    </div>
  </nav>