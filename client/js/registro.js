$(function () {
  
  var r = new Registro();

})

class Registro {
  constructor() {
    this.submitEvent()
  }

  submitEvent(){
    $('#formulario').submit((event)=>{
      event.preventDefault();
      this.checkContrasena();
    })
  }
  
  checkContrasena() {
    var contrasena = $('#contrasena').val();
    var repContrasena = $('#contrasenaRepetida').val();

    if (contrasena === repContrasena) {
      this.getDatos();

    } else {
      alert('Las contraseñas no coinciden')
    }
  }

  getDatos() {
    var form_data = new FormData();
    form_data.append('nombre', $('#nombre').val());
    form_data.append('fechaNacimiento', $('#fechaNacimiento').val());
    form_data.append('email', $('#email').val());
    form_data.append('contrasena', $('#contrasena').val());
    form_data.append('sexo', $('input[name="sexo"]:checked').val());

    /*if($('input[name="sexo"]').val() == $('#masculino').val()){
      form_data.append('sexo', $('#masculino').val());
      console.log($('input[name="sexo"]'));
    }

    if ($('input[name="sexo"]').val() == $('#femenino').val()) {
      form_data.append('sexo', $('#femenino').val());
      alert($('input[name="sexo"]'));
    }*/

    this.sendForm(form_data);
  }

  sendForm(formData) {
    $.ajax({
      url: '../server/create_user.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: formData,
      type: 'POST',
      success: function (php_response) {
        alert(php_response.msg);
        window.location.href = 'index.html'
      },
      error: function () {
        alert("Error en la comunicación con el servidor!!");
      }
    })
  }

    /*submitEvent() {
        $('form').submit((event) => {
            event.preventDefault()
            this.sendForm()
        })
    }

    sendForm() {
        let form_data = new FormData();
        form_data.append('username', $('#user').val())
        form_data.append('password', $('#password').val())
        $.ajax({
            url: '../server/create_user.php',
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            data: form_data,
            type: 'POST',
            success: function (php_response) {
                if (php_response.msg == "OK") {
                    window.location.href = 'main.html';
                } else {
                    alert(php_response.msg);
                }
            },
            error: function () {
                alert("error en la comunicación con el servidor");
            }
        })
    }*/
}