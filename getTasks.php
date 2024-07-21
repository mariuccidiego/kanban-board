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

// Query per ottenere l'ultimo aggiornamento dei task
$query = "
SELECT 
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
);
";

$result = $mysqli->query($query);

$tasks = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = [
            'task_id' => $row['task_id'],
            'data_creazione' => $row['data_creazione'],
            'creatore' => $row['creatore'],
            'titolo' => $row['titolo'],
            'descripcion' => $row['descripcion'],
            'priorita' => $row['priorita'],
            'data_ver' => $row['data_ver'],
            'stato_titolo' => $row['stato_titolo'],
            'fk_stato' => $row['fk_stato'],
            'stato_descripcion' => $row['stato_descripcion']
        ];
    }
}

// Chiusura della connessione
$mysqli->close();

// Restituisce i task in formato JSON
header('Content-Type: application/json');
echo json_encode($tasks);
?>