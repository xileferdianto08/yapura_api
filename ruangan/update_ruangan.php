<?php 
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $id = $_POST['ruanganId'];
        $namaruangan = $_POST['nama'];
        $maxCapacity = $_POST['maxCapacity'];
        $desc = $_POST['desc'];
        $gambar = $_FILES['gambar']['name'];
        $response = array();

        $queryruangan = $con->prepare("SELECT * FROM list_ruangan WHERE id = :id");
        $queryruangan->bindParam(":id", $id);
        $queryruangan->execute();
        $result = $queryruangan->fetch();

        $path = "https://yapuraapi.000webhostapp.com/yapura_api/img_ruangan/";

        $postPicture = $_FILES['gambar']['name'];

        if($queryruangan->rowCount() > 0){
            $oldFile = substr($result['gambar'],58);
            if (str_contains($postPicture, ".jpg") || str_contains($postPicture, ".jpeg") || str_contains($postPicture, ".png")) {
                $pictureDir = $_FILES['gambar']['tmp_name'];

                $pictName = $namaruangan."_".$postPicture;
                unlink("../img_ruangan/".$oldFile);
                move_uploaded_file($pictureDir, "../img_ruangan/" . $pictName);

                

                $filename = "https://yapuraapi.000webhostapp.com/yapura_api/img_ruangan/" . $pictName;

                $query = "UPDATE `list_ruangan` SET nama = '$namaruangan', maxCapacity = '$maxCapacity', description = '$desc', gambar = '$filename' WHERE id = '$id'";
                $result = $con->query($query);

                if ($result) {
                    $response['status'] = 'OK';
                } else {
                    $response['status'] = 'FAILED';
                }
            } else {
                $response['status'] = 'EXT_FAILED';
            }   
        }else{
            $response['status'] = 'DATA_NOT_FOUND';
        }
    } else {
        $response['status'] = 'DB_FAILED';
    }

    echo json_encode(array('server_response' => $response));
