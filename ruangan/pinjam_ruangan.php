<?php 
if($_SERVER['REQUEST_METHOD'] =='POST'){
    require_once '../dbConfig/db_connect.php';

    if($con) {
        $userId = $_POST['userId'];
        $ruangId = $_POST['ruanganId'];
        $startDate = $_POST['startDate'];
        $startTime = $_POST['startTime'];
        $endDate = $_POST['endDate'];
        $endTime = $_POST['endTime'];
        $capacity = $_POST['capacity'];
        $necessity = $_POST['necessity'];
        $response = array();

        $query = "SELECT * FROM `peminjaman_ruang` WHERE `ruangId` = '$ruangId'";
        $result = mysqli_query($con, $query);

        $row = mysqli_num_rows($result);
        $results = mysqli_fetch_all($result);

        if($row > 0){
            http_response_code(400);
            array_push($response, array(
                'status' => 'ROOM_BORROWED_ALR'
            ));
        } else {
            $query2 = "INSERT INTO `peminjaman_ruang`(`id`, `userId`, `ruangId`, `startDate`, `startTime`, `endDate`, `endTime`, `capacity`, `necessity`, `status`) VALUES (NULL,'$userId','$ruangId','$startDate','$startTime','$endDate','$endTime','$capacity','$necessity',NULL)";

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
