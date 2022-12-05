<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $userId = $_POST['userId'];
        $barangId = $_POST['barangId'];
        $startDate = $_POST['startDate'];
        $startTime = $_POST['startTime'];
        $endDate = $_POST['endDate'];
        $endTime = $_POST['endTime'];
        $qty = $_POST['qty'];
        $necessity = $_POST['necessity'];
        $response = array();

        $query = $con->prepare("SELECT * FROM list_barang WHERE id = :id");
        $query->bindParam(":id", $barangId);
        $query->execute();
        $results = $query->fetch();



        $sisaBarang = (int)$results['maxQty'] - $qty;
        //echo $sisaBarang;

        if ($sisaBarang < 1) {
            array_push($response, array(
                'status' => 'NO_ITEMS_LEFT'
            ));
        } else if ($qty > $results['maxQty']) {
            array_push($response, array(
                'status' => 'EXCEED_MAX_QTY'
            ));
        } else {
            $query2 = "INSERT INTO peminjaman_barang(`id`, `userId`, `barangId`, `startDate`, `startTime`, `endDate`, `endTime`, `qty`, `necessity`) 
                         VALUES (NULL,'$userId','$barangId','$startDate','$startTime','$endDate','$endTime','$qty','$necessity')";

            $query3 = $con->prepare("UPDATE list_barang
                        SET maxQty = :qtyNow
                        WHERE id = :id");
            $query3->bindParam(":qtyNow", $sisaBarang);
            $query3->bindParam(":id", $barangId);
            

            $result2 = $con->query($query2);
            $result3 = $query3->execute();

            if ($result2 && $result3) {
                array_push($response, array(
                    'status' => 'OK'
                ));
            } else {
                
                array_push($response, array(
                    'status' => 'FAILED'
                ));
            }
        }
    } else {
        array_push($response, array(
            'status' => 'DB_FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
}
