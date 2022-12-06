<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $query = $con->prepare("SELECT pb.*, lb.nama nama FROM peminjaman_barang pb
        JOIN list_barang as lb ON pb.barangId = lb.id");

        $response = array();
        $response['data_peminjaman_b'] = array();

        $query->fetchAll();

        if ($query->rowCount() > 0) {
            foreach ($query as $row) {
                array_push($response['data_peminjaman_b'], $results);
            }
        } else {
            $response['status'] = "DATA_UNAVAIL";
        }
    } else {
        $response['status'] = "DB_FAILED";
    }

    echo json_encode(array($response));
}
