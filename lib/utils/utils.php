<?php
function formatDate($date){
    $formattedDate = date('d F Y, H:i', strtotime($date));

    $months = [
        'January' => 'Gennaio', 'February' => 'Febbraio', 'March' => 'Marzo',
        'April' => 'Aprile', 'May' => 'Maggio', 'June' => 'Giugno',
        'July' => 'Luglio', 'August' => 'Agosto', 'September' => 'Settembre',
        'October' => 'Ottobre', 'November' => 'Novembre', 'December' => 'Dicembre'
    ];

    return str_replace(array_keys($months), array_values($months), $formattedDate);
}

function getDomain(){
    $protocol = $_SERVER['REQUEST_SCHEME'];
    $host = $_SERVER['HTTP_HOST'];
    $fullUrl = $protocol . '://' . $host;
    return $fullUrl;
}
?>
