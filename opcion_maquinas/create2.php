<?php
  session_start();
	include 'conection.php';

  $id = mysqli_real_escape_string($conn,$_POST['id']);
  $nombre = mysqli_real_escape_string($conn,$_POST['nombre']);
  $tip_maquina   = mysqli_real_escape_string($conn,$_POST['tip_maquina']);
  $query = mysqli_prepare($conn, "SELECT * FROM MDP_Maquina WHERE id = ? ");
  mysqli_stmt_bind_param($query, "s", $id);
  mysqli_stmt_execute($query);
  $result = mysqli_stmt_get_result($query);
  $nr = mysqli_num_rows($result);
       if (isset($_GET['cancel'])) {
    	$_SESSION['error'] = False;
        header("Location: prueba.php");
        exit();
	}

        if ( $nr < 1 ) {
            $_SESSION['error'] = False;
            $query = mysqli_prepare($conn, "INSERT INTO MDP_Maquina (id,id_tipo_maquina,nombre,id_estado) VALUES (?, ? , ? ,1)");
  	    mysqli_stmt_bind_param($query, "sss", $id,$tip_maquina,$nombre);
  	    mysqli_stmt_execute($query);
	}
       else {
          $_SESSION['error'] = True;
       }
  header("Location: prueba.php");
?>
