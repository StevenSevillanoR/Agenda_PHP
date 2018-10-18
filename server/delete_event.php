<?php

  session_start();

  require('conector.php');

  if (isset($_SESSION['username'])){
    $con = new ConectorBD('localhost','user_agenda','12345');
    
    $titulo = $_POST['titulo'];
    $fechaIni = $_POST['fechaIni'];
    $horaIni = $_POST['horaIni'];
    $fechaFin = $_POST['fechaFin'];
    $horaFin = $_POST['horaFin'];

    if($con->initConexion('agenda_db')=='OK'){
      $resultado1 = $con->consultar(['usuarios'],['id'], "WHERE usuario='".$_SESSION['username']."'");
      $fila1 = $resultado1->fetch_assoc();

      $resultado = $con->consultar(['eventos'],['id','titulo','fechaInicio','horaInicio','fechaFin','horaFin', 'fullday'], "WHERE usuario_id='".$fila1['id']."'");
      //$fila2 = $resultado->fetch_assoc();
      //$fila=$resultado->fetch_array();

      $response['eliminado']=false;

      while($fila=$resultado->fetch_array()){
        
          if($fila['titulo']==$titulo && $fila['fechaInicio']==$fechaIni){
            if($fila['fullday']==0){
              if($fila['horaInicio']==$horaIni){
                if($fila['horaFin']==$horaFin and $fila['fechaFin']==$fechaFin){
                  $resul = $con->eliminarEvento($fila['id'], $fila1['id']);
                  $response['id']=$fila['id'];
                  $response['eliminado'] = $resul;
                  $response['msg'] = "OK";
                  $response['conexion'] = "OK";
                  //echo json_encode($response, JSON_FORCE_OBJECT);
                  break;
                }else if($fila['fechaFin']==null){
                  $resul = $con->eliminarEvento($fila['id'], $fila1['id']);
                  $response['id']=$fila['id'];
                  $response['eliminado'] = $resul;
                  $response['msg'] = "OK";
                  $response['conexion'] = "OK";
                  //echo json_encode($response, JSON_FORCE_OBJECT);
                  break;
                }else {
                  $response['id']=$fila['id'];
                  $response['eliminado']=false;
                  $response['msg']="El id que ingreso no se encuentra en la base de datos";
                }
              }else if($fila['horaInicio']==null or $fila['horaInicio']==""){
                if($fila['fechaFin']==$fechaFin && $fila['horaFin']==$horaFin){
                  $resul = $con->eliminarEvento($fila['id'], $fila1['id']);
                  $response['id']=$fila['id'];
                  $response['eliminado'] = $resul;
                  $response['msg'] = "OK";
                  $response['conexion'] = "OK";
                  //echo json_encode($response, JSON_FORCE_OBJECT);
                  break;
                }else if($fila['fechaFin']==null){
                  $resul = $con->eliminarEvento($fila['id'], $fila1['id']);
                  $response['id']=$fila['id'];
                  $response['eliminado'] = $resul;
                  $response['msg'] = "OK";
                  $response['conexion'] = "OK";
                  //echo json_encode($response, JSON_FORCE_OBJECT);
                  break;
                }else{
                  $response['id']=$fila['id'];
                  $response['eliminado']=false;
                  $response['msg']="El id que ingreso no se encuentra en la base de datos";
                  //break;
                }
              }else{
                $response['id']=$fila['id'];
                $response['eliminado']=false;
                $response['msg']="El id que ingreso no se encuentra en la base de datos";
                //break;
              }
            }else{
              $resul = $con->eliminarEvento($fila['id'], $fila1['id']);
              $response['id']=$fila['id'];
              $response['eliminado'] = $resul;
              $response['msg'] = "OK";
              $response['conexion'] = "OK";
              //echo json_encode($response, JSON_FORCE_OBJECT);
              break;
            }
          }else{
              $response['eventos'] = $fila;
              $response['usuario_id']=$fila1['id'];
              $response['titulo'] = $titulo;
              $response['fechaInicio'] = $fechaIni;
              $response['horaInicio'] = $horaIni;
              $response['fechaFin'] = $fechaFin;
              $response['horaFin'] = $horaFin;
              $response['id']=$fila['id'];
              $response['eliminado']=false;
              $response['msg']="El id que ingreso no se encuentra en la base de datos de ".$fila1['id'];
              
              //break;
          }

        $response['eventos'] = $fila;
        $response['conexion'] = "OK";
        //echo json_encode($response, JSON_FORCE_OBJECT);
        
      }
      echo json_encode($response, JSON_FORCE_OBJECT);
    }else{
      $response['msg'] = "No se pudo conectar a la base de datos";
      //$response['id']=$fila['id'];
      $response['eliminado'] = "No hay elementos eliminados";
      //echo json_encode($response, JSON_FORCE_OBJECT);
    }  
    //$con->cerrarConexion();  
  }else{
    $response['eliminado'] = "";
    $response['id']="";
    $response['msg'] = "No se ha iniciado sesión"; 
    //echo json_encode($response, JSON_FORCE_OBJECT);   
  }

  $con->cerrarConexion();

 ?>
