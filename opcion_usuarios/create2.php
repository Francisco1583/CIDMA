<?php
        include '../opcion_maquinas/conection.php';

  $matricula = $_POST['matricula'];
  $nombre = mysqli_real_escape_string($conn,$_POST['nombre']);
  $ape_p   = mysqli_real_escape_string($conn,$_POST['ape_p']);
  $ape_m   = mysqli_real_escape_string($conn,$_POST['ape_m']);

        if ( $matricula != "" AND $nombre != "" AND $ape_p != "" AND $ape_m != "") {
                        $query = mysqli_prepare($conn,"SELECT * FROM MDP_Usuario WHERE TRIM(matricula) = ? ;");
                        mysqli_stmt_bind_param($query, "s", $matricula);
                        mysqli_stmt_execute($query);
                        $result = mysqli_stmt_get_result($query);
                        
                        if (mysqli_num_rows($result) > 0) {
                          header("Location: prueba.php?matricula_repetida=true");
                          exit;
                        }
                        else {
			                             
                          $query = mysqli_prepare($conn,"INSERT INTO MDP_Usuario (matricula,nombre,Apellido_paterno,Apellido_materno,id_estado) VALUES ( ? , ? , ? , ? ,1)");
                          mysqli_stmt_bind_param($query, "ssss", $matricula, $nombre, $ape_p, $ape_m);
                          mysqli_stmt_execute($query);
                          
                        }
        }
  header("Location: prueba.php");
?>
