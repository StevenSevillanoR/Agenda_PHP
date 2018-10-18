<?php
  require('conector.php');

  session_start();

  if (isset($_SESSION['username'])) {

    $con = new ConectorBD('localhost', 'user_agenda', '12345');
    if ($con->initConexion('agenda_db')=='OK') {
      $resultado = $con->consultar(['usuarios'], ['nombre', 'id'], "WHERE usuario ='".$_SESSION['username']."'");
      $fila = $resultado->fetch_assoc();
      $response['nombre']=$fila['nombre'];

      $resultado = $con->obtenerEventos($fila['id']);
      $i=0;
      while ($fila = $resultado->fetch_assoc()) {
          if($fila['fullday']==0){
            $response['infoEventos'][$i]['title']=$fila['titulo'];
            $response['infoEventos'][$i]['start']=$fila['fechaInicio']."T".$fila['horaInicio'];
            $response['infoEventos'][$i]['end']=$fila['fechaFin']."T".$fila['horaFin'];
            $response['infoEventos'][$i]['fullDay']=$fila['fullday'];
          }else{
            $response['infoEventos'][$i]['title']=$fila['titulo'];
            $response['infoEventos'][$i]['start']=$fila['fechaInicio'];
            $response['infoEventos'][$i]['start_hour']=$fila['horaInicio'];
            $response['infoEventos'][$i]['end']=$fila['fechaFin'];
            $response['infoEventos'][$i]['end_hour']=$fila['horaFin'];
            $response['infoEventos'][$i]['fullDay']=$fila['fullday'];
          }
        $i++;
      }
      $response['msg'] = "OK";

    }else {
      $response['msg'] = "No se pudo conectar a la Base de Datos";
    }
  }else {
    $response['msg'] = "No se ha iniciado una sesión";
  }

  echo json_encode($response);

  


 ?>
