<?php
    header('Access-Control-Allow-Origin: *');
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET, POST');

    $serverRoot = $_SERVER['DOCUMENT_ROOT'];
    require_once($serverRoot.'/config.php');
    
    $inputs = $_POST ? $_POST : $_GET;
    $output = [];

    $sql = "SELECT * FROM both_sensors ORDER BY id DESC LIMIT 1;";
    $query = mysqli_query($conn, $sql);
    $queryData = mysqli_fetch_all($query, MYSQLI_ASSOC);
    mysqli_free_result($query);
    if(count($queryData)){
        $queryDatum = $queryData[0];
        $output['id'] = $queryDatum['id'];

        $distance = floatval($queryDatum['sonic_sensor_value']);
        $gasValue = floatval($queryDatum['gas_sensor_value']);

        $dustbinLen = 30; // in cm;
        // $gasThreshold = 

        if($distance > $dustbinLen){
            $distance = $dustbinLen;
        }

        $filledLen = $dustbinLen - $distance;
        $filledLevel = 100 * ($filledLen / $dustbinLen);

        $output['filled_level'] = $filledLevel;
        $output['gas_value'] = $gasValue;
    }

    echo json_encode($output);

?>