<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $namaRuangan = $_POST['nama'];
        $maxCapacity = $_POST['maxCapacity'];
        $desc = $_POST['desc'];
        
        $response = array();


        $path = "http://localhost/yapura_api/yapura_api/img_ruangan/";

        $postPicture = $_FILES['gambar']['name'];

        if (str_contains($postPicture, ".jpg") || str_contains($postPicture, ".jpeg") || str_contains($postPicture, ".png")) {
            $pictureDir = $_FILES['gambar']['tmp_name'];

            move_uploaded_file($pictureDir, "../img_ruangan/" . $postPicture);

            $filename = "http://localhost/yapura_api/yapura_api/img_ruangan/" . $postPicture;

            $query = "INSERT INTO `list_ruangan`(`id`, `nama`, `maxCapacity`, `description`, `gambar`) VALUES (NULL,'$namaRuangan','$maxCapacity','$desc','$filename')";

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
        } else {
            http_response_code(400);
            echo "Please upload a .png, .jpg, or .jpeg  <br>";

            array_push($response, array(
                'status' => 'EXT_FAILED'
            ));
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
