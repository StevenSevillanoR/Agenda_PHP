<?php 

  class ConectorBD
  {
    
    private $host;
    private $user;
    private $password;
    private $conexion;
    
    /*private $host = 'localhost';
    private $user = 'user_agenda';
    private $password = '12345'
    private $conexion;
    private $nombre_db = 'agenda_db';*/

    function __construct($host, $user, $password){
      $this->host = $host;
      $this->user = $user;
      $this->password = $password;
    }

    function initConexion($nombre_db){
      $this->conexion = new mysqli($this->host, $this->user, $this->password, $nombre_db);
      if ($this->conexion->connect_error) {
        return "Error:" . $this->conexion->connect_error;
      }else {
        return "OK";
      }
    }

    function newTable($nombre_tbl, $campos){
      $sql = 'CREATE TABLE '.$nombre_tbl.' (';
      $length_array = count($campos);
      $i = 1;
      foreach ($campos as $key => $value) {
        $sql .= $key.' '.$value;
        if ($i!= $length_array) {
          $sql .= ', ';
        }else {
          $sql .= ');';
        }
        $i++;
      }
      return $this->ejecutarQuery($sql);
    }

    function ejecutarQuery($query){
      return $this->conexion->query($query);
    }

    function cerrarConexion(){
      $this->conexion->close();
    }

    function nuevaRestriccion($tabla, $restriccion){
      $sql = 'ALTER TABLE '.$tabla.' '.$restriccion;
      return $this->ejecutarQuery($sql);
    }

    function nuevaRelacion($from_tbl, $to_tbl, $from_field, $to_field){
      $sql = 'ALTER TABLE '.$from_tbl.' ADD FOREIGN KEY ('.$from_field.') REFERENCES '.$to_tbl.'('.$to_field.');';
      return $this->ejecutarQuery($sql);
    }

    function insertData($tabla, $data){
      $sql = 'INSERT INTO '.$tabla.' (';
      $i = 1;
      foreach ($data as $key => $value) {
        $sql .= $key;
        if ($i<count($data)) {
          $sql .= ', ';
        }else $sql .= ')';
        $i++;
      }
      $sql .= ' VALUES (';
      $i = 1;
      foreach ($data as $key => $value) {
        $sql .= $value;
        if ($i<count($data)) {
          $sql .= ', ';
        }else $sql .= ');';
        $i++;
      }
      return $this->ejecutarQuery($sql);
    }

    function getConexion(){
      return $this->conexion;
    }

    function actualizarRegistro($tabla, $data, $condicion){
      $sql = 'UPDATE '.$tabla.' SET ';
      $i=1;
      foreach ($data as $key => $value) {
        $sql .= $key.'='.$value;
        if ($i<sizeof($data)) {
          $sql .= ', ';
        }else $sql .= ' WHERE '.$condicion.';';
        $i++;
      }

      //echo $sql;
      return $this->ejecutarQuery($sql);
    }

    function eliminarRegistro($tabla, $condicion){
      $sql = "DELETE FROM ".$tabla." WHERE ".$condicion.";";
      return $this->ejecutarQuery($sql);
    }

    function consultar($tablas, $campos, $condicion = ""){
      $sql = "SELECT ";
      $a = array_keys($campos);
      $ultima_key = end($a);
      foreach ($campos as $key => $value) {
        $sql .= $value;
        if ($key!=$ultima_key) {
          $sql.=", ";
        }else $sql .=" FROM ";
      }

      $b = array_keys($tablas);
      $ultima_key = end($b);
      foreach ($tablas as $key => $value) {
        $sql .= $value;
        if ($key!=$ultima_key) {
          $sql.=", ";
        }else $sql .= " ";
      }

      if ($condicion == "") {
        $sql .= ";";
      }else {
        $sql .= $condicion.";";
      }
      return $this->ejecutarQuery($sql);
    }

    function datosUsuario($usuario){
      $sql="SELECT * FROM usuarios WHERE usuario='".$usuario."'";
      return $this->ejecutarQuery($sql);
    }

    function obtenerEventos($id){
      $sql="SELECT titulo,fechaInicio,horaInicio,fechaFin,horaFin,fullday 
            FROM eventos AS e WHERE e.usuario_id='".$id."'";
      return $this->ejecutarQuery($sql);
    }

    function eliminarEvento($evento,$usuario){
      $sql="DELETE FROM eventos WHERE id='".$evento."' AND usuario_id='".$usuario."'";
      return $this->ejecutarQuery($sql);
    }
  }

?>