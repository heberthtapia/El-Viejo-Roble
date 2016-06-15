// JavaScript Document
function despliega(p, div, id){	
	$.ajax({
		url: p,
		type: 'post',				
		cache: false,
		data: 'id='+id,
		beforeSend: function(data){
			$("#"+div).html('<div id="load" align="center"><p>Cargando contenido. Por favor, espere ...</p></div>');
			},		
		success: function(data){
			$("#"+div).fadeOut(1000,function(){
				$("#"+div).html(data).fadeIn(2000);	
			});
			//$("#"+div).html(data);			
		}
	});
}

/* ORDENA FORMULARIO */

function ordena(i){
	
	$('#tableList tbody').find('tr').each(function(index, element) {						  
		$(this).find('td').eq(0).text(i);						  
		i++;			  
	});
	
}

/* WEB CAM */

/* RECARGA IMAGEN */

function recargaImg(img, mod){
	$('#foto').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/'+mod+'/uploads/photos/'+img+'&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
	}

function recargaImgC(img, mod){
	$('#fotoC').html('<img class="thumb" src="thumb/phpThumb.php?src=../modulo/'+mod+'/uploads/photos/'+img+'&amp;w=90&amp;h=75&amp;far=1&amp;bg=FFFFFF&amp;hash=361c2f150d825e79283a1dcc44502a76" alt="">');
	}

function closeWebcam(){
	$('#camera').css('display','none');
	$('#save').removeAttr('disabled', 'disabled');
}

function openWebcam(){
	$('#camera').css('display','block');
	$('#save').attr('disabled', 'disabled');
}

/* COLORBOX */

function open_win(url, mensaje, w, h, id){
	$.colorbox({
		open:true,
		href: url+"?id="+id,
		width: w+"px",
		height: h+"px",
		title: mensaje,
		scrolling: false,
		onComplete:function(){ $("form:not(.filter) :input:visible:enabled:first").focus(); }	
	});
}

/* GUARDA FORMULARIO */

function saveForm(idForm, p){

	var dato = JSON.stringify( $('#'+idForm).serializeObject() );
	
	$.ajax({
		url: "modulo/"+p,
		type: 'post',
		dataType: 'json',
		async:true,
		data:{res:dato},
		success: function(data){
			//alert(data.checksEmail);									
			parent.$.colorbox.close();			
			//ordena(2);
			//alert(data.tabla);			
			if(data.tabla === 'empleado'){		
				//fnClickAddRow(data,true);
				despliega('modulo/empleado/listEmpleado.php','contenido');
			}
			if(data.tabla === 'inventario'){
				//fnClickAddRowU(data,true);
				despliega('modulo/producto/listProducto.php','contenido');
			}
			if(data.tabla === 'pedido'){
				//fnClickAddRowInvG(data,true);
				/* CAMBIIO STASTUS CONTADOR */
				
				  if(data.OkCont === 0){					 
					$('tr#tb'+data.pedido+' td.Pendiente').removeClass('Pendiente').addClass('Aprobado');				
					$('tr#tb'+data.pedido+' td.Aprobado a').text('APROBADO');
				  }else{
					if(data.OkCont === 1){
						$('tr#tb'+data.pedido+' td.Aprobado').removeClass('Aprobado').addClass('Pendiente');				
						$('tr#tb'+data.pedido+' td.Pendiente a').text('PENDIENTE');
					}else{
						/* CAMBIIO STASTUS ALMACEN */
					  if(data.OkAlm === 0){
						$('tr#tb'+data.pedido+' td.NoEntregado').removeClass('NoEntregado').addClass('Entregado');				
						$('tr#tb'+data.pedido+' td.Entregado a').text('ENTREGADO');
					  }else{
						if(data.OkAlm === 1){
							$('tr#tb'+data.pedido+' td.Entregado').removeClass('Entregado').addClass('NoEntregado');				
							$('tr#tb'+data.pedido+' td.NoEntregado a').text('NO ENTREGADO');
						}else{
							despliega('modulo/pedido/listPedido.php','contenido');	
						}
					  }
					}
				  }										
			}
			if(data.tabla === 'cliente'){
				despliega('modulo/cliente/listCliente.php','contenido');				
			}			
		},
		error: function(data){				
			alert('Error al guardar el formulario');
			}
	});
}

/* ADICCIONA FILA EMPLEADO */

function fnClickAddRow(data){

  var ac = '<div class="accEmp">';
			   
  ac += '  <div class="accion">';
  ac += '  <a href="javascript:void(0);" onclick="javascript:openEditForm(&#39;'+data.tabla+'/'+data.tabla+'-edit.php&#39;,&#39;'+data.ci+'&#39;, &#39;&#39;, &#39;'+710+'&#39;, &#39;'+540+'&#39;);">';
  ac += '   <img src="images/icono/edit.png" width="32" height="32"  alt="" title="Editar" />';
  ac += '  </a>';
  ac += '  </div><!--End accion-->';
		
  ac += '  <div class="accion">';
  ac += '  <a href="javascript:void(0);" onclick="javascript:deleteRow(&#39;delEmp.php&#39;,&#39;'+data.ci+'&#39;,&#39;'+data.tabla+'&#39;);">';
  ac += '   <img src="images/icono/recycle.png" width="32" height="32"  alt="" title="Eliminar" />';
  ac += '  </a>';
  ac += '  </div><!--End accion-->';
		
  ac += '  <div class="accion check">';
  ac += '	<form id="myform'+data.ci+'" name="myform" class="status">';		
  ac += '     <label><input name="checks" type="checkbox" checked="checked" onclick="status('+data.ci+',&#39;'+data.tabla+'&#39;);" style="position: absolute; margin-left: -9999px;" value=" " />';
  ac += '  <span class="selected"> </span></label>';
  ac += '  </form>';
  ac += ' </div><!--End accion-->';
  ac += '<div class="cleafix"></div>';
  ac += '</div><!--End accEmp-->';
		
/* imagen a desplegar */		

  if(data.img != ''){
	  var img = "<img class='thumb' src='thumb/phpThumb.php?src=../modulo/"+data.tabla+"/uploads/photos/"+data.img+"&amp;w=100&h=50&far=1&bg=FFFFFF&hash=361c2f150d825e79283a1dcc44502a76' alt=''>";
  }else{
	  var img = "<img class='thumb' src='thumb/phpThumb.php?src=../modulo/"+data.tabla+"/uploads/photos/sin_imagen.jpg&amp;w=100&h=50&far=1&bg=FFFFFF&hash=361c2f150d825e79283a1dcc44502a76' alt=''>";
	  }
/***********************/					
	$('#tableList').dataTable().fnAddData( [
		1,			
		data.date,
		img,
		data.name,
		data.paterno,
		data.materno,
		devCargo(data.cargo),
		ac] 
	);	
	$('#tableList').find('tbody').find('tr:first').attr('id','tb'+data.ci);
	$('#tableList').find('tbody').find('tr:first').children('td').addClass('center');
	$('#tableList').find('tbody').find('tr:first').children('td').slice(0,6).addClass('last'); 
}

/* LIMPIAR FORMULARIO */

function clearForm(idForm){
	$('#'+idForm).find('input').each(function(index, element) {
		jQuery('#'+idForm).validationEngine('hide')			
    });	
}

/* GENERA PASSWORD */

function generaPass(id){
	$.ajax({
		url: 'classes/generaPass.php',		
		type: 'post',		
		cache: false,		
		success: function(data){			
			$("#"+id).attr('value',data);			
		}
	});
}

/* ID IMAGEN */

function idImg(){	
	$.ajax({
		url: 'classes/img.php',
		type: 'post',				
		cache: false,			
		success: function(data){
			recargaImg(data);	
			recargaImgC(data);	
		}
	});
}

/* FUNCION DEVUELVE CARGO */

function devCargo(c){
	var cargo = null;
	switch(c){
		case 'adm':
			cargo = 'Administrador';
			break;      
		case 'alm': 
			cargo = 'Almacen';
			break;      
		case 'con': 
			cargo = 'Contador';
			break;
		case 'pre': 
			cargo = 'Preventista';
			break;         	 
		default: 
			cargo = 'Error';
		}
		return(cargo);
	}
	
/* ADICIONA PEDIDO */

function adicFila(idForm, p){

	var dato = JSON.stringify( $('#'+idForm).serializeObject() );
	//alert(dato);
	$.ajax({
		url: "classes/"+p,
		type: 'post',
		dataType: 'json',
		async:true,
		data:{res:dato},
		success: function(data){
			sw = 0;
			
			$('#tabla tbody').find('tr').each(function(index, element){				
				cod = $(this).attr('id');	
											
				if( cod === data.producto ){									
					cantidad = $('tr#'+data.producto).find('td').eq(1).find('input').val();					
					//alert('******'+cantidad);
					cantidad = parseInt(cantidad) + parseInt(data.cant);	
					//alert('-------'+cantidad);
					if( parseFloat(cantidad) <= parseFloat(data.cantI) ){
											
					  precio = $('tr#'+data.producto).find('td').eq(2).find('input').val();					  			
					  precio = parseFloat(precio) * parseFloat(cantidad);					
					  $('tr#'+data.producto).find('td').eq(1).find('input').val(cantidad);
					  //$('tr#'+data.producto).find('td').eq(3).find('input').val(cant);								  
					  $('tr#'+data.producto).find('td').eq(5).find('input').val(precio.toFixed(2));				
					  //$('tr#'+data.producto).find('td').eq(4).find('input').val(precio);					  
					  
					}else{
						alert('Producto sin Stock.....');
					}					
					sw = 1;
				}							  
			});	
					
			if( sw === 0 && data.producto !== undefined ){		
				agregarFila(data);		
			}
			$('#producto').val('');			
			$('#cant').val('');			
			subPrecio = 0;			
			$('#tabla tbody').find('tr').each(function(index, element){				
				subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());				
			});	
			$('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));			
			
			des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val(); 
			if(des == '') des = 0;	
			
			bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val(); 
			if(bon == '') bon = 0;	
				
			total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);
			$('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));
									
		},
		error: function(data){			
			alert('Error al guardar el formulario');
			}
	});
	$('#efectivo').val('');
	$('#cambio').val('');
	$('#codigo').focus();
}

/* EDITA PEDIDO */

function adicFilaEdit(idForm, p){
	"use strict";
	var dato = JSON.stringify( $('#'+idForm).serializeObject() );
	//alert(dato);
	$.ajax({
		url: "classes/"+p,
		type: 'post',
		dataType: 'json',
		async:true,
		data:{res:dato},
		success: function(data){
			sw = 0;
			$('#tabla tbody').find('tr').each(function(index, element){				
				cod = $(this).attr('id');
				if( cod === data.producto ){									
					cant = $('tr#'+data.producto).find('td').eq(1).find('input#cantidad').val();					
					cant = parseInt(cant) + parseInt(data.cant);									
					if( parseFloat(cant) <= (parseFloat(data.cantI) + parseInt(data.cantInv)) ){					
					  precio = $('tr#'+data.producto).find('td').eq(2).find('input').val();					  			
					  precio = parseFloat(precio) * parseFloat(cant);					
					  $('tr#'+data.producto).find('td').eq(1).find('input#cantidad').val(cant);
					  //$('tr#'+data.producto).find('td').eq(3).find('input').val(cant);								  
					  $('tr#'+data.producto).find('td').eq(5).find('input').val(precio.toFixed(2));				
					  //$('tr#'+data.producto).find('td').eq(4).find('input').val(precio);					  
					}else{
						alert('Producto sin Stock.....');
					}					
					sw = 1;
				}							  
			});	
					
			if( sw == 0 && data.producto != undefined ){		
				agregarFila(data);		
			}
			$('#producto').val('');			
			$('#cant').val('');			
			subPrecio = 0;			
			$('#tabla tbody').find('tr').each(function(index, element){				
				subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());				
			});	
			$('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));			
			
			des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val(); 
			if(des == '') des = 0;	
			
			bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val(); 
			if(bon == '') bon = 0;	
				
			total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);;			
			$('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));
									
		},
		error: function(data){			
			alert('Error al guardar el formulario');
			}
	});
	$('#efectivo').val('');
	$('#cambio').val('');
	$('#codigo').focus();
}

function agregarFila(data){

  cant = $('input#cant').val();
  //cant = parseInt(cant) + parseInt(data.cant);
  if( parseFloat(cant) <= parseFloat(data.cantI) ){	
  // Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
  //if( data.cantidad > 0 ){  
	  //$('#tabla thead').removeAttr('hidden');
	  $('#tabla tfoot').removeAttr('hidden');
	  //$('#submitVenta').removeAttr('hidden');
	  //alert(data.cant);
	  precio = parseFloat(data.cant) * parseFloat(data.precio);
	  
	  var strNueva_Fila = '<tr id="'+data.producto+'">'+
	  '<td class="det">'+data.producto+' '+data.detalle+''+
	  '<input type="hidden" id="item" name="item" value="'+data.producto+'" ></td>'+
	  
	  '<td><input type="text" disabled="disabled" id="cantidad" name="cantidad" value="'+data.cant+'" >'+
	  '<input type="hidden" id="cantidad" name="cantidad" value="'+data.cant+'" ></td>'+
	   
	  '<td><input type="text" disabled="disabled" id="precio" name="precio" value="'+data.precio+'" >'+
	  '<input type="hidden" id="precio" name="precio" value="'+data.precio+'" ></td>'+
	   
	  '<td></td>'+  		
	  '<td></td>'+
	  '<td><input type="text" disabled="disabled" id="subTotal" name="subTotal" value="'+precio.toFixed(2)+'" ></td>'+
	  '<td align="right"><a onclick="eliminarFila(&#39;'+data.producto+'&#39;)"><img class="delet" src="images/delete.png" width="16" height="16" /></a></td>'+
	  '</tr>';	
	
	  $("#tabla tbody").append(strNueva_Fila);
	  
	  $('#tabla tbody').find('tr').each(function(index, element){				
		  if( index % 2 == 0 ){
			  $(this).addClass('odd');
		  }else{
			  $(this).addClass('even');
		  }
	  });  
	  
	  $('#tabla tbody').find('tr').each(function(index, element){				
		var p = parseFloat($(this).find('td').eq(6).find('input').val());			  
		$(this).find('td').eq(6).find('input').val(p.toFixed(2));
	  });
  
  }else{
	  
	  alert('Producto sin Stock');
	  
	}
}

function eliminarFila(idTr){
	
	if( $('#tabla tbody').find('tr').length == 1 ){
		if( !confirm('¿Esta seguro que desea eliminar el pedido?')){
			return;
		}		
		despliega('modulo/pedido/newPedido.php','contenido');		
	}
	
	$('#'+idTr).remove();	
	
	subPrecio = 0;			
	$('#tabla tbody').find('tr').each(function(index, element){				
		subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());				
	});	
	$('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));			
	
	des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val(); 
	if(des == '') des = 0;	
	
	bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val(); 
	if(bon == '') bon = 0;	
		
	total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);;			
	$('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));
	
	if( total < 0  )				
		$('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').css('color','#F7070B');
	else
		$('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').css('color','#000000');
	
	$('#tabla tbody').find('tr').each(function(index, element){				
		if( index/2 == 0 ){
			$(this).removeClass('even');
			$(this).addClass('odd');
		}else{
			$(this).removeClass('odd');
			$(this).addClass('even');
		}
	});
	$('#efectivo').val('');
	$('#cambio').val('');
	$('#codigo').focus();	
}

/* RECARGA TOTALES DEL EDIT */

function recargaFila(){
	subPrecio = 0;
	$('#tabla tbody').find('tr').each(function(index, element){				
		subPrecio = parseFloat(subPrecio) + parseFloat($(this).find('td').eq(5).find('input').val());				
	});	
	$('#tabla tfoot').find('th').eq(1).find('input').val(subPrecio.toFixed(2));			
	
	des = $('#tabla tfoot').find('tr').eq(1).find('th').eq(1).find('input').val(); 
	if(des == '') des = 0;	
	
	bon = $('#tabla tfoot').find('tr').eq(2).find('th').eq(1).find('input').val(); 
	if(bon == '') bon = 0;	
		
	total = parseFloat(subPrecio)-parseFloat(des)-parseFloat(bon);;			
	$('#tabla tfoot').find('tr').eq(3).find('th').eq(1).find('input').val(total.toFixed(2));
			
	}

/* GUARDA PEDIDO */

function savePedido(idForm, p){
	
	if( !confirm('CONFIRMAR PEDIDO!!!')){
		return;
	}
	var dato = JSON.stringify( $('#'+idForm).serializeObject() );	
	$.ajax({
		url: "modulo/pedido/"+p,
		type: 'post',
		dataType: 'json',
		async:true,
		data:{res:dato},
		success: function(data){
			despliega('modulo/pedido/listPedido.php','contenido');		
			window.open('modulo/pedido/pdfPedido.php?res='+dato, '_blank');						
		},
		error: function(data){				
			alert('Error al guardar el formulario');
			}
	});
}

/* GUARDA NUEVA ORDEN DE PRODUCCION */

function saveOrdenP(idForm, p){
	
	if( !confirm('CONFIRMAR ORDEN DE PRODUCCION!!!') ){
		return;
	}
			
	var dato = JSON.stringify( $('#'+idForm).serializeObject() );	
	$.ajax({
		url: "modulo/produccion/"+p,
		type: 'post',
		dataType: 'json',
		async:true,
		data:{res:dato},
		success: function(data){
			despliega('modulo/produccion/listProduccion.php','contenido');	
			window.open('modulo/produccion/pdfOrdenP.php?res='+dato, '_blank');						
		},
		error: function(data){				
			alert('Error al guardar el formulario');
			}
	});
}

function popUp(id, nit, pat, des, efec, cam){
	
	window.open("webPages/recibo.php?id="+id+"&nit="+nit+"&pat="+pat+"&des="+des+"&efec="+efec+"&cam="+cam+"","popimpr","width=210,height=650,scrollbars=NO"); //damos un titulo	

}

/* session */

function outSession(user){	
	$(location).attr('href','classes/outSession.php?user='+user);	
}

function detalle(id){	
	window.open('modulo/pedido/pdfPedDet.php?res='+id, '_blank');						
}

/* ELIMINAR REGISTRO DEL INVENTARIO*/

function deleteRowBD(p, idTr, tipo, table){
  var resp=0;
   rr = $.ajax({
		url: 'modulo/'+tipo+'/'+p,
		type: 'post',
		async:false,
		data: 'id='+idTr+'&tipo='+tipo+'&table='+table,
		success: function(data){ 
			if(data!=1)
				alert('No se puede eliminar el ITEM.');
			else
				resp = data
		},
		error: function(data){			
			alert('Error al eliminar el ITEM.');			
			}
	  });	
	  return resp;
}

/* CAMBIAR STATUS DE EL EMPLEADO */

function status(id, table){

	$.ajax({
		url: 'classes/status.php',
		type: 'post',
		async:true,
		data: 'id='+id+'&table='+table,
		success: function(data){
		  
			if($('#myform'+id).find('label').find('span').hasClass('selected')){
				
				$('#myform'+id).find('label').find('span').removeClass('selected');	
				
			}else{
				
				$('#myform'+id).find('span').addClass('selected');
				
			}		
		}
	  });
}