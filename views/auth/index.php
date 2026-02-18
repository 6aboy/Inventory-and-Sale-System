<?php
require_once '../../config/Database.php';
require_once '../../models/User.php';
include '../../layout/header.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
User::setConnection($db);

$user = User::find($_SESSION['user_id']);
?>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="p-5 bg-white rounded-4 shadow-lg border border-light" style="width: 600px;">
    <h2 class="text-center mb-4 fw-bold" style="color: #112D4E;">User Dashboard</h2>

    <div class="mb-3">
      <label class="form-label fw-semibold" style="color: #112D4E;">Username</label>
      <p class="form-control bg-light"><?= htmlspecialchars($user->username) ?></p>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold" style="color: #112D4E;">Role</label>
      <p class="form-control bg-light"><?= htmlspecialchars(ucfirst($user->role)) ?></p>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold" style="color: #112D4E;">Created At</label>
      <p class="form-control bg-light"><?= date('F j, Y · g:i A',strtotime($user->created_at)) ?></p>
    </div>

    <div class="d-flex justify-content-center gap-3 mt-4">
      <a href="change_password.php" class="btn px-4 text-white" style="background-color: #ffc107; border: none;">Change Password</a>
      <a href="../auth/logout.php" class="btn px-4 text-white" style="background-color: #112D4E; border: none;">Logout</a>
    </div>
  </div>
</div>


<?php include '../../layout/footer.php'; ?>
