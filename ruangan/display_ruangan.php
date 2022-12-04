<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {
        $query = $con->prepare("SELECT * FROM list_ruangan");
        $query->execute();

        $response = array();
        $response['data_ruangan'] = array();

        $row = $query->fetchAll();

        if ($query->rowCount() > 0) {
            foreach ($row as $data) {
                http_response_code(200);
                
                array_push($response['data_ruangan'], $data);
            }
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
    //
}
