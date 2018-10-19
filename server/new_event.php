<?php
  
  include('conector.php');

  session_start();

  if (isset($_SESSION['username'])) {
    $con = new ConectorBD('localhost', 'user_agenda', '12345');
    if ($con->initConexion('agenda_db')=='OK') {

      $data['titulo'] = "'".$_POST['titulo']."'";
      $data['fechaInicio'] = "'".$_POST['start_date']."'";
      $data['horaInicio'] = "'".$_POST['start_hour']."'";
      $data['fechaFin'] = "'".$_POST['end_date']."'";
      $data['horaFin'] = "'".$_POST['end_hour']."'";
      $data['fullday'] = $_POST['allDay'];

      /*if ($_POST['end_date'] == "" || $_POST['end_hour'] == "" || $_POST['end_date']==null || $_POST['start_date']==null){
        if ($_POST['allDay'] == false){
          $data['fullday'] = 0;

        }else{
          $data['fullday'] = 1;
        }
      }*/
      
      $resultado = $con->consultar(['usuarios'],['id'], "WHERE usuario='".$_SESSION['username']."'");
      $fila = $resultado->fetch_assoc();
      $data['usuario_id'] = $fila['id'];

      

      if ($con->insertData('eventos', $data)) {
        $response['dia']=$_POST['allDay'];
        $response['datos'] = $data;
        $response['msg']= 'OK';
      }else {
        $response['dia']=$_POST['allDay'];
        $response['datos'] = $data;
        $response['msg']= 'No se pudo realizar la inserción de los datos';
      }
    }else {
      $response['msg']= 'No se pudo conectar a la base de datos';
    }
  }else {
    $response['msg']= 'No se ha iniciado una sesión';
  }

  echo json_encode($response);

  //$con->cerrarConexion();

 ?>
