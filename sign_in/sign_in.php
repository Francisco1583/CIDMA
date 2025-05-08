<?php
// se verifica que se haya iniciado sesion mediante la existencia de una variable de sesión, de ser el caso 
// redirige el acceso a la interfaz de maquinas.
 session_start();
 if(isset($_SESSION['user_id'])) {
   header('Location: ../opcion_maquinas/prueba.php');
   exit;
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <link rel="stylesheet" href="sign_in_css.css">
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <table  class="container">
      <tr >
        <td id="title"><h1>Mechatronics Lab</h1></td>
      </tr>
      <tr>
        <td>
          <form class="" action="sign_inphp.php" method="post">
            <table  class="datos">
              <?php
                if(isset($_SESSION['validation']) AND $_SESSION['validation'] == False) {
                 echo '<tr>';
                 echo '<td class="error_inicio"><h4>¡Usuario o Contraseña Incorrectos!</h4></td>';
                 echo '</tr>';
                }
              ?>
              <tr>
                <td class="td_input"><input id="entrada" type="text" placeholder="Matrícula" name="user" /></td>
              </tr>
              <tr>
                <td class="td_input"><input id="entrada" type="password" placeholder="Contraseña" name="password"/></td>
              </tr>
              <tr class="espacios">
              </tr>
              <tr>
                <td id="iniciar_sesion"><input type="submit" value="Ingresar" id="ingresar" /></td>
              </tr>
            </table>
          </form>
        </td>
      </tr>
    </table>
  </body>
</html>
