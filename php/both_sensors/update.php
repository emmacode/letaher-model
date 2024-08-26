<?php
    header('Access-Control-Allow-Origin: *');
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: GET, POST');

    $serverRoot = $_SERVER['DOCUMENT_ROOT'];
    require_once($serverRoot.'/config.php');
    
    $inputs = $_POST ? $_POST : $_GET;
    $output = [];

    $distance = $inputs['distance'] ?? '';
    $gasValue = $inputs['gas_value'] ?? '';

    $sqlSet = "";
    if($distance !== ''){
        $sqlSet .= ($sqlSet ? ", " : "") . " sonic_sensor_value = '$distance'";
    }
    if($gasValue !== ''){
        $sqlSet .= ($sqlSet ? ", " : "") . " gas_sensor_value = '$gasValue'";
    }

    if($sqlSet){
        $sql = "SELECT * FROM both_sensors LIMIT 1;";
        $query = mysqli_query($conn, $sql);
        $queryData = mysqli_fetch_all($query, MYSQLI_ASSOC);
        mysqli_free_result($query);
        if(count($queryData)){
            //update row;
            $queryDatum = $queryData[0];
            $binId = $queryDatum['id'];
            $sql = "UPDATE both_sensors SET ".$sqlSet.";";
            $query = mysqli_query($conn, $sql);
        }
        else {
            //insert new one;
            $sql = "INSERT INTO both_sensors(sonic_sensor_value, gas_sensor_value) VALUES('$distance', '$gasValue');";
            $query = mysqli_query($conn, $sql);
        }
    }

    $output['done'] = true;
    $output['inputs'] = $inputs;
    echo json_encode($output);

?>