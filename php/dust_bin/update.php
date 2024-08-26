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
    if($distance !== ''){
        $sql = "SELECT * FROM dust_bin LIMIT 1;";
        $query = mysqli_query($conn, $sql);
        $queryData = mysqli_fetch_all($query, MYSQLI_ASSOC);
        mysqli_free_result($query);
        if(count($queryData)){
            //update row;
            $queryDatum = $queryData[0];
            $binId = $queryDatum['id'];
            $sql = "UPDATE dust_bin SET sensed_value = '$distance' WHERE id = '$binId' LIMIT 1;";
            $query = mysqli_query($conn, $sql);
        }
        else {
            //insert new one;
            $sql = "INSERT INTO dust_bin(sensed_value) VALUES('$distance');";
            $query = mysqli_query($conn, $sql);
        }
        $output['done'] = true;
    }

    echo json_encode($output);

?>