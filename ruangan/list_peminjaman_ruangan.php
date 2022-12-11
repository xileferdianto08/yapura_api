<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $query = $con->prepare("SELECT pr.*, lr.nama as namaRuangan, lr.gambar as gambar, us.nama as namaUser FROM peminjaman_ruangan pr
        JOIN list_ruangan as lr ON pr.ruangId = lr.id
        JOIN users as us ON pr.userId = us.id");

        $response = array();
        $response['data_peminjaman_r'] = array();
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($query->rowCount() > 0) {

            foreach ($result as $row) {
                array_push($response['data_peminjaman_r'], $row);
            }
        } else {

            $response['status'] = "DATA_UNAVAIL";
        }
    } else {
        $response['status'] = "DB_FAILED";
    }

    echo json_encode(array($response));
}
