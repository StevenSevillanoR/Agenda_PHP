<?php

  session_start();
  require('conector.php');

  $con = new ConectorBD('localhost','user_agenda','12345');

  $passw=$_POST["password"];
  $email=$_POST["username"];

  $php_response['conexion'] = $con->initConexion('agenda_db');

  ///*//if ($response['conexion']=='OK') {
    //$resultado_consulta = $con->consultar(['usuarios'],
    //['usuario', 'psw'], 'WHERE usuario="'.$_POST['username'].'" AND psw="'.$_POST['password'].'"');

    //if ($resultado_consulta->num_rows != 0) {
      //$response['acceso'] = 'concedido';
    //}else $response['acceso'] = 'rechazado';
  //}*/

  if ($php_response['conexion'] == 'OK') {

    $resul=$con->datosUsuario($email);

    while ($rows = $resul->fetch_array()) {

        if(password_verify($passw, $rows["psw"])) {
          $_SESSION['id'] = $rows["id"];
          $_SESSION['username']= $rows["usuario"];

          $php_response=array("conexion"=>"OK",
                              "acceso"=>"concedido", 
                              "resul"=>$resul, 
                              "email"=>$email,
                              "msg"=>"OK",
                              "motivo"=>"email correcto",
                              "data"=>"2");
          //echo json_encode($php_response,JSON_FORCE_OBJECT);
        }else if($rows['usuario']!=null || $rows['usuario']!=0){
          $php_response=array("conexion"=>"OK",
                              "acceso"=>"rechazado", 
                              "resul"=>$resul, 
                              "email"=>$email,
                              "msg"=>"La contraseña no coincide con la base de datos. Verifique la contraseña para el usuario ".$email,
                              "motivo"=>"email correcto",
                              "data"=>"2");
          //echo json_encode($php_response,JSON_FORCE_OBJECT);
        }else{
          $php_response=array("conexion"=>"OK",
                              "acceso"=>"rechazado", 
                              "resul"=>$resul, 
                              "email"=>$email,
                              "msg"=>"El usuario ".$email." ingresado no se encuentra en la base de datos. Ingrese un usuario correcto",
                              "motivo"=>"email incorrecto",
                              "data"=>"2");
        }
        echo json_encode($php_response,JSON_FORCE_OBJECT);
      
    }  
      

    //$resultado_consulta = $con->datosUsuario($_POST['username']);

    /*$resultado_consulta = $con->consultar(['usuarios'],
    ['usuario', 'psw'], 'WHERE usuario="'.$_POST['username'].'" AND psw="'.$_POST['password'].'"');

    if ($resultado_consulta->num_rows != 0) {
      $fila = $resultado_consulta->fetch_assoc();
      if (password_verify($_POST['password'], $fila['psw'])) {
        $response['acceso'] = 'concedido';
        session_start();
        $_SESSION['usuario']=$fila['usuario'];
      }else {
        $response['motivo'] = 'Contraseña incorrecta';
        $response['acceso'] = 'rechazado';
      }
    }else{
      $response['motivo'] = 'Email incorrecto';
      $response['acceso'] = 'rechazado';
    }*/
  }else{
    if($php_response['conexion'] != 'OK'){
      $php_response=array("conexion"=>"Error", "acceso"=>" ", "resul"=>" ", "email"=>" ","msg"=>"No se pudo establecer la conexión", "data"=>" ");
      echo json_encode($php_response, JSON_FORCE_OBJECT);
    }
  }

  //echo json_encode($response);

  $con->cerrarConexion();

 ?>
