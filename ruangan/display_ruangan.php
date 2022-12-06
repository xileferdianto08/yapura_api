<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {
        $query = $con->prepare("SELECT * FROM list_ruangan");
        $query->execute();

        $response = array();
        $response['server_response'] = array();
        

        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($query->rowCount() > 0) {
            foreach ($row as $data) {
                array_push($response['server_response'], $data);
            }
        } else {
            $response['status'] = "DATA_UNAVAIL";
        }
    } else {
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array($response));
    //
}
