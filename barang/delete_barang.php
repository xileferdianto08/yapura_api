<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $barangId = $_POST['barangId'];

        $response = array();
        $query = "DELETE FROM `list_barang` WHERE id = '$barangId'";
        
        $result = $con->query($query);

        if ($result) {
            $response['status'] = 'OK';
        } else {
            $response['status'] = 'FAILED';
        }
    }
} else {
    $response['status'] = 'DB_FAILED';
}
echo json_encode(array('server_response' => $response));
