function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{ 
			// Creacion del objet AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") { xmlhttp=new XMLHttpRequest(); } 

	return xmlhttp; 
}

function eliminaEspacios(cadena)
{
	// Funcion equivalente a trim en PHP
	var x=0, y=cadena.length-1;
	while(cadena.charAt(x)==" ") x++;	
	while(cadena.charAt(y)==" ") y--;	
	return cadena.substr(x, y-x+1);
}

function validaIngreso(valor)
{
	/* Funcion encargada de validar lo ingresado por el usuario. Se devuelve TRUE en caso de ser 
	valido, FALSE en caso contrario */
	var reg=/(^[a-zA-Z0-9.@ ]{1,40}$)/;
	if(reg.test(valor)) return true;
	else return false;
}

function abrir(palabra) {
	document.getElementById('buscar').value = palabra;
	nuevoEvento('buscar');
}
function nuevoEvento(evento)
{
	// Obtengo el div donde se mostraran las advertencias y errores
	var divMensaje=document.getElementById("resultados");

	/* Dependiendo de cual sea el evento que ejecuto esta funcion (ingreso o verificacion) se setean
	distintas variables */	
	if(evento=="buscar")
	{
		var input=document.getElementById("buscar");
		// Boton presionado
		var boton=document.getElementById("botonBuscar");
		// Valor ingresado por el usuario
		var valor=input.value;
		// Texto a colocar en el input mientras se esta cargando la respuesta del servidor
		var textoAccion="Buscando...";
	}
	// Elimino espacios por delante y detras de lo ingresado por el usuario
	valor=eliminaEspacios(valor);
	// Si el ingreso es invalido coloco un mensaje de error en la capa correspondiente.
	if(!validaIngreso(valor)) 
	{
		divMensaje.innerHTML="El texto ingresado contiene caracteres o longitud inv&aacute;lida";
	}
	else
	{
		// Deshabilito inputs y botones para evitar dobles ingresos.
		boton.disabled=true; input.disabled=true;
		//input.value=textoAccion;
		
		// Creo la conexion con el servidor y le envio la variable evento (que le indica si debe ingresar o verificar) y el dato a utilizar.
		var ajax=nuevoAjax();
		ajax.open("POST", "system/comprueba.php", true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		
		// Buscamos el FILTRO DE ORDENAMIENTO checkeado.
		var rad_val;
		if (document.getElementById("random").checked) rad_val = document.getElementById("random").value ;
		else rad_val = document.getElementById("alfabetico").value ;
		
		// Buscamos el FILTRO DE COMPLEJIDAD DE PALABRAS checkeado.
		var complejidad;
		if (document.getElementById("simple").checked) complejidad = document.getElementById("simple").value ;
		else complejidad = document.getElementById("experto").value ;
		

		
		// Enviamos la información.
		ajax.send(evento + "=" + valor + "&filtro=" + rad_val + "&complex=" + complejidad); // Modificado.
		input.value=textoAccion;
		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4)
			{
				// Habilito nuevamente botones e inputs
				input.value=valor;
				boton.disabled=false; input.disabled=false;
				// Muestro el mensaje enviado desde el servidor
				divMensaje.innerHTML=ajax.responseText;
			}
		}
	}
}


