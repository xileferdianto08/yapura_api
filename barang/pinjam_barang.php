<?php 
if($_SERVER['REQUEST_METHOD'] =='POST'){
    require_once '../dbConfig/db_connect.php';

    if($con) {
        


    }else {
        http_response_code(500);
        echo "Database cannot connect";
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
    mysqli_close($con);
    
}
?>