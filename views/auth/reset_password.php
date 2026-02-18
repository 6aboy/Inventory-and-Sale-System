<?php
require_once '../../config/Database.php';
require_once '../../models/User.php';
include '../../layout/header.php';

session_start();

$database = new Database();
$db = $database->getConnection();
User::setConnection($db);

if (!isset($_SESSION['reset_user_id'])) {
    header('Location: forgot_password.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm_password']);

    if ($password === $confirm && strlen($password) >= 6) {
        $user = User::find($_SESSION['reset_user_id']);
        $user->update(['password' => $password]);
        unset($_SESSION['reset_user_id']);

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Password Updated!',
                text: 'You can now log in with your new password.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = 'login.php';
            });
        </script>";
        exit;
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Passwords must match and be at least 6 characters long.'
            });
        </script>";
    }
}
?>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="p-5 bg-white rounded-4 shadow-lg" style="width: 450px;">
    <h2 class="text-center mb-4 fw-bold text-primary">🔒 Reset Password</h2>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" class="form-control" id="password" name="password" required minlength="6">
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-success">Update Password</button>
      </div>
    </form>
  </div>
</div>

<?php include '../../layout/footer.php'; ?>
