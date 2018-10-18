<?php
 
 session_start();

 require('conector.php');

 if(isset($_SESSION['username'])){
    $con = new ConectorBD('localhost','user_agenda','12345');

    $tituloA = $_POST['titulo'];
    $fechaIniA = $_POST['start_date'];
    $horaIniA = $_POST['start_hour'];
    $fechaFinA = $_POST['end_date'];
    $horaFinA = $_POST['end_hour'];
    
    $titulo = $_POST['tituloP'];
    $fechaIni = $_POST['fechaIniP'];
    $horaIni = $_POST['horaIniP'];
    $fechaFin = $_POST['fechaFinP'];
    $horaFin = $_POST['horaFinP'];

    $datosA['titulo']="'".$tituloA."'";
    $datosA['fechaInicio']="'".$fechaIniA."'";
    $datosA['horaInicio']="'".$horaIniA."'";
    $datosA['fechaFin']="'".$fechaFinA."'";
    $datosA['horaFin']="'".$horaFinA."'";
    

    if($con->initConexion('agenda_db')=='OK'){
      $resultado1 = $con->consultar(['usuarios'],['id'], "WHERE usuario='".$_SESSION['username']."'");
      $fila1 = $resultado1->fetch_assoc();

      $resultado = $con->consultar(['eventos'],['id','titulo','fechaInicio','horaInicio','fechaFin','horaFin', 'fullday'], "WHERE usuario_id='".$fila1['id']."'");
      
      while($fila=$resultado->fetch_array()){
        
          if($fila['titulo']==$titulo && $fila['fechaInicio']==$fechaIni){
            if($fila['fullday']==0){
              $datosA['fullday']=0;
              if($fila['horaInicio']==$horaIni){
                if($fila['horaFin']==$horaFin and $fila['fechaFin']==$fechaFin){
                  $resul = $con->actualizarRegistro('eventos', $datosA, "id=".$fila['id']);
                  $response['id']=$fila['id'];
                  $response['actualizado'] = $resul;
                  $response['msg'] = "OK";
                  break;
                }else if($fila['fechaFin']==null){
                  $resul = $con->actualizarRegistro('eventos', $datosA, '"id='.$fila['id'].'"');
                  $response['id']=$fila['id'];
                  $response['actualizado'] = $resul;
                  $response['msg'] = "OK";
                  break;
                }else {
                  $response['id']=$fila['id'];
                  $response['actualizado']=false;
                  $response['msg']="El id que ingreso no se encuentra en la base de datos";
                }
              }else if($fila['horaInicio']==null or $fila['horaInicio']==""){
                if($fila['fechaFin']==$fechaFin && $fila['horaFin']==$horaFin){
                  $resul = $con->actualizarRegistro('eventos', $datosA, "id='".$fila['id']."'");
                  $response['id']=$fila['id'];
                  $response['actualizado'] = $resul;
                  $response['msg'] = "OK";
                  break;
                }else if($fila['fechaFin']==null){
                  $resul = $con->actualizarRegistro('eventos', $datosA, "id='".$fila['id']."'");
                  $response['id']=$fila['id'];
                  $response['actualizado'] = $resul;
                  $response['msg'] = "OK";
                  break;
                }else{
                  $response['id']=$fila['id'];
                  $response['actualizado']=false;
                  $response['msg']="El id que ingreso no se encuentra en la base de datos";
                  break;
                }
              }else{
                $response['id']=$fila['id'];
                $response['actualizado']=false;
                $response['msg']="El id que ingreso no se encuentra en la base de datos";
                break;
              }
            }else{
              $datosA['fullday']=1;
              $resul = $con->actualizarRegistro('eventos', $datosA, "id='".$fila['id']."'");
              $response['id']=$fila['id'];
              $response['actualizado'] = $resul;
              $response['msg'] = "OK";
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
              $response['actualizado']=false;
              $response['msg']="El id que ingreso no se encuentra en la base de datos 4";
              break;
          }

        $response['conexion'] = "OK";
        echo json_encode($response, JSON_FORCE_OBJECT);
      } 
    }else{
      $response['msg'] = "No se pudo conectar a la base de datos";
      //$response['id']=$fila['id'];
      $response['actualizado'] = "No hay elementos actualizados";
      //echo json_encode($response, JSON_FORCE_OBJECT);
    }   
    //$con->cerrarConexion(); 
  }else{
    $response['actualizado'] = "";
    $response['id']="";
    $response['msg'] = "No se ha iniciado sesión"; 
    //echo json_encode($response, JSON_FORCE_OBJECT);   
  }

?>
