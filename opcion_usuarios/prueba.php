<?php
 session_start();
 include '../opcion_maquinas/conection.php';
 if (!isset($_SESSION['user_id'])) {
   header('Location: ../sign_in/sign_in.php');
   exit;
 }

 if (isset($_POST["us_estado"])) {
                 $us_estado = mysqli_real_escape_string($conn, $_POST["us_estado"]);
              $ma = mysqli_real_escape_string($conn, $_POST["matricula"]);
              $query = mysqli_prepare($conn, "UPDATE MDP_Usuario SET id_estado = ? WHERE matricula = ?;");
              mysqli_stmt_bind_param($query, "ss",$us_estado,$ma);
              mysqli_stmt_execute($query);
 }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>maquinas</title>
    <link rel="stylesheet" href="styless.css">
    <script src="selectState.js"></script>
</head>
<body>
  <div id="mycontainer">
    <!-- Código del menú lateral, mismo que el de 'Máquinas' e 'Historial' -->
    <div id="left_column">
      <br></br>
      <div id="userimg_config">
        <img id="user_img" src="https://www.logolynx.com/images/logolynx/eb/ebb57b64c932f54ffced3c416b4f652d.jpeg" >
      </div>
  
      <div id="lateral_name">
        <h4><?php echo $_SESSION['user_name']; ?></h4>
      </div>
  
      <div>
        <table  class= lateral_table>
          <tr>
            <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/machines.png"></img></td>
            <td><a href="../opcion_maquinas/prueba.php"><h4>Máquinas</h4></a></td>
          </tr>
        </table>
      </div>
  
      <div>
        <table class= lateral_table>
          <tr>
            <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/reports.png"></img></td>
            <td><a href="../opcion_historial/prueba.php"><h4>Historial</h4></a></td>
          </tr>
        </table>
      </div>
  
      <div>
        <table class= lateral_table>
          <tr>
            <td class="lateral_menu_icons_alineacion"><img class="lateral_menu_icons" src="../logos/users.png"></img></td>
            <td><a href="prueba.php"><h4>Usuarios</h4></a></td>
          </tr>
        </table>
      </div>
  
      <div id="lateral_name">
        <table class="lateral_table" id="logOutSpace">
          <tr>
            <td class="img_disps_align"><a href="../logout.php"><img class="lateral_menu_icons" src="../logos/log_out.png"></a></td>
          </tr>
        </table>
      </div>
    </div>
  
    <!-- Interfaz de 'Usuarios' -->
    <div id="right_column">
      <div id="title">
        <h1>Usuarios</h1>
      </div>
      <form method="post" action="prueba.php">
        <div class="filter_option">
          <!-- Mismo código para filtrar información que en 'Historial', solo cambian los parámetros -->
	  Buscar por:
          <select name="parameter">
            <option value="">Mostrar todo</option>
            <option value="matricula">Matrícula</option>
            <option value="nombre">Nombre</option>
            <option value="Apellido_paterno">Apellido paterno</option>
            <option value="Apellido_materno">Apellido materno</option>
          </select>
        </div>
        <div class="filter_option">
          <input type="text" placeholder="Buscar" maxlength="100" name="str">
        </div>
        <div class="filter_option">
          <input class="btn btn-buscar" type="submit" value="Buscar">
        </div>
      </form>
      <div id="machines_div">
        <?php
          include '../opcion_maquinas/conection.php';
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $parameter = htmlspecialchars($_POST['parameter']);
          $str = htmlspecialchars($_POST['str']);
             if($parameter == '') {
               $result = mysqli_query($conn,"select * FROM MDP_users_detail WHERE matricula NOT IN (SELECT Matricula_usuario FROM MDP_Administrador);");
             }
             else {
              $parameter = mysqli_real_escape_string($conn, $parameter);
              $str = mysqli_real_escape_string($conn, $str.'%');
              $query = mysqli_prepare($conn, "select * FROM MDP_users_detail WHERE ".$parameter." LIKE ? ;");
              mysqli_stmt_bind_param($query, "s",$str);
              mysqli_stmt_execute($query);
              $result = mysqli_stmt_get_result($query);
             }
          }
          else {
          $result = mysqli_query($conn,"select * FROM MDP_users_detail WHERE matricula NOT IN (SELECT Matricula_usuario FROM MDP_Administrador);");
          }
        
	  // Se muestran los atributos de la tabla
          if (mysqli_num_rows($result) > 0) {
            echo '<tr>';
            echo '<td colspan="100%">';
            echo '<table id="hist_table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Matrícula</th>';
            echo '<th>Nombre</th>';
            echo '<th>Apellido Paterno</th>';
            echo '<th>Apellido Materno</th>';
            echo '<th>Estado</th>';
            echo '<th>Editar</th>';
            echo '<th>Eliminar</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
	    // Se muestran las tuplas
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<tr>';
              echo '<td>'.$row["matricula"].'</td>';
              echo '<td>'.$row["nombre"].'</td>';
              echo '<td>'.$row["Apellido_paterno"].'</td>';
              echo '<td>'.$row["Apellido_materno"].'</td>';
              echo '<td>';
              echo '<form id= "miFormulario" method="post" action="prueba.php">';
              echo '<input type="hidden" name="matricula" value="'.$row["matricula"].'">';
              echo '<select name="us_estado" onchange="this.form.submit()">';
              if ($row["estado"] == 'de alta') {
                     echo '<option value = "1">'.$row["estado"].'</option>';
                     echo '<option value="2">de baja</option>';
              }
              else {
                echo '<option value = "2">'.$row["estado"].'</option>';
                echo '<option value="1">de alta</option>';
              }
              echo '</select>';
              echo '</form>';
              echo '</td>';
	      // Botón para actualizar o editar datos de un usuario, despliega una ventana emergente
              echo '<td class="img_space"><a href="update2.php?id='.$row["matricula"].'&update_confirm=true"><img id="add_icon" src="https://cdn-icons-png.flaticon.com/512/45/45706.png"></a></td>';
	      // Botón para eliminar un usuario, despliega una ventana emergente
              echo '<td class="img_space"> <a href="prueba.php?matricula='.$row['matricula'].'&btn-delete=True&nombre='.$row["nombre"].'"><img src="https://cdn-icons-png.flaticon.com/512/40/40002.png" alt="Icon 2" class="image_bottom"></a></td>';
              echo '</tr>';
            }
          }
          else {
            echo '<div><h3>Sin usuarios para mostrar</h3></div>';
          }
        ?>
        <tr>
          <td colspan="100%">
            <div id="add_div">
	      <!-- Botón para aregar usuarios -->
              <label for="btn-modal"><img id="add_icon" src="https://cdn-icons-png.flaticon.com/512/992/992651.png"></label>
              <input type="checkbox" id='btn-modal' <?php if(isset($_GET["matricula_repetida"])) { echo ' checked';}?>>
              <div class="modal">
                <div id="container">
                  <div class="">
                    <h2>Agregar usuario</h2>
                  </div>
                  <?php
		    // Se evita que la matrícula se repita
                    if (isset($_GET["matricula_repetida"])) {
                     echo '<div>';
                     echo '<h3>*Matrícula ya existente*</h3>';
                     echo '</div>';
                    }
                  ?>
		  <!-- Llenado de información para nuevo usuario -->
                  <form class="" action="create2.php" method="post">
                    <div>
                      <input type="text" name="matricula" placeholder="Matrícula" maxlength="9" required>
                    </div>
                    <div>
                      <input type="text" name="nombre" placeholder="Nombre" maxlength="100" required>
                    </div>
                    <div>
                      <input type="text" name="ape_p" placeholder="Apellido paterno" maxlength="100" required>
                    </div>
                    <div>
                      <input type="text" name="ape_m" placeholder="Apellido materno" maxlength="100" required>
                    </div>
                    <button type="submit" class="btn btn-buscar">Agregar</button>
                    <a class="btn btn-gray" href="prueba.php">Cancelar</a>
                  </form>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <?php
          echo '</tbody>';
          echo '</table>';
          if(isset($_SESSION["gbl_update"]) AND $_SESSION["gbl_update"] == "True") {
                  $global_update_id = mysqli_real_escape_string($conn, $_SESSION["global_update_id"]);
		  $query = mysqli_prepare($conn, "SELECT * FROM MDP_Usuario WHERE matricula = ? ");
		  mysqli_stmt_bind_param($query, "s", $global_update_id);
		  mysqli_stmt_execute($query);
		  $result = mysqli_stmt_get_result($query);
                  $row = mysqli_fetch_assoc($result);
                  echo '<div id="add_div">
                  <div class="modal1">
                  <div id="container-up">
                  <div class="">
		  <!-- Ventana emergente para editar usuario -->
                  <h2>Editar usuario</h2>
                  </div>';
		    // Llenado de nueva información
                    echo '<form class="" action="update2.php" method="post">
                           <div>
                             <input type="hidden" name="update_send" value="true">
                           </div>
                           <div>
                            <input type="text" name="nombre" placeholder="Nombre" maxlength="100" value="'.$row["nombre"].'" required>
                           </div>
                           <div>
                           <input type="text" name="ape_p" placeholder="Apellido paterno" maxlength="100" value="'.$row["Apellido_paterno"].'" required>
                           </div>
                           <div>
                           <input type="text" name="ape_m" placeholder="Apellido materno" maxlength="100" value="'.$row["Apellido_materno"].'" required>
                           </div>
                           <button type="submit" class="btn btn-buscar">Editar</button>
                           <a class="btn btn-gray" href="update2.php?update_confirm=false">Cancelar</a>
                      </form>
                  </div>
                  </div>
                  </div>';
          }
	  // Ventana emergente de eliminar usuario
          if(isset($_GET["btn-delete"])) {
                  $global_update_id = mysqli_real_escape_string($conn, $_SESSION["global_update_id"]);
                  $query = mysqli_prepare($conn, "SELECT * FROM MDP_Usuario WHERE matricula = ? ");
                  mysqli_stmt_bind_param($query, "s", $global_update_id);
                  mysqli_stmt_execute($query);
                  $result = mysqli_stmt_get_result($query);
                  $row = mysqli_fetch_assoc($result);
                  echo '<div id="add_div">
                  <div class="modal1">
                  <div id="container-up">
                    <div class="">
                      <h2>Está a punto de eliminar al usuario "'.$_GET['nombre'].'"</h2>
                    </div>
                      <h3>Al momento de eliminar un usuario, también se eliminarán sus registros</h3>
                      <h3>¿Está seguro de eliminar al usuario seleccionado?</h3>
                    <form class="" action="delete2.php?id='.$_GET["matricula"].'" method="post">
                           <button type="submit" class="btn btn-red">Eliminar</button>
                           <a class="btn btn-gray" href="prueba.php">Cancelar</a>
                      </form>
                  </div>
                  </div>
                  </div>';
          }
          echo '</td>';
          echo '</tr>';
        ?>
      </div>
    </div>
  </div>
</body>
</html>
