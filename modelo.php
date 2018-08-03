<?php
require 'db.php';
require 'PHPMailer/PHPMailerAutoload.php';
require 'constantes.php';
require 'pdf.php';

$GLOBALS['DB']->exec('SET CHARACTER SET utf8');

function enviarmailesp($correo) {
	$mail = new PHPMailer();

	/*Configuracion PHPMailer*/
	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'YourEmail';
	$mail->Password = EMAIL_PASSWORD;

	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->setFrom('YourEmail', 'Oficina Central Restaurantia');
	$mail->addAddress($correo, 'Cliente');
	$mail->Subject  = 'Información de la reserva';
	$mail->Body     = 'Aqui tiene toda la información relacionada a su reserva, gracias y hasta la próxima.';
	$mail->addAttachment('Reserva.pdf', 'Reserva.pdf');
	$mail->send();
}

function generapdf($local, $correopersona, $horas) {
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetXY(65, 75);
    $pdf->SetFont('Arial','B',30);
    $pdf->Write(0, 'Datos de la reserva');
    $pdf->SetFont('Arial','B',10);
    $pdf->SetXY(30, 120);
    $pdf->SetFillColor(23, 92, 204);//Fondo azul de celda
    $pdf->SetTextColor(240, 255, 240); //Letra color blanco
    $pdf->SetDrawColor(22, 78, 170);
    $pdf->Cell(39,7,'NOMBRE DEL RESTAURANTE',1, 0 , 'C', true );
    $pdf->Cell(39,7,'DIRECCION',1, 0 , 'C', true );
    $pdf->Cell(39,7,'ENTRANTE',1, 0 , 'C', true );
    $pdf->Cell(39,7,'PLATO 1',1, 0 , 'C', true );
    $pdf->Cell(39,7,'PLATO 2',1, 0 , 'C', true );
    $pdf->Cell(39,7,'BEBIDA',1, 0 , 'C', true );
    $pdf->Cell(39,7,'POSTRE',1, 0 , 'C', true );
    $pdf->Cell(39,7,'INVITA',1, 0 , 'C', true );
    $pdf->Cell(39,7,'FECHA',1, 0 , 'C', true );
    $pdf->Cell(39,7,'HORA',1, 0 , 'C', true );
    $pdf->Ln();//Salto de línea para generar otra fila
		
    $pdf->SetXY(30,120+7);
    $pdf->SetFont('Arial','',10);
    $pdf->SetFillColor(229, 229, 229); //Gris tenue de cada fila
    $pdf->SetTextColor(3, 3, 3); //Color del texto: Negro			
    $pdf->Cell(39,7,$local[0],1, 0 , 'C');
    $pdf->Cell(39,7,$local[1],1, 0 , 'C');
    $pdf->Cell(39,7,$local[2],1, 0 , 'C' );
    $pdf->Cell(39,7,$local[3],1, 0 , 'C' );
    $pdf->Cell(39,7,$local[4],1, 0 , 'C' );
    $pdf->Cell(39,7,$local[5],1, 0 , 'C' );
    $pdf->Cell(39,7,$local[6],1, 0 , 'C' );
    $pdf->Cell(39,7,$local[7],1, 0 , 'C' );
    $pdf->Cell(39,7,$horas[0],1, 0 , 'C' );
    $pdf->Cell(39,7,$horas[1],1, 0 , 'C' );
    $pdf->Ln();
	  
	ob_end_clean();
    $pdf->Output('Reserva.pdf','F');
    enviarmailesp($correopersona);
}

function getHoras($codigo) {
	$sql = 'SELECT * FROM validar_reservas WHERE cod_verif = ?;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigo);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$datos[] = $c[0][4]; //fecha
	$datos[] = $c[0][5]; //hora
	
	unset($codigo);
	return $datos;
}

function existeCorreo($correo) {
	$sql = 'SELECT * FROM res_usuarios;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$tablar = $consulta->fetchAll();
	
	foreach($tablar as $t) {
		$correosr[] = $t['correo']; //Array de correos de los res_usuarios de la tabla real
	}
	
	$sql = 'SELECT * FROM res_validar_usuarios;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$tablaf = $consulta->fetchAll();
	
	foreach($tablaf as $t) {
		$correosf[] = $t['correo']; //Array de nombres de los res_usuarios de la tabla real
	}
	
	if(in_array($correo, $correosr) || in_array($correo, $correosf)){
		return true; //true porque está
	}
	else
		return false;
}

function existecorreoR($correo) {
	$sql = 'SELECT * FROM res_restaurante;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$tablar = $consulta->fetchAll();
	
	foreach($tablar as $t) {
		$correosr[] = $t['correo']; //Array de correos de los res_usuarios de la tabla real
	}
	
	$sql = 'SELECT * FROM res_validar_restaurante;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$tablaf = $consulta->fetchAll();
	
	foreach($tablaf as $t) {
		$correosf[] = $t['correo']; //Array de nombres de los res_usuarios de la tabla real
	}
	
	if(in_array($correo, $correosr) || in_array($correo, $correosf)){
		return true; //true porque está
	}
	else
		return false;
}

function existeUser($user) {
	$sql = 'SELECT * FROM res_usuarios;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$tablar = $consulta->fetchAll();
	
	foreach($tablar as $t) {
		$usersr[] = $t['user']; //Array de nombres de los res_usuarios de la tabla real
	}
	
	$sql = 'SELECT * FROM res_validar_usuarios;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$tablaf = $consulta->fetchAll();
	
	foreach($tablaf as $t) {
		$usersf[] = $t['user']; //Array de nombres de los res_usuarios de la tabla real
	}
	
	if(in_array($user, $usersr) || in_array($user, $usersf)){
		return true; //true porque está
	}
	else
		return false;
}

function comprobarUsuario($user, $password) {
	$res = false;
	$id = false;
	
	$sql = "SELECT * FROM res_usuarios WHERE user = ? AND password = ?;";
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $user);
	$consulta->bindParam( 2, $password);
	$consulta->execute();
	
	$filas = $consulta->rowCount();
	
	if($filas==1){
		$res = true;
	}
	
	if($res==true) {
		$filas = $consulta->fetchAll();
		
		foreach($filas as $fila) {
			$id = $fila['id'];
		}
	}
	
	$dev[] = $res;
	$dev[] = $id;
	
	return $dev;		
}

function insertarUsuario($codigoverif, $user, $password, $nombre, $apellido, $email, $direccion) {
	$sql = 'INSERT INTO res_validar_usuarios VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigoverif);
	$consulta->bindParam( 2, $user);
	$consulta->bindParam( 3, $password);
	$consulta->bindParam( 4, $nombre);
	$consulta->bindParam( 5, $apellido);
	$consulta->bindParam( 6, $email);
	$consulta->bindParam( 7, $direccion);
	$consulta->execute();
}

function enviarmailv($codigo,$correo,$nombre) {
	$mail = new PHPMailer();

	/*Configuracion PHPMailer*/
	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'YourEmail';
	$mail->Password = EMAIL_PASSWORD;

	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->setFrom('YourEmail', 'Oficina Central Restaurantia');
	$mail->addAddress($correo, 'Usuario');
	$mail->Subject  = 'Bienvenido a Restaurantia.';
	$mail->Body     = 'Bienvenido a Restauratia, '.$nombre.', puedes activar tu cuenta en el siguiente enlace http://www.nicolasmeseguer.com/Restaurantia/controlador.php?codigov='.$codigo;
	
	unset($codigo);
	$mail->send();
}

function enviarmailv2($codigo,$correo,$nombre) {
	$mail = new PHPMailer();

	/*Configuracion PHPMailer*/
	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'YourEmail';
	$mail->Password = EMAIL_PASSWORD;

	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->setFrom('YourEmail', 'Oficina Central Restaurantia');
	$mail->addAddress($correo, 'Usuario');
	$mail->Subject  = 'Registro de Restaurante en Restaurantia.';
	$mail->Body     = 'Tu restaurante, '.$nombre.', est&aacute; a punto de ser activado, solo te queda activarlo en el siguiente enlace: http://www.nicolasmeseguer.com/Restaurantia/controlador.php?codigovr='.$codigo;
	
	unset($codigo);
	$mail->send();
}

function enviarmailv3($codigo,$correo,$fecha,$hora,$nombre) {
	$mail = new PHPMailer();

	/*Configuracion PHPMailer*/
	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'YourEmail';
	$mail->Password = EMAIL_PASSWORD;

	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->setFrom('YourEmail', 'Oficina Central Restaurantia');
	$mail->addAddress($correo, 'Usuario');
	$mail->Subject  = 'Confirmacion de reserva.';
	$mail->Body     = 'Tu reserva en el res_restaurante, '.$nombre.', a las '.$hora.', el '.$fecha.', está a punto de terminar, confirma tu asistencia en el siguiente enlace http://www.nicolasmeseguer.com/Restaurantia/controlador.php?codigoff='.$codigo;
	
	unset($codigo);
	$mail->send();
}

function enviarmailv4($correo, $persona) {
	$mail = new PHPMailer();

	/*Configuracion PHPMailer*/
	$mail->IsSMTP();
	$mail->SMTPDebug = 0;
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'YourEmail';
	$mail->Password = EMAIL_PASSWORD;

	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	
	$mail->setFrom('YourEmail', 'Oficina Central Restaurantia');
	$mail->addAddress($correo, 'Restaurante');
	$mail->Subject  = 'Confirmacion de reserva.';
	$mail->Body     = 'Uno de nuestros res_usuarios, '.$persona[0].' '.$persona[1].', ha realizado una reserva en su res_restaurante, el día, '.$persona[3].', a las '.$persona[4].'.';
	
	$mail->send();
}

function getCodigos() {
	$sql = 'SELECT * FROM res_validar_usuarios';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
		
	$codigos = $consulta->fetchAll();
	
	foreach($codigos as $c) {
		$cgs[] = $c['id_conf'];
	}
	
	return $cgs;
}

function getCodigosR() {
	$sql = 'SELECT * FROM res_validar_restaurante';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
		
	$codigos = $consulta->fetchAll();
	
	foreach($codigos as $c) {
		$cgs[] = $c['id_conf'];
	}
	
	return $cgs;
}

function getCodigosRR() {
	$sql = 'SELECT * FROM res_validar_reservas';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
		
	$codigos = $consulta->fetchAll();
	
	foreach($codigos as $c) {
		$cgs[] = $c['cod_verif'];
	}
	
	return $cgs;
}

function verifUsuario($c) {
	$sql = 'SELECT * FROM res_validar_usuarios WHERE id_conf = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $c);
	$consulta->execute();
	
	$usuario = $consulta->fetchAll();
	
	foreach($usuario as $u) {
		$user = $u['user'];
		$password = $u['password'];
		$nombre = $u['nombre'];
		$apellido = $u['apellido'];
		$correo = $u['correo'];
		$direccion = $u['direccion'];
	}
	
	$sql = 'INSERT INTO res_usuarios VALUES(NULL, ?, ?, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $user);
	$consulta->bindParam( 2, $password);
	$consulta->bindParam( 3, $nombre);
	$consulta->bindParam( 4, $apellido);
	$consulta->bindParam( 5, $correo);
	$consulta->bindParam( 6, $direccion);
	$consulta->execute();
	
	$sql = 'DELETE FROM res_validar_usuarios WHERE id_conf = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $c);
	$consulta->execute();
}

function verifRestaurante($codigo) {
	$sql = 'SELECT * FROM res_validar_restaurante WHERE id_conf = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigo);
	$consulta->execute();
	
	$restaurant = $consulta->fetchAll();
	
	$id = $restaurant[0]['id']; //Para en un futuro borrar este registro
	
	$id_menu = $restaurant[0]['id_menu']; //Menu asociado al res_restaurante
	
	$nombre = $restaurant[0]['nombre'];
	$direccion = $restaurant[0]['direccion'];
	$correo = $restaurant[0]['correo'];
	$nombre_imagen = $restaurant[0]['nombre_imagen'];
	$imagen = $restaurant[0]['imagen'];
	$tipo = $restaurant[0]['tipo'];
	
	$sql = 'SELECT * FROM res_validar_menu WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id_menu);
	$consulta->execute();
	
	$menus = $consulta->fetchAll();
	
	$entrante = $menus[0]['entrante'];
	$plato1 = $menus[0]['plato1'];
	$plato2 = $menus[0]['plato2'];
	$bebida = $menus[0]['bebida'];
	$postre = $menus[0]['postre'];
	$invita = $menus[0]['invita'];
	
	$sql = 'DELETE FROM res_validar_restaurante WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id);
	$consulta->execute();

	$sql = 'DELETE FROM res_validar_menu WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id_menu);
	$consulta->execute();
	
	$sql = 'INSERT INTO res_menu VALUES(NULL, ?, ?, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $entrante);
	$consulta->bindParam( 2, $plato1);
	$consulta->bindParam( 3, $plato2);
	$consulta->bindParam( 4, $bebida);
	$consulta->bindParam( 5, $postre);
	$consulta->bindParam( 6, $invita);
	$consulta->execute();
	
	$sql = 'SELECT * FROM res_menu ORDER BY id DESC';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$menus = $consulta->fetchAll();
	
	$id = $menus[0]['id'];
	
	$sql = 'INSERT INTO res_restaurante VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id);
	$consulta->bindParam( 2, $nombre);
	$consulta->bindParam( 3, $direccion);
	$consulta->bindParam( 4, $correo);
	$consulta->bindParam( 5, $nombre_imagen);
	$consulta->bindParam( 6, $imagen);
	$consulta->bindParam( 7, $tipo);
	$consulta->execute();
}

function verifReserva($c) {
	$sql = 'SELECT * FROM res_validar_reservas WHERE cod_verif = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $c);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$id = $c[0][0];
	$id_res = $c[0][2];
	$id_user = $c[0][3];
	$fecha = $c[0][4];
	$hora = $c[0][5];

	$sql = 'DELETE FROM res_validar_reservas WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id);
	$consulta->execute();
	
	$sql = 'INSERT INTO res_reservas VALUES(NULL, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id_res);
	$consulta->bindParam( 2, $id_user);
	$consulta->bindParam( 3, $fecha);
	$consulta->bindParam( 4, $hora);
	$consulta->execute();
}

function getUserIdEsp($codigoverif) {
	$sql = 'SELECT * FROM res_validar_reservas WHERE cod_verif = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigoverif);
	$consulta->execute();
	
	$c = $consulta->fetchAll();

	$id_user = $c[0][3];
	$fecha = $c[0][4];
	$hora = $c[0][5];
	
	$sql = 'SELECT * FROM res_usuarios WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id_user);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$nombre = $c[0][3];
	$apellido = $c[0][4];
	$correo = $c[0][5];
	
	$persona[] = $nombre;
	$persona[] = $apellido;
	$persona[] = $correo;
	$persona[] = $fecha;
	$persona[] = $hora;
	
	return $persona;
}

function getUserCorrEsp($codigo) {
	$sql = 'SELECT * FROM res_validar_reservas WHERE cod_verif = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigo);
	$consulta->execute();
	
	$c = $consulta->fetchAll();

	$id_user = $c[0][3];
	
	$sql = 'SELECT * FROM res_usuarios WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id_user);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$correo = $c[0][5];
	
	return $correo;
}

function getCorreoRest($codigo) {
	$sql = 'SELECT * FROM res_validar_reservas WHERE cod_verif = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigo);
	$consulta->execute();
	
	$c = $consulta->fetchAll();

	$id_rest = $c[0][2];
	
	$sql = 'SELECT * FROM res_restaurante WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id_rest);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$correo = $c[0][4];
	
	return $correo;
}

function insertarDatos($res_menu, $codigoverif, $nombre, $direccion, $correo, $archivo, $tamanio, $tipo, $nombref) {

	$sql = 'INSERT INTO res_validar_menu VALUES(NULL, ?, ?, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $res_menu[0]);
	$consulta->bindParam( 2, $res_menu[1]);
	$consulta->bindParam( 3, $res_menu[2]);
	$consulta->bindParam( 4, $res_menu[3]);
	$consulta->bindParam( 5, $res_menu[4]);
	$consulta->bindParam( 6, $res_menu[5]);
	$consulta->execute();
	
	$sql = 'SELECT * FROM res_validar_menu ORDER BY id DESC';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$res = $consulta->fetchAll();
	
	$codigo = $res[0][0]; //Codigo del res_menu que acabo de insertar
	
	$fp = fopen($archivo, 'rb');
	$contenido = fread($fp, $tamanio);
	fclose($fp);
	
	$sql = 'INSERT INTO res_validar_restaurante VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigo);
	$consulta->bindParam( 2, $codigoverif);
	$consulta->bindParam( 3, $nombre);
	$consulta->bindParam( 4, $direccion);
	$consulta->bindParam( 5, $correo);
	$consulta->bindParam( 6, $nombref);
	$consulta->bindParam( 7, $contenido);
	$consulta->bindParam( 8, $tipo);
	$consulta->execute();
}

function getRest($cod){
	$sql = 'SELECT * FROM res_validar_reservas WHERE cod_verif = ?;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $cod);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$codigo = $c[0][2];
	
	$sql = 'SELECT * FROM res_restaurante WHERE id = ?;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigo);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$idmenu = $c[0][1];
	
	$arr[] = $c[0][2];
	$arr[] = $c[0][3];
	
	$sql = 'SELECT * FROM res_menu WHERE id = ?;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $idmenu);
	$consulta->execute();
	
	$c = $consulta->fetchAll();
	
	$arr[] = $c[0][1];
	$arr[] = $c[0][2];
	$arr[] = $c[0][3];
	$arr[] = $c[0][4];
	$arr[] = $c[0][5];
	$arr[] = $c[0][6];
	
	return $arr;
}

function getRestaurante($id) {
	$sql = 'SELECT * FROM res_restaurante WHERE id = ?;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id);
	$consulta->execute();
	
	$r = $consulta->fetchAll();
	
	foreach($r as $f) {
		$m[] = $f[0];
		$m[] = $f[1];
		$m[] = $f[2];
		$m[] = $f[3];
		$m[] = $f[4];
		$m[] = $f[5];
		$m[] = $f[6];
		$m[] = $f[7];
	}
	return $m;
}

function getMenu($cd) {
	$sql = 'SELECT * FROM res_menu WHERE id = ?;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $cd);
	$consulta->execute();
	
	$r = $consulta->fetchAll();
	
	foreach($r as $f) {
		$m[] = $f['entrante'];
		$m[] = $f['plato1'];
		$m[] = $f['plato2'];
		$m[] = $f['bebida'];
		$m[] = $f['postre'];
		$m[] = $f['invita'];
	}
	
	return $m;
}

function checkCode($c) {
	$sql = 'SELECT * FROM res_restaurante;';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$codigos = $consulta->fetchAll();
	
	foreach($codigos as $cc) {
		$cg[] = $cc['id'];
	}
	
	if(in_array($c, $cg)) {
		return true;
	}
	else
		return false;
}

function getAllRestaurantes() {
	$sql = 'SELECT * FROM res_restaurante';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->execute();
	
	$restaurantes = $consulta->fetchAll();
	
	foreach( $restaurantes as $res_restaurante) {
		$id[] = $res_restaurante['id'];
		$id_menu[] = $res_restaurante['id_menu'];
		$nombre[] = $res_restaurante['nombre'];
		$direccion[] = $res_restaurante['direccion'];
		$correo[] = $res_restaurante['correo'];
		$nombre_imagen = $res_restaurante['nombre_imagen'];
		$imagen[] = $res_restaurante['imagen'];
		$tipo[] = $res_restaurante['tipo'];
	}
	$datos[] = $id;
	$datos[] = $id_menu;
	$datos[] = $nombre;
	$datos[] = $direccion;
	$datos[] = $correo;
	$datos[] = $nombre_imagen;
	$datos[] = $imagen;
	$datos[] = $tipo;
	
	return $datos;
}

function agregarReserva($iduser, $codigoverif, $fecha, $hora, $idr) {
	$sql = 'INSERT INTO res_validar_reservas VALUES(NULL, ?, ?, ?, ?, ?)';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $codigoverif);
	$consulta->bindParam( 2, $idr);
	$consulta->bindParam( 3, $iduser);
	$consulta->bindParam( 4, $fecha);
	$consulta->bindParam( 5, $hora);
	$consulta->execute();
}

function getCorreo($id) {
	$sql = 'SELECT * FROM res_usuarios WHERE id = ?';
	$consulta = $GLOBALS['DB']->prepare($sql);
	$consulta->bindParam( 1, $id);
	$consulta->execute();
	
	$resultado = $consulta->fetchAll();
	
	return $resultado[0][5];
}
?>