<?php
    session_start();
    //var_dump($_SESSION);
    require_once("Db.php");
    $db = new Db();
    $filename = "export_znamek.csv";
    if($_SESSION["role"] == "Žák"){
        $query = $db->exportCsvZak($_SESSION["login"]); 
    }
    else{ 
        $query = $db->exportCsvUcitel($_SESSION["tridniTrida"]);
    }
    //var_dump($query);
    
    $file = fopen($filename,"w");
    foreach ($query as $line){
        fputcsv($file,$line);
    }  
    fclose($file);
    if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        exit;
    }
    ?>