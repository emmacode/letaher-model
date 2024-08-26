<?php
    header('Access-Control-Allow-Origin: *');
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET, POST');

    $serverRoot = $_SERVER['DOCUMENT_ROOT'];
    require_once($serverRoot.'/config.php');
    
    $inputs = $_POST ? $_POST : $_GET;
    $output = [];

    $sql = "SELECT * FROM dust_bin ORDER BY id DESC LIMIT 1;";
    $query = mysqli_query($conn, $sql);
    $queryData = mysqli_fetch_all($query, MYSQLI_ASSOC);
    mysqli_free_result($query);
    if(count($queryData)){
        $queryDatum = $queryData[0];
        $output['id'] = $queryDatum['id'];

        $distance = floatval($queryDatum['sensed_value']);
        $dustbinLen = 30; // in cm;

        if($distance > $dustbinLen){
            $distance = $dustbinLen;
        }

        $filledLen = $dustbinLen - $distance;
        $filledLevel = 100 * ($filledLen / $dustbinLen);

        $output['filled_level'] = $filledLevel;
        $output['filled_level_unit'] = '%';
    }

    echo json_encode($output);

?>