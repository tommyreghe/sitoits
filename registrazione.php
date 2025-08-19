<?php
// Includi il file di configurazione per la connessione
include 'config.php';

// Inizializza variabile errore
$error = "";

// Verifica se il modulo è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prendi i dati dal modulo (username e password)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Controllo che i campi non siano vuoti
    if (empty($username) || empty($password)) {
        $error = "Username e password sono obbligatori.";
    } else {
        // Controllo se l'username esiste già nel database
        $checkSql = "SELECT COUNT(*) AS count FROM utenti WHERE username = ?";
        $checkStmt = sqlsrv_prepare($conn, $checkSql, array($username));

        if (!$checkStmt) {
            $error = "Errore nella preparazione della query: " . print_r(sqlsrv_errors(), true);
        } else {
            if (sqlsrv_execute($checkStmt)) 
            {
                $row = sqlsrv_fetch_array($checkStmt, SQLSRV_FETCH_ASSOC);
                if ($row['count'] > 0) {
                    $error = "Account già esistente.";
                } 
                else {
                    // Sicurezza: hash della password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Query per inserire i dati
                    $sql = "INSERT INTO utenti (username, password) VALUES (?, ?)";
                    $stmt = sqlsrv_prepare($conn, $sql, array(&$username, &$hashedPassword));

                    if (!$stmt) {
                        $error = "Errore nella preparazione della query di inserimento: " . print_r(sqlsrv_errors(), true);
                    } else {
                        if (sqlsrv_execute($stmt)) {
                            header("Location: login.php");
                            exit;
                        } else {
                            $error = "Errore nell'inserimento dell'utente: " . print_r(sqlsrv_errors(), true);
                        }
                    }
                }
            } else {
                $error = "Errore nell'esecuzione della query: " . print_r(sqlsrv_errors(), true);
            }
        }
    }
    // Chiudi la connessione
    sqlsrv_close($conn);
}
?>












<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inserisci Utente</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
    }
    .position-relative {
      position: relative;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="registration-box p-4 border rounded shadow-sm bg-white">
      <h2 class="text-center mb-4">Crea un account</h2>
      <form id="registrationForm" method="POST" action="registrazione.php">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" id="username" name="username" class="form-control" placeholder="Inserisci username" required>
        </div>
        <div class="mb-3 position-relative">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Inserisci password" required>
          <span class="toggle-password" id="togglePassword">👁️</span>
        </div>
        <div class="mb-3 position-relative">
          <label for="confirm_password" class="form-label">Conferma</label>
          <input type="password" class="form-control" id="confirm_password" placeholder="Inserisci password">
          <span class="toggle-password" id="togglePassword_confirm">👁️</span>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Inserisci</button>
        </div>
      </form>
      <div class="text-center mt-3">
        <a href="login.php">Torna al login</a>
        <p><?php
          if (!empty($error)) {
          echo "<p style='color:red;'>$error</p>";
          }?>
        </p>
      </div>
    </div>
  </div>

  <script>
    // Controllo conferma password
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirm_password').value;

      if (password !== confirmPassword) {
        e.preventDefault();
        alert('Le password non corrispondono. Riprova.');
      }
    });

    // Mostra/nascondi password
    
   
      function visualizza(icon) 
        {
        const input = icon.previousElementSibling;
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
      }

      // Collego gli event listener alle icone
      const togglePasswordIcons = document.querySelectorAll('.toggle-password');

      togglePasswordIcons.forEach(icon => {
        icon.addEventListener('click', function() {
          visualizza(this);
        });
      });
  </script>
</body>
</html>




