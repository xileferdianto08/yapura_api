<?php 
if($_SERVER['REQUEST_METHOD'] =='POST'){
    require_once '../dbConfig/db_connect.php';

    if($con) {
        $userId = $_POST['userId'];
        $barangId = $_POST['barangId'];
        $startDate = $_POST['startDate'];
        $startTime = $_POST['startTime'];
        $endDate = $_POST['endDate'];
        $endTime = $_POST['endTime'];
        $qty = $_POST['qty'];
        $necessity = $_POST['necessity'];
        $response = array();

        $query = "SELECT * FROM `peminjaman_barang` WHERE `barangId` = '$barangId'";
        $result = mysqli_query($con, $query);

        $row = mysqli_num_rows($result);
        $results = mysqli_fetch_all($result);

        $sisaBarang = $results['qty'] - $qty;

        if($sisaBarang < 1){
            http_response_code(400);
            array_push($response, array(
                'status' => 'NO_ITEMS_LEFT'
            ));
        } else if ($qty > $results['qty']){
            http_response_code(400);
            array_push($response, array(
                'status' => 'EXCEED_MAX_QTY'
            ));
        } else {
            $query2 = "INSERT INTO `peminjaman_barang`(`id`, `userId`, `barangId`, `startDate`, `startTime`, `endDate`, `endTime`, `qty`, `necessity`, `status`) VALUES (NULL,'$userId','$barangId','$startDate','$startTime','$endDate','$endTime','$qty','$necessity',NULL)";

            $result2 = mysqli_query($con, $query2);

            if ($result2) {
                http_response_code(200);
                array_push($response, array(
                    'status' => 'OK'
                ));
            } else {
                http_response_code(200);
                array_push($response, array(
                    'status' => 'FAILED'
                ));
            }
        }
    }else {
        http_response_code(500);
        echo "Database cannot connect";
        array_push($response, array(
            'status' => 'DB_FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
    mysqli_close($con);
    
}
