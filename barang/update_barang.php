<?php 
    require_once '../dbConfig/db_connect.php';

    if ($con) {
        $id = $_POST['barangId'];
        $namaBarang = $_POST['nama'];
        $maxQty = $_POST['maxQty'];
        $desc = $_POST['desc'];
        $gambar = $_FILES['gambar']['name'];
        $response = array();

        $queryBarang = $con->prepare("SELECT * FROM list_barang WHERE id = :id");
        $queryBarang->bindParam(":id", $id);
        $queryBarang->execute();
        $result = $queryBarang->fetch();

        $path = "https://yapuraapi.000webhostapp.com/yapura_api/img_barang/";

        $postPicture = $_FILES['gambar']['name'];

        if($queryBarang->rowCount() > 0){
            $oldFile = substr($result['gambar'],58);

            if (str_contains($postPicture, ".jpg") || str_contains($postPicture, ".jpeg") || str_contains($postPicture, ".png")) {
                $pictureDir = $_FILES['gambar']['tmp_name'];

                $pictName = $namaBarang."_".$postPicture;
                //unlink("../img_barang/".$oldFile);
                move_uploaded_file($pictureDir, "../img_barang/" . $pictName);

                

                $filename = "https://yapuraapi.000webhostapp.com/yapura_api/img_barang/" . $pictName;

                $query = "UPDATE `list_barang` SET nama = '$namaBarang', maxQty = '$maxQty', description = '$desc', gambar = '$filename'";
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
