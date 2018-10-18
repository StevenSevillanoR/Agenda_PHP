$(function(){
  var l = new Login();
})

class Login {
  constructor() {
    this.submitEvent()
  }

  submitEvent(){
    $('form').submit((event)=>{
      event.preventDefault()
      this.sendForm()
    })
  }

  sendForm(){
    let form_data = new FormData();
    form_data.append('username', $('#username').val())
    form_data.append('password', $('#password').val())
    $.ajax({
      url: '../server/check_login.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: 'POST',
      success: function(php_response){
        console.log(php_response);
        if (php_response.conexion == "OK") {
          alert('Me conecte');
          alert(php_response.acceso);
          if(php_response.motivo == "email correcto"){
            if (php_response.acceso == 'concedido') {
              window.location.href = 'main.html';
            } else {
              //alert(php_response.acceso);
              alert('Contraseña incorrecta, inténtelo de nuevo. ' + php_response.msg);
            }
          }else{
            alert("El usuario ingresado no existe en la base de datos");
          }
        } else {
            alert("No me conecte. "+php_response.msg);
            alert("El usuario ingresado no existe en la base de datos");
          }
      },
      error: function(php_response){
        console.log(php_response.conexion);
        if(php_response.conexion != "OK"){
          alert("Error en la comunicación con el servidor!!");
        }else{
          alert("El usuario ingresado no existe en la base de datos");
        }
        
      }
    })
  }
}
