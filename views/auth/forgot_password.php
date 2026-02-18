<?php
require_once '../../config/Database.php';
require_once '../../models/User.php';
include '../../layout/header.php';

session_start();

$database = new Database();
$db = $database->getConnection();
User::setConnection($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $user = User::findByUsername($username);

    if ($user) {
      
        $_SESSION['reset_user_id'] = $user->id;

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'User found!',
                text: 'You can now reset your password.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = 'reset_password.php';
            });
        </script>";
        exit;
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'User not found',
                text: 'No account matches that username.'
            });
        </script>";
    }
}
?>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <div class="p-5 bg-white rounded-4 shadow-lg" style="width: 450px;">
    <h2 class="text-center mb-4 fw-bold text-primary">🔑 Forgot Password</h2>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Enter your Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Reset Password</button>
      </div>
    </form>
    <div class="text-center mt-3">
      <a href="login.php" class="text-decoration-none text-secondary">Back to Login</a>
    </div>
  </div>
</div>

<?php include '../../layout/footer.php'; ?>
