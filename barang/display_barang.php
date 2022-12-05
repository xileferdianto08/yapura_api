<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {
        $query = $con->prepare("SELECT * FROM list_barang");
        $query->execute();

        $response = array();
        $response['data_barang'] = array();

        $row = $query->fetchAll();

        foreach ($row as $data) {
            if ($query->rowCount() > 0) {
                array_push($response['data_barang'], $data);
            } else {
                echo "Data unavailable yet<br>";
                $response['status'] = "DATA_UNAVAIL";
            }
        }
    } else {
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
}
