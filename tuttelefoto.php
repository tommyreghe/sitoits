<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Immagini</title>
    <link rel="stylesheet" href="style.css">
    <script src="page.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
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

        <table class="table table-striped table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Giorno</th>
                    <th>NDVI</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'query.php';
                include 'config.php';
                // Crea un oggetto della classe Persona
                $query="SELECT Giorno, NDVI, Foto FROM ImmaginI ORDER BY Giorno DESC";
                // Crea un oggetto Query passando la connessione e la query
                $sql = new Query($conn, $query);
                // Esegui la query passando i parametri
                $params = null;
                $sql->esegui($params);
                ?>
                
            </tbody>
        </table>
</body>
</html>
