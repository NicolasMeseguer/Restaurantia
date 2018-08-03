<?php
require 'controlador.php';

session_destroy();

header('Location: controlador.php');
?>