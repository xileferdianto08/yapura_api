<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $namaruangan = $_POST['nama'];
        $maxCapacity = $_POST['maxCapacity'];
        $desc = $_POST['desc'];
        // $gambar = $_FILES['gambar']['name'];
        $response = array();

        $queryruangan = $con->prepare("SELECT * FROM list_ruangan WHERE nama = :nama");
        $queryruangan->bindParam(":nama", $namaruangan);
        $queryruangan->execute();
        $queryruangan->fetch();

        $path = "https://yapuraapi.000webhostapp.com/yapura_api/img_ruangan/";

        $postPicture = $_FILES['gambar']['name'];

        if ($queryruangan->rowCount() > 0) {
            array_push($response, array(
                'status' => 'DATA_EXIST'
            ));
        } else {
            if (str_contains($postPicture, ".jpg") || str_contains($postPicture, ".jpeg") || str_contains($postPicture, ".png")) {
                $pictureDir = $_FILES['gambar']['tmp_name'];

                $pictName = $namaruangan."_".$postPicture;

                move_uploaded_file($pictureDir, "../img_ruangan/" . $pictName);



                $filename = "https://yapuraapi.000webhostapp.com/yapura_api/img_ruangan/" . $pictName;

                $query = "INSERT INTO `list_ruangan`(`id`, `nama`, `maxCapacity`, `description`, `gambar`) VALUES (NULL,'$namaruangan','$maxCapacity','$desc','$filename')";
                $result = $con->query($query);

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

                array_push($response, array(
                    'status' => 'EXT_FAILED'
                ));
            }
        }
    } else {
        array_push($response, array(
            'status' => 'DB FAILED'
        ));
    }

    echo json_encode(array('server_response' => $response));
}
