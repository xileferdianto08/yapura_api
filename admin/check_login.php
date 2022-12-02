<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $response = array();
        $response['login_admin'] = array();

        if ($email == '') {
            http_response_code(400);
            echo "Email cannot be empty<br>";
            $response['status'] = "EMAIL_EMPTY";
        }
        if (!str_contains($email, '@umn.ac.id')) {
            http_response_code(400);
            echo "Please use your staff email!";
            $response['status'] = "EMAIL_INCORRECT_FORMAT";
        } else {
            if ($password == '') {
                http_response_code(400);
                echo "Password cannot be empty<br>";
                $response['status'] = "PWD_EMPTY";
            } else {
                $hashedPwd = hash('sha512', $password);

                $query = "SELECT * FROM `admin` WHERE `email` = '$email' AND `password` = '$hashedPwd'";
                $result = mysqli_query($con, $query);


                $row = mysqli_num_rows($result);
                $results = mysqli_fetch_array($result);

                if ($row > 0) {
                    if ($results['verified'] == 0) {
                        http_response_code(401);
                        echo "Your account is not verified";
                        $response['status'] = 'ADMIN_UNVERIFIED';
                    } else {
                        http_response_code(200);
                        echo "Succesfully Logged in";

                        $data['nama'] = $results['nama'];
                        $data['email'] = $results['email'];

                        array_push($response['login_admin'], $data);

                        $response['status'] = "LOGIN_SUCCESS";
                    }
                } else {
                    http_response_code(404);
                    echo "Email or Password is incorrect<br>";
                    $response['status'] = "DATA_INCORRECT";
                }
            }
        }
    } else {
        http_response_code(500);
        echo "Database cannot connect<br>";
        $response['status'] = "DB_FAILED";
    }

    echo json_encode(array('server_response' => $response));
    mysqli_close($con);
}
