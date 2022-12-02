<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {
        $query = "SELECT * FROM `list_ruangan`";
        $result = mysqli_query($con, $query);
        $response = array();
        $response['data_ruangan'] = array();


        $row = mysqli_num_rows($result);
        $results = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if ($row > 0) {
            http_response_code(200);
           
            array_push($response['data_ruangan'], $results);
        
        } else {
            http_response_code(200);
            echo "Data unavailable yet<br>";
            $response['status'] = "DATA_UNAVAIL";
        }
    } else {
        http_response_code(500);
        echo "Database cannot connect";
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
    mysqli_close($con);
}
