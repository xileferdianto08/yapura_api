<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = array();

        $query = $con->prepare("SELECT * FROM admin WHERE email = :email");
        $query->bindParam(":email", $email);

        $query->execute();
        $query->fetch();

        if (!str_contains($email, '@umn.ac.id')) {
            array_push($response, array(
                'status' => 'EMAIL_INCORRECT_FORMAT'
            ));
        } else {
            if ($query->rowCount() > 0) {
                array_push($response, array(
                    'status' => 'USER_ALREADY_EXIST'
                ));
            } else {
                $hashedPwd = hash('sha512', $password);

                $query2 = "INSERT INTO `admin`(`id`, `nama`, `email`, `password`) VALUES (NULL,'$nama','$email','$hashedPwd')";

                $result = $con->query($query2);

                if ($result) {
                    array_push($response, array(
                        'status' => 'OK'
                    ));
                } else {
                    array_push($response, array(
                        'status' => 'FAILED'
                    ));
                }
            }
        }
    } else {
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
}
