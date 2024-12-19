<?php
session_start();
include __DIR__ . "/../../lib/system.php";
include __DIR__ . "/../../lib/classes/DatabaseManager.php";
include __DIR__ . "/../../lib/classes/AuthManager.php";

$databaseManager = new DatabaseManager();
$authManager = new AuthManager($databaseManager);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $fields = $_POST['selectedFields'];

    if(empty($name)){
        sendResponse(false, "Il Nome del contenuto non è valido.", 500);
    }

    if(empty($fields)){
        sendResponse(false, "È obbligatorio specificare almeno un campo.", 500);
    }

    if (!$authManager->isUserLoggedIn()){
        sendResponse(false, "Sessione scaduta. Accedi di nuovo", 401);
    }
    if ($databaseManager->createContent($name, $fields)){
        sendResponse(true, "Contenuto creato con successo!");
    }
    sendResponse(false, "Si è verificato un errore durante la creazione del contenuto. Si prega di riprovare", 500);
}
sendResponse(false, "Si è verificato un errore durante la creazione del contenuto. Si prega di riprovare", 500);
