<?php
class Query {
    // Proprietà della classe
    public $sql;
    private $conn;

    // Costruttore per inizializzare le proprietà
    public function __construct($conn, $sql) {
        $this->conn = $conn;  // Connessione al database
        $this->sql = $sql;  // La query
    }

    // Metodo della classe per eseguire la query
    public function esegui($params = null) {
        // Preparazione della query
        $stmt = sqlsrv_query($this->conn, $this->sql, $params);

        if ($stmt === false) {
            die("Errore nella query: " . print_r(sqlsrv_errors(), true));
        }

        // Visualizza i risultati
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Giorno']->format('d-m-Y')) . "</td>";
            echo "<td>" . htmlspecialchars($row['NDVI']) . "</td>";
            echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['Foto']) . '" class="img-thumbnail"/></td>';
            echo "</tr>";
        }

        sqlsrv_free_stmt($stmt);
    }
}
?>
