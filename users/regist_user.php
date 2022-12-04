<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = array();

        $query = $con->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindParam(":email", $email);

        $query->execute();
        $query->fetch();

        if (!str_contains($email, '@umn.ac.id')) {
            http_response_code(400);
            echo "Please use your staff email!";
            array_push($response, array(
                'status' => 'EMAIL_INCORRECT_FORMAT'
            ));
        } else {
            if ($query->rowCount() > 0) {
                http_response_code(200);
                echo "Email is already exist!";
                array_push($response, array(
                    'status' => 'USER_ALREADY_EXIST'
                ));
            } else {
                $hashedPwd = hash('sha512', $password);

                $query2 = "INSERT INTO `users`(`id`, `nama`, `email`, `password`) VALUES (NULL,'$nama','$email','$hashedPwd')";

                $result = $con->query($query2);

                if ($result) {
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
        }
    } else {
        http_response_code(500);
        echo "Database cannot connect";
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
}
