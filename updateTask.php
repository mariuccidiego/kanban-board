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

// Ottieni l'ID del task dalla richiesta POST
$data = json_decode(file_get_contents("php://input"), true);
$taskId = $data['taskId'];
$titolo = $data['titolo'];
$priorita = $data['priorita'];
$descripcion = $data['descripcion'];

// Effettua la query per creare una nuova versione del task con lo stato aggiornato
$currentDate = date("Y-m-d");
$query = "SELECT 
t.id AS task_id,
t.data_creazione,
u.username AS creatore,
v.titolo,
v.descripcion,
v.priorita,
v.data_ver,
s.titolo AS stato_titolo,
s.descripcion AS stato_descripcion,
v.fk_stato
FROM task t
JOIN utente u ON t.fk_creatore = u.id
JOIN versione v ON t.id = v.fk_task
JOIN stato s ON v.fk_stato = s.id
WHERE v.id in (
SELECT MAX(id)
FROM versione
GROUP BY fk_task
) and v.fk_task='$taskId';";
$stato="";
$result = $mysqli->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['fk_stato'];
        $stato=$row['fk_stato'];
    }
}

$query = "INSERT INTO versione (titolo, descripcion, priorita, data_ver, fk_stato, fk_task)
        VALUES ('$titolo', '$descripcion', $priorita, '$currentDate', $stato, $taskId)";

echo $query;

if ($mysqli->query($query) === TRUE) {
    // Operazione completata con successo
    echo json_encode(array("message" => "Task avanzato con successo"));
} else {
    // Errore durante l'operazione
    echo json_encode(array("error" => "Errore durante l'avanzamento del task: " . $mysqli->error));
}


// Chiusura della connessione
$mysqli->close();
?>
