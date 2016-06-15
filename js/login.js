// JavaScript Document
$(document).ready(function(e) {
   // despliega('form.php','conten');
});

function verifica(id_F, p){

	var dato = JSON.stringify( $('#'+id_F).serializeObject() );
	
	$.ajax({
		url: "classes/"+p,
		type: 'post',
		dataType: 'json',
		async:true,
		data:{res:dato},
		beforeSend: function(data){
		  $('#error').css('display','block');
		  $('#error').html('<div id="loader"><p>Validando...</p></div>');
		},	
		success: function(data){											
		  if(data != 0){
			  
			  $('#error').html('<p>Redireccionando...</p>');
			  
			  $(location).attr('href','admin.php');
			  
			  /*if( data.cargo == 'adm' ){
			  	$(location).attr('href','admin.php');
			  }
			  if( data.cargo == 'alm' ){
				$(location).attr('href','almacen.php');
			  }
			  if( data.cargo == 'con' ){
				$(location).attr('href','contador.php');
			  }
			  if( data.cargo == 'pre' ){
				$(location).attr('href','admin.php');
			  }*/
			  
		  }else{			 
			  $('#error').html('<p>Usuario o contrase&ntilde;a no validas</p>');
			  clearForm('login');
		  }
		},
		error: function(data){			
			//alert('Error al guardar el formulario');
		}
	});
}

function clearForm(idForm){
		
	$('#'+idForm).find('input[type="password"], input[type="text"]').each(function(index, element) {
		var id = $(this).attr('id');			
			var $val =  $(this).val('');	
    });
	$('#username').focus();			
}