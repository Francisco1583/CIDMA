<?php
    include '../opcion_maquinas/conection.php';
    $id = 0;
    $id = mysqli_real_escape_string($conn,$_REQUEST['id']);
    $query = mysqli_prepare($conn, "DELETE FROM MDP_Usuario WHERE TRIM(matricula) = ?");
    mysqli_stmt_bind_param($query, "s", $id);
    mysqli_stmt_execute($query);
    mysqli_close($conn);
    header("Location: prueba.php");
?>
