<?php
  
  include('conector.php');

  $data['nombre'] = "'".$_POST['nombre']."'";
  $data['nacimiento'] = "'".$_POST['fechaNacimiento']."'";
  $data['sexo'] = "'".$_POST['sexo']."'";
  $data['usuario'] = "'".$_POST['email']."'";
  $data['psw'] = "'".password_hash($_POST['contrasena'], PASSWORD_DEFAULT)."'";

  $con = new ConectorBD('localhost','user_agenda','12345');
  $response['conexion'] = $con->initConexion('agenda_db');

  if ($response['conexion']=='OK') {
    if($con->insertData('usuarios', $data)){
      $response['msg']="Se ha registrado correctamente!!";
    }else {
      $response['msg']= "Hubo un error y los datos no han sido cargados";
    }
  }else {
    $response['msg']= "No se pudo conectar a la base de datos";
  }

  echo json_encode($response);

  

 ?>
