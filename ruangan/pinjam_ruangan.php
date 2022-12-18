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

        $query = $con->prepare("SELECT * FROM peminjaman_ruangan WHERE ruangId = :rId AND startDate = :startDate AND startTime = :startTime");
        $query->bindParam(":rId", $ruangId);

        $query->bindParam(":startDate", $startDate);
        $query->bindParam(":startTime", $startTime);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);



        $itungTime = $result['endTime'] = isset($result['endTime'])?  (int)substr($startTime, 0, 2) - (int)substr($result['endTime'], 0, 2): '';


        $itungDate1 = $result['endDate'] = isset($result['endDate'])? (int)substr($startDate, 8, 2) - (int)substr($result['endDate'], 8, 2): '';
        

        if ($query->rowCount() > 0 || (int)$itungTime < 0 || (int) $itungDate1 > 0) {
                $response['status']='ROOM_BORROWED';
        } else {
            $query1 = $con->prepare("SELECT * FROM list_ruangan WHERE id = :ruangId ");
            $query1->bindParam(":ruangId", $ruangId);

            $query1->execute();
            $results = $query1->fetch(PDO::FETCH_ASSOC);

            $maxCapacity = $results['maxCapacity'] = isset($results['maxCapacity']) ? (int)$results['maxCapacity'] : '';



            if ($capacity > (int)$results['maxCapacity']) {
                print_r($con->errorInfo());
                $response['status'] ='EXCEED_MAX_CAPACITY';
               
            } else {
                $query2 = $con->prepare("INSERT INTO peminjaman_ruangan(`id`,`userId`, `ruangId`, `startDate`, `startTime`, `endDate`, `endTime`, `capacity`, `necessity`) VALUES (NULL, :userId, :ruangId, :startDate, :startTime, :endDate, :endTime, :capacity, :necessity)");
                $query2->bindParam(":userId", $userId);
                $query2->bindParam(":ruangId", $ruangId);
                $query2->bindParam(":startDate", $startDate);
                $query2->bindParam(":startTime", $startTime);
                $query2->bindParam(":endDate", $endDate);
                $query2->bindParam(":endTime", $endTime);
                $query2->bindParam(":capacity", $capacity);
                $query2->bindParam(":necessity", $necessity);

                $result2 = $query2->execute();


                if ($result2) {
                    $response['status'] ='OK';
                   
                } else {

                    print_r($con->errorInfo());
                    $response['status'] ='FAILED';
                   
                }
            }
        }
    } else {
        $response['status'] ='DB_FAILED';
       
    }

    echo json_encode(array('server_response' => $response));
}
