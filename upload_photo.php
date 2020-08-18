<?php
session_start();
if (!isset($_SESSION['loggued_on_user']) || !isset($_SESSION['id'])) {
    exit;
}
include_once('db/connect_db.php');

$json = json_decode(file_get_contents('php://input'));
if (isset($json->upload_cam)) {
    $id = $_SESSION['id'];
    $mask_width = $json->width;
    $mask_height = $json->height;
    $mask_x = $json->coord_x;
    $mask_y = $json->coord_y;
    $geolocation = $json->geolocation;

    $img = str_replace(' ', '+', $json->upload_cam);

    $wm = imagecreatefrompng($json->mask);
    $wmW=imagesx($wm);
    $wmH=imagesy($wm);

    $image = imagecreatefromstring(base64_decode($img));

    /* imagecopyresampled - копирует и изменяет размеры части изображения
    * с пересэмплированием
    */
    imagecopyresampled ($image, $wm, $mask_x, $mask_y, 0, 0, $mask_width, $mask_height, $wmW, $wmH);

    /* imagejpeg - создаёт JPEG-файл filename из изображения image
    * третий параметр - качество нового изображение 
    * параметр является необязательным и имеет диапазон значений 
    * от 0 (наихудшее качество, наименьший файл)
    * до 100 (наилучшее качество, наибольший файл)
    * По умолчанию используется значение по умолчанию IJG quality (около 75)
    */

    $pdo = connect_db();
    if (!$pdo) {
        echo 'Database connection error';
        return;
    }
    ob_start();
    imagejpeg($image);
    $contents = ob_get_clean();

    $sql = "INSERT INTO `photoes` (`user_id`, `photo`, `pubdate`, `geolocation`) VALUES (:id_user, :photo, NOW(), :geolocation);";
    $result = $pdo->prepare($sql);
    $result->execute([
        'id_user' => $id,
        'photo' => base64_encode($contents),
        'geolocation' => $geolocation,
    ]);

    // imagedestroy - освобождает память
    imagedestroy($image);
    imagedestroy($wm);

    unset($image, $img);

    echo 'OK';
} else {
    echo 'ERROR';
}
?>