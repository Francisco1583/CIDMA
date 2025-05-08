<?php
 session_start();
 if (!isset($_SESSION['user_id'])) {
   header('Location: ../sign_in/sign_in.php');
   exit;
 }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>maquinas</title>
    <link rel="stylesheet" href="styless.css">
</head>
<body>
  <div id="mycontainer">
	<!-- Menú lateral, mismo código que en 'Máquinas' -->
        <div id="left_column">
	  <br></br>
          <div id="userimg_config">
            <img id="user_img" src="https://www.logolynx.com/images/logolynx/eb/ebb57b64c932f54ffced3c416b4f652d.jpeg" >
          </div>
    
          <div id="lateral_name">
            <h4><?php echo $_SESSION['user_name']; ?></h4>
          </div>
    
          <div>
            <table class= lateral_table>
              <tr>
                <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/machines.png"></img></td>
                <td><a href="../opcion_maquinas/prueba.php"><h4>Máquinas</h4></a></td>
              </tr>
            </table>
          </div>
    
          <div>
            <table class= lateral_table>
              <tr>
                <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/reports.png"</img></td>
                <td><a href="prueba.php"><h4>Historial</h4></a></td>
              </tr>
            </table>
          </div>
          
          <div>
            <table  class= lateral_table>
              <tr>
                <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/users.png"></img></td>
                <td><a href="../opcion_usuarios/prueba.php"><h4>Usuarios</h4></a></td>
              </tr>
            </table>
          </div>

          <div id="lateral_name">
            <table  class= lateral_table id="logOutSpace">
                <tr>
                    <td class="img_disps_align"><a href="../logout.php"><img class="lateral_menu_icons" src="../logos/log_out.png"></img></a></td>
                </tr>
            </table>
          </div>
        </div>
    
	<!-- Interfaz de 'Historial' -->
        <div id="right_column">
          <center><h1>Historial de Uso de Máquinas</h1></center>
          <div>
            <form method="post" action="prueba.php">
              <div class="filter_option">
                
		<!-- Funcionalidad para buscar y filtrar información en el historial -->
                Buscar por:
          
		<!-- Se selecciona el parámetro por el cual buscar -->
                <select id="menu" name="parameter">
                  <option value="">Mostrar todo</option>
                  <option value="matricula">Matrícula</option>
                  <option value="nombre_usuario">Nombre de usuario</option>
                  <option value="nombre">nombre de Máquina</option>
                  <option value="id_maquina">ID de máquina</option>
                </select>
          
                <input id="buscar_input" type="text" placeholder="Buscar" maxlength="100" name="str">
                 <!-- Filtrado de búsqueda por fechas -->
		 desde:
                <input type="date" name="fecha_init">
                 hasta:
                <input type="date" name="fecha_fin">
                <input id="boton" type="submit" value="Buscar">
                
              </div>
            </form>
          </div>
          <center>
            <?php
             include '../opcion_maquinas/conection.php';
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $parameter = htmlspecialchars($_POST['parameter']);
              $str = htmlspecialchars($_POST['str']);
		 // Se selecciona el parámetro a buscar, ignorando la demás información
                 if($parameter == '' AND ($_POST["fecha_init"] == '' OR $_POST["fecha_fin"] == '')) {
                   $result = mysqli_query($conn,"select * FROM MDP_historial_opcion ORDER BY fecha DESC;");
                 }
		 // Filatrado por fechas
                 elseif($parameter == '' AND $_POST["fecha_init"] != '' AND $_POST["fecha_fin"] != '') {
                   $f_init = mysqli_real_escape_string($conn, $_POST["fecha_init"].' 00:00:00');
                   $f_fin = mysqli_real_escape_string($conn, $_POST["fecha_fin"].' 23:59:59');
                   $query = mysqli_prepare($conn, "select * FROM MDP_historial_opcion WHERE fecha BETWEEN ? AND ? ORDER BY fecha DESC");
                   mysqli_stmt_bind_param($query, "ss", $f_init, $f_fin);
                   mysqli_stmt_execute($query);
                   $result = mysqli_stmt_get_result($query);
                   
                 }
  
                 elseif($parameter != '' AND ($_POST["fecha_init"] == '' OR $_POST["fecha_fin"] == '')) {
                   $parameter = mysqli_real_escape_string($conn, $parameter);
                   $str = mysqli_real_escape_string($conn, $str.'%');
                   $query = mysqli_prepare($conn, "select * FROM MDP_historial_opcion WHERE ".$parameter." LIKE ? ORDER BY fecha DESC");
                   mysqli_stmt_bind_param($query, "s",$str);
                   mysqli_stmt_execute($query);
                   $result = mysqli_stmt_get_result($query);
                  
                 }
                 else {
                   $f_init = mysqli_real_escape_string($conn, $_POST["fecha_init"].' 00:00:00');
                   $f_fin = mysqli_real_escape_string($conn, $_POST["fecha_fin"].' 23:59:59');
		   $parameter = mysqli_real_escape_string($conn, $parameter);
		   $str = mysqli_real_escape_string($conn, $str.'%');
                   $query = mysqli_prepare($conn, "select * FROM MDP_historial_opcion WHERE ".$parameter." LIKE ? AND fecha BETWEEN ? AND ? ORDER BY fecha DESC");
                   mysqli_stmt_bind_param($query, "sss",$str,$f_init, $f_fin);
                   mysqli_stmt_execute($query);
                   $result = mysqli_stmt_get_result($query);
                   
                 }
              }
	      // Se muestra toda la información desde la fecha actual hasta un mes antes
              else {
              $result = mysqli_query($conn,"select * FROM MDP_historial_opcion WHERE fecha BETWEEN DATE_ADD(NOW(), INTERVAL -1 MONTH) AND NOW() ORDER BY fecha DESC;");
              }
	       // Se muestran los atributos de la tabla
               if (mysqli_num_rows($result) > 0) {
                  echo '<table id= "hist_table">';
                   echo '<tr>';
                   echo '<th>Matrícula</th>';
                   echo '<th>Nombre Usuario</th>';
                   echo '<th>ID Máquina</th>';
                   echo '<th>Nombre Máquina</th>';
                   echo '<th>Estado</th>';
                   echo '<th>Fecha</th>';
                   echo '</tr>';
                 echo '</tr>';
		   // Se muestran las tuplas
                   while ($row = mysqli_fetch_assoc($result)) {
                     echo '<tr class = "tabla-colorida">';
                     echo '<tr>';
                     echo '<td>'.$row["matricula"].'</td>';
                     echo '<td>'.$row["nombre_usuario"].'</td>';
                     echo '<td>'.$row["id_maquina"].'</td>';
                     echo '<td>'.$row["nombre"].'</td>';
                     echo '<td>'.$row["estado"].'</td>';
                     echo '<td>'.$row["fecha"].'</td>';
                     echo '</tr>';
                     echo '</tr>';
           	   }
                 echo '</table>';
               }
  
              else {
                echo 'Sin historial para mostrar';
              }
            ?>
          </center>
        </div>
  </div>
</body>
</html>
