<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $query = "SELECT pb.*, lb.nama nama FROM `peminjaman_barang` pb
        JOIN `list_barang` as lb ON pb.barangId = lb.id";
        $result = mysqli_query($con, $query);
        $response = array();
        $response['data_peminjaman_b'] = array();

        $row = mysqli_num_rows($result);
        $results = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if($row > 0){
            http_response_code(200);
           
            array_push($response['data_peminjaman_b'], $results);
        
        } else {
            http_response_code(200);
            echo "Data unavailable <br>";
            $response['status'] = "DATA_UNAVAIL";
        }

    } else {
        http_response_code(500);
        echo "Database cannot connect<br>";
        $response['status'] = "DB_FAILED";
    }
}
