<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $query = $con->prepare("SELECT pr.*, lb.nama nama FROM peminjaman_ruangan pr
        JOIN list_ruangan as lb ON pr.ruanganId = lb.id");

        $response = array();
        $response['data_peminjaman_r'] = array();

        $query->fetchAll();

        if ($query->rowCount() > 0) {
            foreach ($query as $row) {
                array_push($response['data_peminjaman_r'], $results);
            }
        } else {
            $response['status'] = "DATA_UNAVAIL";
        }
    } else {
        $response['status'] = "DB_FAILED";
    }

    echo json_encode(array($response));
}
