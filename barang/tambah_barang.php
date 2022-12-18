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

        $path = "https://yapuraapi.000webhostapp.com/yapura_api/img_barang/";

        $postPicture = $_FILES['gambar']['name'];

        if ($queryBarang->rowCount() > 0) {

            $response['status'] = 'DATA_EXIST';
        } else {
            if (str_contains($postPicture, ".jpg") || str_contains($postPicture, ".jpeg") || str_contains($postPicture, ".png")) {
                $pictureDir = $_FILES['gambar']['tmp_name'];

                $pictName = $namaBarang."_".$postPicture;

                move_uploaded_file($pictureDir, "../img_barang/" . $pictName);

                

                $filename = "https://yapuraapi.000webhostapp.com/yapura_api/img_barang/" . $pictName;

                $query = "INSERT INTO `list_barang`(`id`, `nama`, `maxQty`, `description`, `gambar`) VALUES (NULL,'$namaBarang','$maxQty','$desc','$filename')";
                $result = $con->query($query);

                if ($result) {
                    $response['status'] = 'OK';
                } else {
                    $response['status'] = 'FAILED';
                }
            } else {
                $response['status'] = 'EXT_FAILED';
            }
        }
    } else {
        $response['status'] = 'DB_FAILED';
    }

    echo json_encode(array('server_response' => $response));
}
