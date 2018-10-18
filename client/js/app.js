
let eventoDrag;

class EventsManager {

    constructor() {
        eventoDrag = [{}];
        this.obtenerDataInicial()
    }

    obtenerDataInicial() {
        let url = '../server/getEvents.php'
        $.ajax({
          url: url,
          dataType: "json",
          cache: false,
          processData: false,
          contentType: false,
          type: 'GET',
          success: (data) =>{
            if (data.msg=="OK") {
              this.poblarCalendario(data.infoEventos)
            }else {
              alert(data.msg)
              window.location.href = 'index.html';
            }
          },
          error: function(){
            alert("error en la comunicación con el servidor");
          }
        })

    }

    poblarCalendario(eventos) {
        $('.calendario').fullCalendar({
            header: {
        		left: 'prev,next today',
        		center: 'title',
        		right: 'month,agendaWeek,basicDay'
        	},
        	//defaultDate: '2018-10-14',
        	navLinks: true,
        	editable: true,
        	eventLimit: true,
          droppable: true,
          dragRevertDuration: 0,
          timeFormat: 'H:mm',
          eventDrop: (event) => {
              this.actualizarEvento(event)
          },
          events: eventos,
          eventDragStart: (event,jsEvent) => {
            $('.delete-btn').find('img').attr('src', "img/trash-open.png");
            $('.delete-btn').css('background-color', '#a70f19')
            this.fromEvent(event);
          },
          eventDragStop: (events,jsEvent) =>{
            var trashEl = $('.delete-btn');
            var ofs = trashEl.offset();
            var x1 = ofs.left;
            var x2 = ofs.left + trashEl.outerWidth(true);
            var y1 = ofs.top;
            var y2 = ofs.top + trashEl.outerHeight(true);
            if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 &&
                jsEvent.pageY >= y1 && jsEvent.pageY <= y2) {
                  this.eliminarEvento(events, jsEvent)
                  $('.calendario').fullCalendar('removeEvents', events._id);
                  //window.location.reload();
            }
          }
        })
    }

    anadirEvento(){
      var form_data = new FormData();
      form_data.append('titulo', $('#titulo').val())
      form_data.append('start_date', $('#start_date').val())
      //form_data.append('allDay', document.getElementById('allDay').checked)
      if (!document.getElementById('allDay').checked) {
        form_data.append('allDay', false)
        form_data.append('end_date', $('#end_date').val())
        form_data.append('end_hour', $('#end_hour').val())
        form_data.append('start_hour', $('#start_hour').val())
      }else{
        form_data.append('allDay', true)
        form_data.append('end_date', "")
        form_data.append('end_hour', "")
        form_data.append('start_hour', "")
      }

      //console.log(!document.getElementById('allDay').checked);
      //console.log(form_data.getAll());

      $.ajax({
        url: '../server/new_event.php',
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{
          console.log(data, data.datos, data.dia);
          if (data.msg=="OK") {
            alert('Se ha añadido el evento exitosamente')
            if (document.getElementById('allDay').checked) {
              alert("Todo el día")
              $('.calendario').fullCalendar('renderEvent', {
                title: data.titulo,
                start: $('#start_date').val()+"T00:00:00",
                allDay: true
              })
              document.location.href="main.html";
            }else {
              alert("No es de todo el día")
              $('.calendario').fullCalendar('renderEvent', {
                title: data.titulo,
                start: data.fechaInicio+"T"+data.horaInicio+":00",
                allDay: false,
                end: data.fechaFin+"T"+data.horaFin+":00"
              })
              window.location.reload();
            }
            //this.obtenerDataInicial();
          } else if (data.msg = "No se pudo realizar la inserción de los datos") {
            alert(data.msg)
          }
        },
        error: function(data){
          console.log(data)
          alert("Error en la comunicación con el servidor!!");
        }
      })

    }

    eliminarEvento(event, jsEvent){

      var form_data = new FormData();
      
      if(event.end != null){
        console.log(event.title,
          event.start._i.substring(0, 10),
          event.start._i.substring(11),
          event.end._i.substring(0, 10),
          event.end._i.substring(11));
        form_data.append('titulo', event.title)
        form_data.append('fechaIni', event.start._i.substring(0, 10))
        form_data.append('horaIni', event._start._i.substring(11))
        form_data.append('fechaFin', event.end._i.substring(0, 10))
        form_data.append('horaFin', event._end._i.substring(11))
      }else{
        console.log(event.title,
          event.start._i.substring(0, 10),
          event.start._i.substring(11));
        form_data.append('titulo', event.title)
        form_data.append('fechaIni', event.start._i.substring(0, 10))
        form_data.append('horaIni', event._start._i.substring(11))
        form_data.append('fechaFin', null)
        form_data.append('horaFin', null)
      }      

      console.log(event);
      
      $.ajax({
        url: '../server/delete_event.php',
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{
          console.log(data, data.eliminado, event._id);
          if (data.msg=="OK" && data.eliminado==true){
            alert('Se ha eliminado el evento exitosamente');
            //window.location.reload();
            
          }else if(data.eliminado == false){
            alert(data.msg);
            //window.location.reload();
            
          }else{
            alert("No hay comunicación con el servidor");
            window.location.reload();
            
          }
        },
        error: function(data){
          console.log(event._id, data);
          if(data.conexion =='OK' && data.eliminado!=false){
            alert("Se ha eliminado el evento exitosamente");
          }else{
            alert("Error en la comunicación con el servidor!");
          }
          
        }
      })
      $('.delete-btn').find('img').attr('src', "img/trash.png");
      $('.delete-btn').css('background-color', '#8B0913');
    }

    fromEvent(event){

      //var form_data = new FormData();
      var form_data ={};

      if (event.end != null) {
        console.log(event.title,
          event.start._i.substring(0, 10),
          event.start._i.substring(11),
          event.end._i.substring(0, 10),
          event.end._i.substring(11));
        form_data = { id: event._id,
                      titulo: event.title,
                      fechaIni: event.start._i.substring(0, 10),
                      horaIni:event._start._i.substring(11),
                      fechaFin: event.end._i.substring(0, 10),
                      horaFin: event._end._i.substring(11)
        }
                    
        /*form_data.append('titulo', event.title)
        form_data.append('fechaIni', event.start._i.substring(0, 10))
        form_data.append('horaIni', event._start._i.substring(11))
        form_data.append('fechaFin', event.end._i.substring(0, 10))
        form_data.append('horaFin', event._end._i.substring(11))*/
        console.log(form_data);
        eventoDrag = form_data;
        return form_data;
      } else {
        console.log(event.title,
          event.start._i.substring(0, 10),
          event.start._i.substring(11));
        form_data = { id: event._id,
                      titulo: event.title,
                      fechaIni: event.start._i.substring(0, 10),
                      horaIni:event._start._i.substring(11),
                      fechaFin: null,
                      horaFin: null
        }
        
        /*form_data.append('titulo', event.title)
        form_data.append('fechaIni', event.start._i.substring(0, 10))
        form_data.append('horaIni', event._start._i.substring(11))
        form_data.append('fechaFin', null)
        form_data.append('horaFin', null)*/
        console.log(form_data);
        eventoDrag = form_data;
        return form_data;
      }

      
      //console.log(JSON.stringify(form_data));
    }

    actualizarEvento(evento) {
        let titulo = evento.title,
            _id = evento._id,
            start = moment(evento.start).format('YYYY-MM-DD HH:mm:ss'),
            end = moment(evento.end).format('YYYY-MM-DD HH:mm:ss'),
            form_data = new FormData(),
            start_date,
            end_date,
            start_hour,
            end_hour

        start_date = start.substr(0,10)
        end_date = end.substr(0,10)
        start_hour = start.substr(11,8)
        end_hour = end.substr(11,8)

        //Evento en el Drop
        form_data.append('id', _id)
        form_data.append('titulo', titulo)
        form_data.append('start_date', start_date)
        form_data.append('end_date', end_date)
        form_data.append('start_hour', start_hour)
        form_data.append('end_hour', end_hour)

        //Evento antes del Drag
        from_data.append('idP', eventoDrag.id)
        form_data.append('tituloP', eventoDrag.titulo)
        form_data.append('fechaIniP', eventoDrag.fechaIni)
        form_data.append('fechaFinP', eventoDrag.fechaFin)
        form_data.append('horaIniP', eventoDrag.horaIni)
        form_data.append('horaFinP', eventoDrag.horaFin)

        console.log(form_data, eventoDrag, eventoDrag.titulo, eventoDrag.fechaIni);

        $.ajax({
          url: '../server/update_event.php',
          dataType: "json",
          cache: false,
          processData: false,
          contentType: false,
          data: form_data,
          type: 'POST',
          success: (data) =>{
            console.log(data)
            if (data.msg=="OK" && data.actualizado==true) {
              alert('Se ha actualizado el evento exitosamente')
              //window.location.reload();
            }else if(data.msg=="aa" && data.actualizado == false){
              alert(data.msg);
              //window.location.reload();
            }else{
              alert("No hay comunicación con el servidor");
              //window.location.reload();
            }
          },
          error: function(data){
            //console.log(data)
            console.log(evento._id, data);
            if (data.conexion == 'OK' && data.actualizado != false) {
              alert("Se ha actualizado el evento exitosamente");
            } else if(data.conexion != 'OK' || data.actualizado==false){
              alert("Error en la comunicación con el servidor!");
            }
          }
        })
    }

}


$(function(){

  $('#logout').click(function () {
    logoutRequest();
  })

  initForm();
  var e = new EventsManager();
  $('form').submit(function(event){
    event.preventDefault()
    e.anadirEvento()
  })
});

function logoutRequest() {
  $.ajax({
    url: '../server/logout.php',
    dataType: "text",
    cache: false,
    processData: false,
    contentType: false,
    type: 'GET',
    success: function (php_response) {
      alert("Saliendo")
      window.location.href = 'index.html';
    },
    error: function () {
      alert("error en la comunicación con el servidor");
    }
  })
}

function initForm(){
  $('#start_date, #titulo, #end_date').val('');
  $('#start_date, #end_date').datepicker({
    dateFormat: "yy-mm-dd"
  });
  $('.timepicker').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
    minTime: '5',
    maxTime: '23:30',
    defaultTime: '7',
    startTime: '5:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
  });
  $('#allDay').on('change', function(){
    if (this.checked) {
      $('.timepicker, #end_date').attr("disabled", "disabled")
    }else {
      $('.timepicker, #end_date').removeAttr("disabled")
    }
  })

}
