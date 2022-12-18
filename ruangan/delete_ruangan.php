<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $ruangId = $_POST['ruangId'];

        $response = array();

        $query = "DELETE FROM `list_ruangan` WHERE id = '$ruangId'";
        
        $result = $con->query($query);

        if ($result) {
            $response['status'] = 'OK';
        } else {
            $response['status'] = 'FAILED';
        }
    } else {
        $response['status'] = 'DB_FAILED';
    }
    echo json_encode(array('server_response' => $response));
}

