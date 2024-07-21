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

// Imposta l'header della risposta come JSON
header('Content-Type: application/json');

// Controlla che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Riceve il contenuto della richiesta in formato JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica che tutti i campi necessari siano presenti
    if (isset($data['titolo'], $data['descripcion'], $data['creatore'], $data['priorita'])) {
        // Prepara le variabili
        $titolo = $mysqli->real_escape_string($data['titolo']);
        $descripcion = $mysqli->real_escape_string($data['descripcion']);
        $creatore = $mysqli->real_escape_string($data['creatore']);
        $priorita = $mysqli->real_escape_string($data['priorita']);

        // Inserisce il nuovo task nella tabella `task`
        $query_task = "INSERT INTO task (data_creazione, fk_creatore) VALUES (NOW(), (SELECT id FROM utente WHERE username = '$creatore'))";
        
        if ($mysqli->query($query_task) === TRUE) {
            $task_id = $mysqli->insert_id;

            // Inserisce la versione iniziale del task nella tabella `versione`
            $query_version = "
                INSERT INTO versione (fk_task, titolo, descripcion, priorita, data_ver, fk_stato)
                VALUES ($task_id, '$titolo', '$descripcion', '$priorita', NOW(), 1)
            ";

            if ($mysqli->query($query_version) === TRUE) {
                echo json_encode(['status' => 'success', 'message' => 'Task aggiunto con successo.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'inserimento della versione del task: ' . $mysqli->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Errore durante l\'inserimento del task: ' . $mysqli->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dati del task incompleti.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metodo di richiesta non supportato.']);
}

// Chiusura della connessione
$mysqli->close();
?>
