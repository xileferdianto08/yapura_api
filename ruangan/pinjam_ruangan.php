<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $userId = $_POST['userId'];
        $ruangId = $_POST['ruangId'];
        $startDate = $_POST['startDate'];
        $startTime = $_POST['startTime'];
        $endDate = $_POST['endDate'];
        $endTime = $_POST['endTime'];
        $capacity = $_POST['capacity'];
        $necessity = $_POST['necessity'];
        $response = array();

        $query = $con->prepare("SELECT * FROM peminjaman_ruangan WHERE ruangId = :id");
        $query->bindParam(":id", $ruangId);
        $query->execute();
        $query->fetch();


        //echo $sisaBarang;

        if ($query->rowCount() > 0) {
            http_response_code(400);

            array_push($response, array(
                'status' => 'ROOM_BORROWED'
            ));
        } else {
            $query1 = $con->prepare("SELECT maxCapacity FROM list_ruangan WHERE id = :id");
            $query1->bindParam(":id", $ruangId);
            $query1->execute();
            $results = $query1->fetch();

            if ($capacity > (int)$results['maxCapacity']) {
                http_response_code(400);
            
                array_push($response, array(
                    'status' => 'EXCEED_MAX_CAPACITY'
                ));
            } else {
                $query2 = "INSERT INTO peminjaman_ruangan(`id`, `userId`, `ruangId`, `startDate`, `startTime`, `endDate`, `endTime`, `capacity`, `necessity`) VALUES (NULL,'$userId','$ruangId','$startDate','$startTime','$endDate','$endTime','$capacity','$necessity')";


                $result2 = $con->query($query2);


                if ($result2) {
                    http_response_code(200);
                    array_push($response, array(
                        'status' => 'OK'
                    ));
                } else {
                    
                    http_response_code(200);
                    print_r($con->errorInfo());
                    array_push($response, array(
                        'status' => 'FAILED'
                    ));
                }
            }
        }
    } else {
        http_response_code(500);
        echo "Database cannot connect";
        array_push($response, array(
            'status' => 'DB_FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
}
