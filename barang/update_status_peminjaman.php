<?php 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){  
        require_once '../dbConfig/db_connect.php';
        if($con){
            $id = $_POST['id'];
            $approved = $_POST['status'];
            $response = array();
            $status = "";
            if($approved == 0){
                $status = "Rejected";
            } else if($approved == 1) {
                $status = "Accepted";
            }

            $query = $con->prepare("UPDATE peminjaman_barang
                                    SET status = :status
                                    WHERE id = :id");
            $query->bindParam(":status", $status);
            $query->bindParam(":id", $id);

            $result = $query->execute();

            if ($result) {
                array_push($response, array(
                    'status' => 'OK'
                ));
            } else {
                array_push($response, array(
                    'status' => 'FAILED'
                ));
            }


        }else {
            $response['status'] = "DB_FAILED";
        }
        echo json_encode(array('server_response' => $response));
    }
?>