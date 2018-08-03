<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="utf-8">
		<title>Restaurantia</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css">
		<link rel="icon" type="image/png" href="img/r.png" />
	</head>
	
	<body>
	
		<div id="menu">
			<div id="titulo">Restaurantia</div>
			<div id="nav">
				<a href="https://www.instagram.com" target="_blank"><img class="icon" src="img/i.png" alt="Icono Instagram"></a>
				<a href="https://www.facebook.com" target="_blank"><img class="icon" src="img/f.png" alt="Icono Facebook"></a>
				<a href="https://www.pinterest.com" target="_blank"><img class="icon" src="img/p.png" alt="Icono Pinterest"></a>
			</div>
		</div>
		
		<?php
		function displayInicio() {
			?>
			<div id="principal">
			
				<div id="registrar">
					<div class="contenido">
						<p>En tan solo 3 minutos, añade tu restaurante a esta página y prepárate para responder las innumerables llamadas de pedidos que vas a recibir.</p>
						<button type="button" id="botonregistro" onclick="window.location.replace('controlador.php?opcion=r')">Registra tu restaurante</button>
					</div>
				</div>
				
				<hr>
				
				
				<div id="encontrar">
					<div class="contenido">
						<p>¿ Cansado de ir por la calle y no encontrar tu restaurante ?<br>Desde tu sofá podrás encontrar mas de 15 restaurantes cercanos en un par de minutos.</p>
						<button type="button" id="botonregistro" onclick="window.location.replace('controlador.php?opcion=e')">Encuentra tu restaurante</button>
					</div>
				</div>
			</div>
			<?php
		}
		
		function displayFormRegistroRestaurante($error) {
			?>
			<div id="contenedorregistro">
				<div>Registra tu restaurante</div>
				<div id="menuregistro">
					<div id="rarriba">
						<form action="controlador.php" method="POST" enctype="multipart/form-data">
							<div class="labelr">
								<img src="img/shop.png" alt="icono tienda">
								<input type="text" placeholder="Nombre" required name="nombre">
							</div>
							<div class="labelr">
								<img src="img/location.png" alt="icono mapa">
								<input type="text" placeholder="Dirección" required name="direccion">
							</div>
							<div class="labelr">
								<img src="img/mail.png" alt="icono correo">
								<input type="text" placeholder="mirestaurante@ejemplo.com" size="30" required name="email">
							</div>
							<br>
							<div class="labelr">
								<table>
								<caption><img src="img/menu.png" alt="icono cuchillo y tenedor"></caption>
								<tbody>
									<tr>
										<td>Entrante</td>
										<td><input type="text" name="entrante" required></td>
									</tr>
									<tr>
										<td>Plato 1</td>
										<td><input type="text" name="plato1" required></td>
									</tr>
									<tr>
										<td>Plato 2</td>
										<td><input type="text" name="plato2" required></td>
									</tr>
									<tr>
										<td>Bebida</td>
										<td><input type="text" name="bebida" required></td>
									</tr>
									<tr>
										<td>Postre</td>
										<td><input type="text" name="postre" required></td>
									</tr>
									<tr>
										<td>¿La casa invita?(café, licor)</td>
										<td><input type="checkbox" name="adicional" value="true"></td>
									</tr>
								</tbody>
								</table>
								<div class="labelr">
									<img src="img/camera.png" alt="icono camara">
									<input type="file" name="foto" required>
								</div>
								<div class="labelr">
									<input type="submit" value="Registrar restaurante !" name="enviar">
								</div>
								<div class="labelr">
									<?php
									echo $error;
									?>
								</div>
							</div>
						</form>
					</div>
					<div id="rabajo">
						<a href="controlador.php?cerrar=true">Volver</a>
					</div>
				</div>
			</div>
			<?php
		}
		
		function displayOpciones() {
			?>
			<div class="cajaopciones">
				<div class="login">
					<div>¿ Tienes cuenta ?</div>
					<div><a href="controlador.php?opcion=login">Inicia Sesion</a></div>
				</div>
				
				<div class="login">
					<div>¿ No tienes cuenta aún ?</div>
					<div><a href="controlador.php?opcion=register">Regístrate</a></div>
				</div>
			</div>
			<?php
		}
		
		function displayRestaurantes($restaurantes) {
			?>
			<div class="megacaja">
				<?php
				$lg = count($restaurantes[0]);
				for ($i=0;$i<$lg;$i++) {
					?>
					<a href="controlador.php?restaurante=<?php echo $restaurantes[0][$i] ?>">
						<div class="cajacontainer">
							<h2>
							<?php
							echo $restaurantes[2][$i];
							?>
							</h2>
							<h3>Dirección, 
							<?php echo $restaurantes[3][$i]; ?>
							</h3>
							<img style="width: 500px; height: 300px;" src="cargaimagen.php?id=<?php echo $restaurantes[0][$i]?>&type=<?php echo $restaurantes[7][$i]?>" alt="Restaurante <?php echo $restaurantes[2][$i] ?>">
							<?php
							?>
						</div>
					</a>
					<?php
				}
				?>
				<div id="rabajo">
					<a href="controlador.php?cerrar=true">Volver</a>
				</div>
			</div>
			<?php
		}
		
		function displayInfo($restaurante, $menu) {
			?>
			<div class="cajacontainer">
			<h2>
			<?php
			echo $restaurante[2];
			?>
			</h2>
			<h3>Dirección, 
			<?php echo $restaurante[3] ?>
			</h3>
			<img style="width: 500px; height: 300px;" src="cargaimagen.php?id=<?php echo $restaurante[0]?>&type=<?php echo $restaurante[7]?>" alt="Restaurante <?php echo $restaurante[2] ?>">
			<table>
			<caption><h2>Menu</h2></caption>
				<tr>
					<td>Entrante</td>
					<td><?php echo $menu[0]; ?></td>
				</tr>
				<tr>
					<td>Plato 1</td>
					<td><?php echo $menu[1]; ?></td>
				</tr>
				<tr>
					<td>Plato 2</td>
					<td><?php echo $menu[2]; ?></td>
				</tr>
				<tr>
					<td>Bebida</td>
					<td><?php echo $menu[3]; ?></td>
				</tr>
				<tr>
					<td>Postre</td>
					<td><?php echo $menu[4]; ?></td>
				</tr>
				<tr>
					<td>Invita</td>
					<td><?php if($menu[5]=='true') echo "Invita tras la comida"; else echo "No incluye invitaciones"; ?></td>
				</tr>
			</table>
			</div>
			<a href="controlador.php?motivo=reserva&id=<?php echo $restaurante[0] ?>">Solicitar reserva !</a>
			<div id="rabajo">
				<a href="controlador.php">Volver</a>
			</div>
			<?php
		}
		
		function displayConfirmacionR(){
			?>
			<div>
				Tu reserva está a punto de finalizar, te hemos enviado un enlace de verificación a tu correo.<br>
				Te estamos redireccionando, danos un segundo...
			</div>
			<?php
		}
		
		function displayOkReserva() {
			?>
			<div>
				Gracias, tu reserva ha sido confirmada !<br>
				Te hemos enviado un ticket a modo de PDF a tu email con toda la información, gracias por confiar en nosotros !
				<a href="cerrar.php">Haz clic aquí para volver a 'Restaurantia'</a>
			</div>
			<?php
		}
		
		function displayFormSolicitud($restaurante, $direccion, $id) {
			?>
			<div class="cajasolicitud">
				<form action="controlador.php?motivo=reserva&id=<?php echo $id ?>" method="POST">
					<h2><?php echo $restaurante[2] ?></h2>
					<table>
						<tr>
							<td>Fecha</td>
							<td><input type="date" name="fecha"></td>
						</tr>
						<tr>
							<td>Hora</td>
							<td><input type="time" name="hora"></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" value="Grabar reserva" name="enviar"></td>
						</tr>
					</table>
				</form>
			</div>
			<?php
		}
		
		function displayRRCorrecto() {
			?>
			<div class="cajaderrcorrecto">
				Se ha eviado un enlace de verificación al correo, comprueba la bandeja de Spam.
			</div>
			<?php
		}
		
		function displayRegister($error) {
			?>
			<div class="cajadesesion">
			<div class="formsesion">
				<form action="controlador.php?opcion=register" method="POST" autocomplete="off">
					<table>
						<tr>
							<td>Usuario</td>
							<td><input type="text" name="user" placeholder="Para el inicio de sesion" required></td>
						</tr>
						<tr>
							<td>Contraseña</td>
							<td><input type="password" name="password" placeholder="Contraseña" required></td>
						</tr>
						<tr>
							<td>Repite la contraseña</td>
							<td><input type="password" name="password2" placeholder="Repite tu contraseña" required></td>
						</tr>
						<tr>
							<td>Nombre</td>
							<td><input type="text" name="nombre" required size="15"></td>
						</tr>
						<tr>
							<td>Apellido</td>
							<td><input type="text" name="apellido" required size="15"></td>
						</tr>
						<tr>
							<td>Correo Electrónico</td>
							<td><input type="text" name="correo" required placeholder="ejemplo@gmail.com" size="25"></td>
						</tr>
						<tr>
							<td>Direccion</td>
							<td><input type="text" name="direccion" required placeholder="C/ Juán de la Cierva" size="30"></td>
						</tr>
						<tr>
							<td colspan="2"><div align="center" class="g-recaptcha" data-theme="dark" data-sitekey="6LdwImgUAAAAADFWYdwSpA1Laz8cA-J4scsW9-t2"></div></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;"><input type="submit" name="enviar" value="Registrarme en Restaurantia"></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center; background-color: red;"><?php echo $error ?></td>
						</tr>
					</table>
				</form>
			</div>
			</div>
			<div style="align-self: center; text-decoration: none; position: absolute; top: 20px; left: 20px;"><a href="controlador.php">Volver</a></div>
			<?php
		}
		
		function displayRCorrecto() {
			?>
			<div class="cajadesesion">
			<div class="formsesion">
				Un email de verificación ha sido enviado a tu correo.<br>
				Gracias por unirte a Restaurantia !
			</div>
			</div>
			<?php
		}
		
		function displayOkLogin() {
			?>
			<div class="cajadesesion">
			<div class="formsesion2">
				Has iniciado sesión con exito<br>
				Te estamos redireccionando a la página principal, un momento.
			</div>
			</div>
			<?php
		}
		
		function displayCerrar() {
			?>
			<div style="align-self: center; text-decoration: none; position: absolute; top: 20px; left: 20px;"><a href="cerrar.php">Cerrar Sesion</a></div>
			<?php
		}
		
		function displayLogin($error) {
			?>
			<div class="cajadesesion">
			<div class="formsesion2">
				<form action="controlador.php?opcion=login" method="POST" autocomplete="off">
					<table>
						<tr>
							<td>Usuario</td>
							<td><input type="text" name="user"></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" name="password"></td>
						</tr>
						<tr>
							<td></td>
						</tr>
						<tr>
							<td></td>
						</tr>
						<tr>
							<td></td>
						</tr>
						<tr>
							<td></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;"><input type="submit" name="enviar" value="Iniciar sesion en restaurantia"></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;"><?php echo $error ?></td>
						</tr>
					</table>
				</form>
				<div style="align-self: center; text-decoration: none; position: absolute; top: 20px; left: 20px;"><a href="controlador.php">Volver</a></div>
			</div>
			</div>
			<?php
		}
		
		function displayErrorCodigo() {
			?>
			<div class="cajaerrorc">
				El código introducido no es correcto, intentalo de nuevo.
			</div>
			<?php
		}
		
		function displayOkCodigo() {
			?>
			<div class="cajaokc">
				Tu cuenta ha sido verificada con éxito, puedes iniciar sesion.
			</div>
			<?php
		}
		
		function displayOkCodigoR() {
			?>
			<div class="cajaokc">
				Tu Restaurante ha sido registrado con éxito, gracias por formar parte de Restaurantia.
			</div>
			<?php
		}
		
		function displayFoot() {
			?>
			<div id="footer">
				<div id="arriba">
					<div id="contenido">Restaurantia es una web especialzada en la busqueda
					de restaurantes cercanos a su ubicacion, así como, es capaz de registrar 
					nuevos restaurantes.</div>
					
					<div><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d50317.623929586975!2d-1.1621948732626122!3d37.9805948634384!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6381f8d5928c7f%3A0xd627129b38c4ab9a!2sMurcia!5e0!3m2!1ses!2ses!4v1513335219255" width="400" height="250" frameborder="0" style="border:0" allowfullscreen></iframe></div>
				</div>
				<div id="abajo">
					<div>Creado bajo licencia de Nicolás Meseguer Iborra</div>
				</div>
			</div>
			<?php
		}
		?>
		
		<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>
	</body>
</html>