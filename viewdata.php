<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Immagini</title>
    <link rel="stylesheet" href="style.css">
    <script src="page.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <button class="col-1" id="Bottone" onclick="vaia('dashboard.html')">Home</button>
            <h1 class="col">Drone Mapper - Esplora i tuoi Terreni</h1>
            <button class="col-1 Bottone" onclick="vaia('login.php')">Log Out</button>
        </div>
    </div>
</header>
    
        <?php
        require 'config.php';

        if (isset($_GET['data'])) {
            $data = htmlspecialchars($_GET['data']); // Protezione XSS
            $params = array($data);
            // Query per contare il numero di record
            $count_query = "SELECT COUNT(*) AS totale FROM Immagini WHERE Giorno = ?";
            $stmt_count = sqlsrv_query($conn, $count_query, $params);
            
            if ($stmt_count === false) {
                error_log("Errore nella query di conteggio: " . print_r(sqlsrv_errors(), true));
                echo "<h1 class='text-danger'>Errore interno. Riprova più tardi.</h1>";
                exit();
            }
            
            if ($row_count = sqlsrv_fetch_array($stmt_count, SQLSRV_FETCH_ASSOC)) {
                if ($row_count['totale'] > 0) 
                {
                    echo "<table class='table table-striped table-bordered text-center'>
                            <thead class='table-dark'>
                            <tr>
                                <th>Giorno</th>
                                <th>NDVI</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>";

                    include 'query.php';
                    $query = "SELECT Giorno, NDVI, Foto FROM Immagini WHERE Giorno = ?";
                    // Crea un oggetto Query passando la connessione e la query
                    $sql = new Query($conn, $query);
                    // Esegui la query passando i parametri
                    $sql->esegui($params);
                } 
                else 
                {
                    $data_formattata = DateTime::createFromFormat('Y-m-d', $data);
                    echo "
                    <div class='d-flex flex-column justify-content-center align-items-center min-vh-100'>
                        <h1 class='text-danger fs-1 mb-4 text-center'>Nessuna immagine disponibile il ". $data_formattata->format('d-m-Y') ."</h1>
                        <a href='viewdata.html' class='btn btn-primary btn-lg'>Torna alla ricerca</a>
                    </div>";
                }
            }
            
            sqlsrv_free_stmt($stmt_count);
        }
        ?>
    </div>
</body>
</html>
