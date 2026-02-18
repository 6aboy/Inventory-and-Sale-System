<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inventory System</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

  <?php
  require_once '../../config/Database.php';
  require_once '../../models/User.php';

  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
  }

  // Connect database
  $database = new Database();
  $db = $database->getConnection();
  User::setConnection($db);

  $alert = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user by username
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // ✅ Check if password hash matches
      if (password_verify($password, $user['password'])) {

        // Start session for authenticated user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $alert = "
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome back, {$user['username']}!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '../../index.php';
                });
            });
            </script>";
      } else {
        // ❌ Password incorrect
        $alert = "
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Login',
                    text: 'Incorrect password.'
                });
            });
            </script>";
      }
    } else {
      // ❌ No user found
      $alert = "
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Login',
                text: 'Username not found.'
            });
        });
        </script>";
    }
  }
  ?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  </head>

  <body style="font-family: 'Poppins', sans-serif; background-color: #f8f9fa;">

    <div class="d-flex justify-content-center align-items-center vh-100">

      <div class="d-flex rounded-4 shadow-lg overflow-hidden"
        style="width: 900px; height: 500px; background-color: white;">

        <div class="d-flex flex-column justify-content-center text-white position-relative"
          style="flex: 1; background: url('/Inventory-and-Sale-System/assets/js/login1.jpg') center/cover no-repeat;">
          <div style="position: absolute; inset: 0;"></div>
          <div class="position-relative ps-5">
            <h2 class="fw-bold mb-3" style="font-size: 2.2rem;">WELCOME</h2>
            <p style="max-width: 300px; font-size: 1rem; opacity: 0.9;">
              Log in to access your Inventory and Sales System dashboard.
            </p>
          </div>
        </div>

        <div class="d-flex flex-column justify-content-center align-items-center"
          style="flex: 1; background-color: white; padding: 50px;">
          <div style="width: 100%; max-width: 350px;">
            <h3 class="text-center fw-bold mb-4" style="color:#112D4E;">Log In</h3>
            <form method="POST" action="">
              <div class="mb-3">
                <label for="username" class="form-label fw-semibold" style="color:#112D4E;">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label fw-semibold" style="color:#112D4E;">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn fw-semibold py-2"
                  style="background-color:#112D4E; color:white; border:none;">
                  Login
                </button>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>

  </body>

  </html>


  <?= $alert ?>
</body>

</html>