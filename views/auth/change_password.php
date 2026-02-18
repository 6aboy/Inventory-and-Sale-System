<?php
require_once '../../config/Database.php';
require_once '../../models/User.php';
include '../../layout/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
User::setConnection($db);

$user = User::find($_SESSION['user_id']);
if (!$user) {
    header('Location: ../authentication/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_pass = trim($_POST['old_password'] ?? '');
    $new_pass = trim($_POST['new_password'] ?? '');
    $confirm_pass = trim($_POST['confirm_password'] ?? '');

    if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
        $msg = ['error', 'All fields are required.'];
    } elseif ($new_pass !== $confirm_pass) {
        $msg = ['error', 'New passwords do not match.'];
    } elseif (!password_verify($old_pass, $user->password)) {
        $msg = ['error', 'Old password is incorrect.'];
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $updated = $user->update(['password' => $hashed]);

        if ($updated) {
            $msg = ['success', 'Password updated successfully!'];
        } else {
            $msg = ['error', 'Failed to update password.'];
        }
    }

    $icon = htmlspecialchars($msg[0], ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($msg[1], ENT_QUOTES, 'UTF-8');

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon: '{$icon}',
        title: '{$title}',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        window.location.href = 'index.php';
    });
    </script>";
    exit;
}
?>


<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="p-5 bg-white rounded-4 shadow-lg border border-light" style="width: 500px;">
    <h2 class="text-center mb-4 fw-bold" style="color: #112D4E;">Change Password</h2>

    <form method="POST">
      <div class="mb-3">
        <label for="old_password" class="form-label fw-semibold" style="color: #112D4E;">Current Password</label>
        <input type="password" class="form-control" id="old_password" name="old_password" required>
      </div>

      <div class="mb-3">
        <label for="new_password" class="form-label fw-semibold" style="color: #112D4E;">New Password</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
      </div>

      <div class="mb-3">
        <label for="confirm_password" class="form-label fw-semibold" style="color: #112D4E;">Confirm New Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
      </div>

      <div class="d-flex justify-content-center gap-3 mt-4">
        <button type="submit" class="btn px-4" style="background-color:#112D4E; color:white; border:none;">
          Update
        </button>
        <a href="index.php" class="btn px-4" style="background-color:white; color:#112D4E; border:2px solid #112D4E;">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>


<?php include '../../layout/footer.php'; ?>
