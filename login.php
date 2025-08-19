<?php
include 'config.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT password FROM Utenti WHERE username = ?";
    $params = array($username);

    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if ($stmt && sqlsrv_execute($stmt)) {
        if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {
              $error='';
                header("Location: dashboard.html");
                exit;
            } else {

                $error = "Password errata. Riprova.";
            }
        } else {
            $error = "Utente non trovato. Riprova.";
        }
    } else {
        $error = "Errore nella query: " . print_r(sqlsrv_errors(), true);
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>



<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>




<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="login-box p-4 border rounded shadow-sm bg-white">
      <h2 class="text-center mb-4">Login</h2>
      <form method="POST" action="login.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" id="username" name="username" value="<?php echo $_POST['username'] ?? ''; ?>" class="form-control" placeholder="Username" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required />
          <span class="toggle-password" id="togglePassword">👁️</span>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Accedi</button>
        </div>
      </form>
      <div class="text-center mt-3">
        <p>Non hai un account? <a href="registrazione.php">Registrati qui</a></p>
        <p><?php
          if (!empty($error)) {
          echo "<p style='color:red;'>$error</p>";
          }?>
        </p>
      </div>
    </div>
  </div>
</body>

</html>

<script>
  function visualizza(icon) {
    const input = icon.previousElementSibling;
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    icon.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
  }

  // Collego gli event listener alle icone
  const togglePasswordIcons = document.querySelectorAll('.toggle-password');

  togglePasswordIcons.forEach(icon => {
    icon.addEventListener('click', function () {
      visualizza(this);
    });
  });
</script>

