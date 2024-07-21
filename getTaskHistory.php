<?php
// Configurazione del database
define('DB_HOST', 'localhost');
define('DB_NAME', '5i2_Mariucci');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connessione al database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_error) {
    die("Connessione fallita: " . $mysqli->connect_error);
}

// Ottieni l'ID del task dalla richiesta GET
$taskId = $_GET['taskId'];

// Effettua la query per ottenere tutte le versioni del task
$query = "
    SELECT 
        v.titolo AS versione_titolo,
        v.descripcion AS versione_descripcion,
        v.priorita AS versione_priorita,
        v.data_ver AS versione_data,
        s.titolo AS stato_titolo
    FROM versione v
    JOIN stato s ON v.fk_stato = s.id
    WHERE v.fk_task = ?
    ORDER BY v.data_ver ASC
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $taskId);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $history[] = [
            'data' => $row['versione_data'],
            'modifiche' => "Titolo: " . $row['versione_titolo'] . ", Descrizione: " . $row['versione_descripcion'] . ", Priorità: " . $row['versione_priorita'] . ", Stato: " . $row['stato_titolo']
        ];
    }
}

// Restituisci lo storico delle versioni come JSON
header('Content-Type: application/json');
echo json_encode($history);

// Chiusura della connessione
$mysqli->close();
?>
