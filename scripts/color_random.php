<?php
function generarColorHexadecimal() {
    $color = mt_rand(0, 0xFFFFFF);
    return str_pad(dechex($color), 6, '0', STR_PAD_LEFT);
}

include '../utilities/db_conn.php';
$db = new PDO($dsn, $username, $password);

// 1. Obtener todos los usuarios
$query = "SELECT user_id FROM user_data";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// 2. Preparar el update
$update = $db->prepare("UPDATE user_data SET user_avatar_bg = :color WHERE user_id = :id");

// 3. Recorrer usuarios y actualizar su color
foreach ($users as $user) {
    $color = generarColorHexadecimal();
    $update->execute([
        ':color' => $color,
        ':id' => $user['user_id']
    ]);
}
?>
