<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $namaBarang = $_POST['nama'];
        $maxQty = $_POST['maxQty'];
        $desc = $_POST['desc'];
        $gambar = $_FILES['gambar']['name'];
        $response = array();

        $queryBarang = $con->prepare("SELECT * FROM list_barang WHERE nama = :nama");
        $queryBarang->bindParam(":nama", $namaBarang);
        $queryBarang->execute();
        $queryBarang->fetch();

        $path = "http://localhost/yapura_api/yapura_api/img_barang/";

        $postPicture = $_FILES['gambar']['name'];

        if ($queryBarang->rowCount() > 0) {

            array_push($response, array(
                'status' => 'DATA_EXIST'
            ));
        } else {
            if (str_contains($postPicture, ".jpg") || str_contains($postPicture, ".jpeg") || str_contains($postPicture, ".png")) {
                $pictureDir = $_FILES['gambar']['tmp_name'];

                move_uploaded_file($pictureDir, "../img_barang/" . $postPicture);

                $filename = "http://localhost/yapura_api/yapura_api/img_barang/" . $postPicture;

                $query = "INSERT INTO `list_barang`(`id`, `nama`, `maxQty`, `description`, `gambar`) VALUES (NULL,'$namaBarang','$maxQty','$desc','$filename')";
                $result = $con->query($query);

                if ($result) {
                    array_push($response, array(
                        'status' => 'OK'
                    ));
                } else {
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
