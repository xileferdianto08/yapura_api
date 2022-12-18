<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {
        $query = $con->prepare("SELECT * FROM list_barang");
        $query->execute();

        $response = array();
        $response['server_response'] = array();

        $row = $query->fetchAll();

        foreach ($row as $data) {
            if ($query->rowCount() > 0) {
                array_push($response['server_response'], $data);
                
            } else {
                $response['status'] = "DATA_UNAVAIL";
            }
        }
    } else {
        $response['status'] = 'DB_FAILED';
    }

    echo json_encode(array($response));
}
