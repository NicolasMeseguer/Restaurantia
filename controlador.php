<?php
require 'modelo.php';
require 'vista.php';
require 'recaptcha.php';
session_start();

//Iniciar las variables del recaptcha
$secret = "GoogleRecaptcha Secret KEY";
$response = null;
$reCaptcha = new ReCaptcha($secret);

//IMPRIMIR EL PIE SIEMPRE
//displayFoot();

if(isset($_GET['codigov']) && $_GET['codigov']) {
	$c = getCodigos();
	if(in_array($_GET['codigov'],$c)) {
		$codigoverif = $_GET['codigov'];
		verifUsuario($codigoverif);
		displayOkCodigo();
		header( "refresh:5;url=controlador.php" );
		displayFoot();
		exit;
	}
	else {
		displayErrorCodigo();
		header( "refresh:5;url=controlador.php" );
		displayFoot();
		exit;
	}
}

if(isset($_GET['codigoff']) && $_GET['codigoff']) {
	$c = getCodigosRR();
	if(in_array($_GET['codigoff'],$c)) {
		$codigoverif = $_GET['codigoff'];
		//Envia pdf
		//Enviar correo al tio
		//Sacarle, nombre del local, direccion, menu, fecha y hora
		$local = getRest($codigoverif);
		$horas = getHoras($codigoverif);
		$correopersona = getUserCorrEsp($codigoverif);
		//hacerpdf con los datos y enviarselo al tio
		generapdf($local, $correopersona, $horas);
		//Notificar al tio
		$persona = getUserIdEsp($codigoverif);
		$correo = getCorreoRest($codigoverif);
		enviarmailv4($correo, $persona);
		
		verifReserva($codigoverif);
		displayOkReserva();
		exit;
	}
	else {
		displayErrorCodigo();
		header( "refresh:5;url=controlador.php" );
		displayFoot();
		exit;
	}
}

if(isset($_GET['codigovr']) && $_GET['codigovr']) {
	$c = getCodigosR();
	if(in_array($_GET['codigovr'],$c)) {
		$codigoverif = $_GET['codigovr'];
		verifRestaurante($codigoverif);
		displayOkCodigoR();
		header( "refresh:5;url=controlador.php" );
		displayFoot();
		exit;
	}
	else {
		displayErrorCodigo();
		header( "refresh:5;url=controlador.php" );
		displayFoot();
		exit;
	}
}

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true) {
	if(isset($_GET['cerrar']) && $_GET['cerrar']) {
		if ($_GET['cerrar']=='true') {
			unset($_SESSION['sesion']);
			header('Location: controlador.php');
		}
		else {
			header('Location: controlador.php');
		}
	}

	if(isset($_SESSION['sesion']) && $_SESSION['sesion']) {
		if ($_SESSION['sesion']=='registrar') {
			//el tio va a registrar un restaurante
			if(isset($_POST['enviar']) && $_POST['enviar']) {
				
				$entrante = $_POST['entrante'];
				$plato1 = $_POST['plato1'];
				$plato2 = $_POST['plato2'];
				$bebida = $_POST['bebida'];
				$postre = $_POST['postre'];
				if(isset($_POST['adicional']))
					$inv = $_POST['adicional'];
				else
					$inv = 'false';
				
				$nombre = $_POST['nombre'];
				$direccion = $_POST['direccion'];
				$correo = $_POST['email'];
				
				$archivo = $_FILES['foto']['tmp_name'];
				$tamanio = $_FILES['foto']['size'];
				$tipo = $_FILES['foto']['type'];
				$nombref = $_FILES['foto']['name'];
				
				if(!existecorreoR($correo)) {
					if(strlen($entrante)>=3 && strlen($plato1)>=3 && strlen($plato2)>=3 && strlen($bebida)>=3 && strlen($postre)>=3 && strlen($nombre)>=3 && strlen($direccion)>=3 && strlen($correo)>=3 && $archivo != 'none') {
						$menu[] = $entrante;
						$menu[] = $plato1;
						$menu[] = $plato2;
						$menu[] = $bebida;
						$menu[] = $postre;
						$menu[] = $inv;
						
						$codigoverif = rand().rand().rand();

						insertarDatos($menu, $codigoverif, $nombre, $direccion, $correo, $archivo, $tamanio, $tipo, $nombref);
						
						enviarmailv2($codigoverif, $correo, $nombre);
						
						displayRRCorrecto();
						unset($_SESSION['sesion']);
						header( "refresh:5;url=controlador.php" );
					}
				}
				else
					displayFormRegistroRestaurante("El correo ya se encuentra en uso.");
			}
			else {
				displayFormRegistroRestaurante("");
			}
		}
		elseif($_SESSION['sesion']=='encontrar') {
			$restaurantes = getAllRestaurantes();
			if(isset($_GET['motivo']) && $_GET['motivo']=='reserva') {
				if(isset($_POST['enviar']) && $_POST['enviar']) {
					$fecha = $_POST['fecha'];
					$hora = $_POST['hora'];
					
					$idr = $_GET['id'];
					
					$iduser = $_SESSION['iduser'];
					
					$codigoverif = rand().rand().rand();
					
					agregarReserva($iduser, $codigoverif, $fecha, $hora, $idr);
					
					$restaurante = getRestaurante($idr);
					
					$correo = getCorreo($iduser);
					
					enviarmailv3($codigoverif, $correo, $fecha, $hora, $restaurante[2]);
					
					displayConfirmacionR();
					
					unset($_SESSION['sesion']);
					header( "refresh:8;url=controlador.php" );
				}
				else {
					if(checkCode($_GET['id'])) {
						$restaurante = getRestaurante($_GET['id']);
						$menu = getMenu($restaurante[1]);
						displayFormSolicitud($restaurante, $menu, $_GET['id']);
					}
				}
			}
			elseif(isset($_GET['restaurante'])) {
				$codr = $_GET['restaurante'];
				if(checkCode($codr)) {
					$restaurante = getRestaurante($codr);
					$menu = getMenu($restaurante[1]);
					displayInfo($restaurante, $menu);
				}
				else
					displayRestaurantes($restaurantes);
			}
				else
					displayRestaurantes($restaurantes);
		}
	}
	elseif(isset($_GET['opcion']) && $_GET['opcion']) {
		if($_GET['opcion']=='r') {
			$_SESSION['sesion']='registrar';
			header('Location: controlador.php');
		}
		elseif($_GET['opcion']=='e') {
			$_SESSION['sesion']='encontrar';
			header('Location: controlador.php');
		}
		else {
			displayInicio();
		}
	}
	else {
		displayInicio();
	}
	displayCerrar();
}
else {
	if(isset($_GET['opcion']) && $_GET['opcion']) {
		if($_GET['opcion']=='login') {
			
			if(isset($_POST['enviar']) && $_POST['enviar']) {
				if(count($_POST)==3) {
					$user = $_POST['user'];
					$password = $_POST['password'];
					$r = comprobarUsuario($user,$password);
					if($r[0]==true) {
						$_SESSION['iduser']=$r[1]; //id del usuario conectado
						displayOkLogin();
						$_SESSION['loggedin']=true;
						header( "refresh:5;url=controlador.php" );					
					}
					else
						displayLogin("El usuario/contraseña no son correctos");
				}
				else
					displayLogin("");
			}
			else {
				displayLogin("");
			}
		}
		elseif($_GET['opcion']=='register') {
			
			if(isset($_POST['enviar']) && $_POST['enviar']) {
				if(count($_POST)>=8) {
					//tratar parámetros y registrarlos aparte comprobar si existe el nombre de user o el correo puesto
					$user = $_POST['user'];
					$password = $_POST['password'];
					$password2 = $_POST['password2'];
					$nombre = $_POST['nombre'];
					$apellido = $_POST['apellido'];
					$email = $_POST['correo'];
					$direccion = $_POST['direccion'];
					
					if(strlen($user)>=3 && strlen($password2)>=3 && strlen($password)>=3 && strlen($nombre)>=3 && strlen($apellido)>=3 && strlen($email)>=3 && strlen($direccion)>=3) {
						if($password == $password2) {
							if(existeCorreo($email) || existeUser($user))
								displayRegister("El usuario o correo ya existen.");
							else {
								if($_POST["g-recaptcha-response"]) {
									$response = $reCaptcha->verifyResponse(
										$_SERVER["REMOTE_ADDR"],
										$_POST["g-recaptcha-response"]
									);
								}
								else {
									displayRegister("Por favor, verifica que no eres un robot.");
								}
								
								if ($response != null && $response->success) {
									$codigoverif = rand().rand().rand();
									insertarUsuario($codigoverif, $user, $password, $nombre, $apellido, $email, $direccion);
									enviarmailv($codigoverif, $email, $nombre);
									displayRCorrecto();
									header('refresh:5;url=controlador.php');
								}
							}
						}
						else
							displayRegister("Las contraseñas no coinciden.");
					}
					else
						displayRegister("Longitud mínima de los campos, 3 caracteres.");
				}
				else
					displayRegister("");
			}
			else
				displayRegister("");
		}
		else {
			header('Location: controlador.php');
		}
	}
	else {
		displayOpciones();
	}
}

displayFoot(); //Imprime el pie de la página
?>