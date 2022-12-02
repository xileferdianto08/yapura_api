<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';
    if ($con) {


        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = array();

        $query = "SELECT * FROM `users` WHERE `email` = '$email'";
        $result = mysqli_query($con, $query);


        $row = mysqli_num_rows($result);

        if ($nama == '') {
            http_response_code(400);
            echo "Nama cannot be empty<br>";

            array_push($response, array(
                'status' => 'INPUT_NAMA_FAILED'
            ));
        }
        if ($email == '') {
            http_response_code(400);
            echo "Email cannot be empty<br>";
            array_push($response, array(
                'status' => 'INPUT_EMAIL_FAILED'
            ));
        }
        if (!str_contains($email, '@student.umn.ac.id') && !str_contains($email, '@umn.ac.id')) {
            http_response_code(400);
            echo "Please use you're student or staff email!";
            array_push($response, array(
                'status' => 'EMAIL_INCORRECT_FORMAT'
            ));
        } else {
            if ($row > 0) {
                http_response_code(200);
                echo "Email is already exist!";
                array_push($response, array(
                    'status' => 'USER_ALREADY_EXIST'
                ));
            } else {
                if ($password == '') {
                    http_response_code(400);
                    echo "Password cannot be empty<br>";
                    array_push($response, array(
                        'status' => 'INPUT PASSWORD FAILED'
                    ));
                } else {
                    $hashedPwd = hash('sha512', $password);

                    $query = "INSERT INTO `users`(`id`, `nama`, `email`, `password`) VALUES (NULL,'$nama','$email','$hashedPwd')";
                    $result = mysqli_query($con, $query);


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
        }
    } else {
        http_response_code(500);
        echo "Database cannot connect";
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
    mysqli_close($con);
}
