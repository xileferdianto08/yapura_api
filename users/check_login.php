<?php
require_once '../dbConfig/db_connect.php';

if ($con) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = array();

    if ($email == '') {
        http_response_code(400);
        echo "Email cannot be empty<br>";
        array_push($response, array(
            'status' => 'INPUT EMAIL FAILED'
        ));
    }
    if (!str_contains($email, '@student.umn.ac.id') && !str_contains($email, '@umn.ac.id')) {
        http_response_code(400);
        echo "Please use you're student or staff email!";
        array_push($response, array(
            'status' => 'EMAIL_INCORRECT_FORMAT'
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

            $query = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$hashedPwd'";
            $result = mysqli_query($con, $query);


            $row = mysqli_num_rows($result);

            if ($row > 0) {
                http_response_code(200);
                echo "Succesfully Logged in";
                array_push($response, array(
                    'status' => 'OK'
                ));
            } else {
                http_response_code(404);
                echo "Email or Password is incorrect<br>";
                array_push($response, array(
                    'status' => 'FAILED'
                ));
            }
        }
    }
} else {
    http_response_code(500);
    echo "Database cannot connect<br>";
    array_push($response, array(
        'status' => 'DB FAILED'
    ));
}

echo json_encode(array('server_response' => $response));
mysqli_close($con);
