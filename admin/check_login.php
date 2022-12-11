<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = array();
        

        if (!str_contains($email, '@student.umn.ac.id') && !str_contains($email, '@umn.ac.id')) {
            $response['status'] = "EMAIL_INCORRECT_FORMAT";
        } else {

            $hashedPwd = hash('sha512', $password);

            $query = $con->prepare("SELECT * FROM admin WHERE email = :email");
            $query->bindParam(":email", $email);
            $query->execute();

            $result = $query->fetch();

            if ($query->rowCount() > 0) {
                if ($hashedPwd == $result['password']) {

                    $response['adminId'] = $result['id'];
                    $response['nama'] = $result['nama'];
                    $response['email'] = $result['email'];


                    $response['status'] = "LOGIN_SUCCESS";
                } else {
                    $response['status'] = "DATA_INCORRECT";
                }
            } else {
                $response['status'] = "DATA_NOT_EXIST";
            }
        }
    } else {
        $response['status'] = "DB_FAILED";
    }

    echo json_encode(array('server_response' => $response));
}
