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
$creatore = $data['creatore'];
$stato = $data['stato'];

// Effettua la query per creare una nuova versione del task con lo stato aggiornato
$currentDate = date("Y-m-d");
if($stato<4){
    $newState = $stato+1; // Supponiamo che lo stato "In lavorazione" abbia l'ID 2, assicurati che sia corretto
    $query = "INSERT INTO versione (titolo, descripcion, priorita, data_ver, fk_stato, fk_task)
            VALUES ('$titolo', '$descripcion', $priorita, '$currentDate', $newState, $taskId)";
    echo $query;

    if ($mysqli->query($query) === TRUE) {
        // Operazione completata con successo
        echo json_encode(array("message" => "Task avanzato con successo"));
    } else {
        // Errore durante l'operazione
        echo json_encode(array("error" => "Errore durante l'avanzamento del task: " . $mysqli->error));
    }
}


// Chiusura della connessione
$mysqli->close();
?>
