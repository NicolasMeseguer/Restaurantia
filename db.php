<?php
// database connection and schema constants
define('DB_HOST', 'URLDB');
define('DB_USER', 'Usuario');
define('DB_PASSWORD', 'Password');
define('DB_SCHEMA', 'NombreDeLaBD');
define('DB_TBL_PREFIX', 'WROX_');



// establish a connection to the database server
try{
	$GLOBALS['DB'] =  new PDO( "mysql:host=" . DB_HOST . ";dbname=" . DB_SCHEMA, DB_USER, DB_PASSWORD );

}catch(PDOExecption $e) {
        print "Error!: " . $e->getMessage() . "</br>";
}

?>
