<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<style>
	#mapa{
		width:400px;
		height:400px;
		float:left;
		}
	#infor{
		width:400px;
		height:400px;
		float:left;
		}
</style>
<link rel="stylesheet" type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap.min.css">

<script  src = "https://maps.googleapis.com/maps/api/js?callback=initMap" async  defer ></script> 
<script src="js/jquery-1.11.3.js"></script>
<!--ARCHIVOS JS DE BOOTSTRAP-->
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script>
	//VARIABLES GENERALES
	//DECLARAS FUERA DEL READY DE JQUERY
	var map;
	var markers = [];
	var marcadores_bd=[];
	var mapa = null; //VARIABLE GENERAL PARA EL MAPA
	
	function deleteMarkers(lista){		
		for(i in lista){
			lista[i].setMap(null);
		}
	}
	
	function initMap(){
		alert("entra");
		
		var formulario = $('#formulario');
        //COODENADAS INICIALES -16.5207007,-68.1615534
		//VARIABLE PARA EL PUNTO INICIAL
		var punto = new google.maps.LatLng(-16.5207007,-68.1615534);
		//VARIABLE PARA CONFIGURACION INICIAL
		var config = {
			zoom:13,
			center:punto,
			mapTypeId: google.maps.MapTypeId.ROADMAP
			}
	
		mapa = new google.maps.Map( $("#mapa")[0], config );
		
		google.maps.event.addListener(mapa, "click", function(event){
			//alert(event.latLng);
			//OBTENER COORDENADAS POR SEPARADO						
			var coordenadas = event.latLng.toString();
			coordenadas = coordenadas.replace("(", "");
			coordenadas = coordenadas.replace(")", "");
			
			var lista = coordenadas.split(",");			
			//alert(lista[0]+"---"+lista[1])
			var direccion = new google.maps.LatLng(lista[0], lista[1]);
			//variable marcador
			var marcador = new google.maps.Marker({
				//titulo: prompt("Titulo del marcador"),
				position: direccion,
				map: mapa, //ENQUE MAPA SE UBICARA EL MARCADOR
				animation: google.maps.Animation.DROP, //COMO APARECERA EL MARCADOR
				draggable: false // NO PERMITIR EL ARRASTRE DEL MARCADOR
				//title:"Hello World!"
			});
			
			//PASAR LAS COORDENADAS AL FORMULARIO
			formulario.find("input[name='cx']").val(lista[0]);
			formulario.find("input[name='cy']").val(lista[1]);
			//UBICAR EL FOCO EN EL CAMPO TITULO
			formulario.find("input[name='titulo']").focus();
			
			//UBICAR EL MARCADOR EN EL MAPA
			//setMapOnAll(null);
			markers.push(marcador);
			
			//AGREGAR EVENTO CLICK AL MARCADOR
			google.maps.event.addListener(marcador, "click", function(){
				//alert(marcador.titulo);
			});
			deleteMarkers(markers);
			marcador.setMap(mapa);
		});	
		
	}

	$(document).ready(function(e) {				
	
		$('#btn_grabar').on('click', function(){
			//INSTANCIAR EL FORMULARIO
			var f = $('#formulario');
			
			//VALIDAR CAMPO TITULO
			if(f.find("input[name='titulo']").val().trim() == ""){
				alert("Falta titulo");
				return false;
			}
			//VALIDAR CAMPO CX
			if(f.find("input[name='cx']").val().trim() == ""){
				alert("Falta coordenada CX");
				return false;
			}
			//VALIDAR CAMPO CY
			if(f.find("input[name='cy']").val().trim() == ""){
				alert("Falta coordenada CY");
				return false;
			}
			
			if(f.hasClass("busy")){
				//CUANDO SE HAGA CLICK EN EL BOTON GRABAR
				//SE LE MARCARA CON UNA CLASE 'BUSY' INDICANDO
				//QUE YA SE HA PRESIONADO, Y NO PERMITIR QUE SE 
				//REALIZE LA MISMA OPERACION HASTA QUE SE TERMINE
				// SI TIENE LA CLASE 'BUSY', YA NO HARA NADA
				return false;
			}
			//SI NO TIENE LA CLASE BUSY, SE LA PONDREMOS AHORA
			f.addClass("busy");
			//Y CUANDO QUITAR LA CLASE BUSY?
			//CUANDO SE TERMINE DE PROCESAR ESTA SOLICITUD
			//ES DECIR EN EL EVENTO COMPLETE
			var loader_grabar = $("#loader_grabar");
			$.ajax({
				type:"POST",
				url:"docsMaps/iajax.php",
				dataType:"JSON",
				data:f.serialize()+"&tipo=grabar",
				success: function(data){
				  //alert(data.mensaje);
				  if(data.estado == "ok"){
					  loader_grabar.removeClass("label-warning").addClass("label-success").text("Grabado OK").delay(4000).slideUp();
					  listar();
				  }else{
					  alert(data.mensaje);
					}
				},
				beforeSend: function(){
					//NOTIFICAR AL USUARIO MIENTRAS QUE SE PROCESA SU SOLICITUD
					loader_grabar.removeClass("label-success").addClass("label label-warning").text("Procesando...").slideDown();
				},
				complete: function(){
					//QUITAR LA CLASE BUSY
					f.removeClass("busy");
					f[0].reset();
					//[0] JQUERY TRABAJA CON ARRAY DE ELEMENTOS JAVASCRIPT NO
					//ASI QUE SE DEBE ESPECIFICAR CUAL ELEMENT|O SE HARA RESET
					
					//AHORA PERMITIRA OTRA VEZ QUE SE REALICE LA ACCION					
					//NOTIFICAR QUE SE HA TERMINADO DE PROCESAR
					loader_grabar.removeClass("label-warning").addClass("label-success").text("Grabado OK").delay(4000).slideUp();
				}
			});
			return false;
		});
		//BORRAR
		$("#btn_borrar").on("click", function(){
			//CONFIRMAR ACCION DEL USUARIO
			if(confirm("Está seguro?")==false){
				//NO HAY NADA
				return;
			}
			var formulario_edicion = $("#formulario_edicion");
			$.ajax({
				type: "POST",
				url:"docsMaps/iajax.php",
				data:formulario_edicion.serialize()+"&tipo=borrar",
				dataType:"JSON",
				success: function(data){
					//SABER CUANDO SE BORRO CORRECTAMENTE
					if(data.estado == "ok"){
						//MOSTRAR EL MENSAJE
						alert(data.mensaje);
						//BORRAR MARCADORES NUEVOS EN CASO DE QUE HUBIESEN
						deleteMarkers(marcadores_bd);
						//LIMPIAR EL FORMULARIO
						formulario_edicion[0].reset();
						//LISTAR OTRA VEZ LOS MARCADORES
						listar();
					}else{
						//ERROR AL BORRAR
						alert(data.mensaje);
					}
					
				},
				beforeSend: function(){
					
				},
				complete:function(){
					
				}
			});
		});
		//CARGAR PUNTOS AL TERMINAR DE CARGAR LA PAGINA
		listar();
		// BUSCADOR
		$('#search').on('click', function() {			
			// Obtenemos la dirección y la asignamos a una variable
			var address = $('#address').val();			
			// Creamos el Objeto Geocoder
			var geocoder = new google.maps.Geocoder();
			// Hacemos la petición indicando la dirección e invocamos la función
			// geocodeResult enviando todo el resultado obtenido
			geocoder.geocode({ 'address': address}, geocodeResult);
		});
		
	});
	//FUERA DE READY DE JQUERY
	//FUNCION PARA RECUPERAR PUNTOS DE LA BD
	function listar(){
		//ANTES DE LISTAR MARCADORES
		//SE DEBEN QUITAR LOS ANTERIORES DEL MAPA
		
		deleteMarkers(markers);
		var formulario_edicion = $("#formulario_edicion");
		$.ajax({
			type:"POST",
			url:"docsMaps/iajax.php",
			dataType:"JSON",
			data:"&tipo=listar",
			success: function(data){
				if(data.estado=="ok"){
					//alert('Hay puntos en la BD');					
					$.each(data.mensaje, function(i, item){
						//OBTENER LAS COORDENADAS DEL PUNTO
						var posi = new google.maps.LatLng(item.cx, item.cy);
						//CARGAR LAS PROPIEDADES AL MARCADOR
						var marca = new google.maps.Marker({
							idMarcador:item.IdPunto,
							position:posi,
							titulo: item.Titulo,
							cx:item.cx,//esas coordenadas vienen de la BD
							cy:item.cy//esas coordenadas vienen de la BD
						});
						//AGREGAR EVENTO CLICK AL MARCADOR
						//MARCADORES QUE VIENEN DE LA BASE DE DATOS
						google.maps.event.addListener(marca, 'click', function(){
							//alert("Hiciste click en "+marca.idMarcador + " - " + marca.titulo);
							//ENTRAR EN EL SEGUNDO COLAPSIBLE
							//Y OCULTAR EL PRIMERO
							$("#collapseTwo").collapse("show");
							$("#collapseOne").collapse("hide");
							//VER DOCUMENTACION DE BOOTSTRAP
							
							//AHORA PASAR LA INFORMACION DEL MARCADOR
							//AL FORMULARIO
							formulario_edicion.find("input[name='id']").val(marca.idMarcador);
							formulario_edicion.find("input[name='titulo']").val(marca.titulo).focus();
							formulario_edicion.find("input[name='cx']").val(marca.cx);
							formulario_edicion.find("input[name='cy']").val(marca.cy);
							
						});
						//AGREGAR EL MARCADOR A LA VARIABLE MARCADORES_BD
						marcadores_bd.push(marca);
						//UBICAR EL MARCADOR EN EL MAPA
						marca.setMap(mapa);
					});
				}else{
					alert('No hay puntos en la BD');
				}
			},
			beforeSend: function(){
			},
			complete: function(){
			}
		});
	}
	//PLANTILLA AJAX	
	 
	function geocodeResult(results, status) {
		// Verificamos el estatus
		if (status == 'OK') {
			alert('entrar');
			// Si hay resultados encontrados, centramos y repintamos el mapa
			// esto para eliminar cualquier pin antes puesto
			var mapOptions = {
				center: results[0].geometry.location,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map($("#mapa").get(0), mapOptions);
			// fitBounds acercará el mapa con el zoom adecuado de acuerdo a lo buscado
			map.fitBounds(results[0].geometry.viewport);
			// Dibujamos un marcador con la ubicación del primer resultado obtenido
			var markerOptions = { position: results[0].geometry.location }
			var marker = new google.maps.Marker(markerOptions);
			marker.setMap(map);
		} else {
			// En caso de no haber resultados o que haya ocurrido un error
			// lanzamos un mensaje con el error
			alert("Geocoding no tuvo éxito debido a: " + status);
		}
	}
</script>
</head>

<body>
	<div id="mapa">
    </div>
    <div id="infor">
    	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  Agregar
                </a>
              </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                <form id="formulario">
                    <table>
                        <tr>
                            <td>Título</td>
                            <td><input type="text" class="form-control"  name="titulo" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td>Coordenada X</td>
                            <td><input type="text" class="form-control" readonly name="cx" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td>Coordenada Y</td>
                            <td><input type="text" class="form-control" readonly name="cy" autocomplete="off"/></td>
                        </tr>
                        <!-- Aqui estar� se colocaran los mensajes para el usuario -->
                        <tr>
                            <td></td>
                            <td><span id="loader_grabar" class=""></span></td>
                        </tr>
                        <tr>
                            <td><button type="button" id="btn_grabar" class="btn btn-success btn-sm">Grabar</button></td>
                            <td><button type="button" class="btn btn-danger btn-sm">Cancelar</button></td>
                        </tr>
                    </table>
                </form>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                  Eliminar
                </a>
              </h4>
            </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
              <div class="panel-body">
                <form id="formulario_edicion">
                  <!-- CAMPO OCULTO PARA LA VARIABLE ID -->
                  <input type="hidden" name="id"/>
                    <table>
                        <tr>
                            <td>Título</td>
                            <td><input type="text" class="form-control"  name="titulo" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td>Coordenada X</td>
                            <td><input type="text" class="form-control" readonly  name="cx" autocomplete="off"/></td>
                        </tr>
                        <tr>
                            <td>Coordenada Y</td>
                            <td><input type="text" class="form-control"  readonly name="cy" autocomplete="off"/></td>
                        </tr>
                        <!-- Aqui estar� se colocaran los mensajes para el usuario -->
                        <tr>
                            <td></td>
                            <td><span id="loader_grabar" class=""></span></td>
                        </tr>
                        <tr>
                            <td><button type="button" id="btn_actualizar" class="btn btn-success btn-sm">Actualizar</button></td>
                            <td><button type="button" id="btn_borrar" class="btn btn-danger btn-sm">Borrar</button></td>
                        </tr>
                    </table>
                </form>
              </div>
            </div>
          </div>
          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
              <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                  Buscar
                </a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
              <div class="panel-body">
                <form id="formulario_buscar">
                    <table>
                      <TR>
                        <td>
                          <input type="text" id="address" name="address" class="form-control" autocomplete="off" />
                        </td>
                        <td>
                          <button type="button" id="search" name="search"  class="btn btn-success btn-sm" >Buscar</button>
                        </td>
                      </TR>                     

                    </table>
                  </form>
              </div>
            </div>
          </div>
        </div>
    </div>
    
    <div>
	<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>    
    </div>
    <script>
	//$('#myModal').modal(options);
    </script>
</body>
</html>