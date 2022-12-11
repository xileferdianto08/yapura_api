<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $userId = $_POST['userId'];
        $query = $con->prepare("SELECT pb.*, lb.nama as namaBarang, lb.gambar as gambar FROM peminjaman_barang pb
        JOIN list_barang as lb ON pb.barangId = lb.id WHERE pb.userId = :userId");

        $query->bindParam(":userId", $userId);
        $query->execute();

        $response = array();
        $response['data_peminjaman_b'] = array();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($query->rowCount() > 0) {
            foreach ($result as $row) {

                array_push($response['data_peminjaman_b'], $row);
            }
        } else {
            $response['status'] = "DATA_UNAVAIL";
        }
    } else {
        $response['status'] = "DB_FAILED";
    }

    echo json_encode(array($response));
}
