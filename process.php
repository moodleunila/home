<?php
// Configure your Subject Prefix and Recipient here
//$asuntoPrefix = '[Contact via website]';
$errors = array(); // array to hold validation errors
$data   = array(); // array to pass back data
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$nombre    = stripslashes(trim($_POST['nombre']));
    $aPaterno    = stripslashes(trim($_POST['aPaterno']));
    $aMaterno    = stripslashes(trim($_POST['aMaterno']));
    $telefono = stripslashes(trim($_POST['telefono']));
    $celular = stripslashes(trim($_POST['celular']));
    $email   = stripslashes(trim($_POST['email']));  
    $mensaje = stripslashes(trim($_POST['mensaje']));	
    if (empty($nombre))
        $errors['nombre'] = 'El nombre es requerido.';
    if (empty($aPaterno))
        $errors['paterno'] = 'El aPaterno es requerido.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'El correo electrónico es inválido';
    if (empty($mensaje))
        $errors['mensaje'] = 'El mensaje es requerido';
    if (!empty($errors)){
        $data['success'] = false;
        $data['errors']  = $errors;
    } 
    else{
        $bandera = true;
        if (!preg_match("([a-zA-ZñÑáéíóúÁÉÍÓÚ]+$)", $nombre)){
            $errors['formato-nombre'] = 'El formato de nombre es incorrecto';
            $bandera = false;
        }
        if (!preg_match("([a-zA-ZñÑáéíóúÁÉÍÓÚ]+$)", $aPaterno)){
            $errors['formato-paterno'] = 'El formato de paterno es incorrecto';
            $bandera = false;
        }
        if (preg_match('/(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$mensaje)){
            $errors['formato-mensaje'] = 'El formato de mensaje es una URL';
            $bandera =  false;
        }        
        if ($bandera){
            persistir_database($nombre, $aPaterno, $aMaterno, $telefono, $celular, $email, $mensaje);
            $data['success'] = true;
        }
        else{
            $data['success'] = false; 
        }
    }
    // return all our data to an AJAX call
    $data['errors'] = $errors;
    echo json_encode($data);
}
function persistir_database($nombre, $aPaterno, $aMaterno, $telefono, $celular, $email, $mensaje){
    /*
    Conexion BD
    */
    $usuario = "root";
    $password = "";
    $servidor = "localhost";
    $database = "contacto";
    date_default_timezone_set('America/Mexico_City');
    $date_time = date("Y-m-d H:i:s");
    $table = "tbl_lead";
    // Create connection
    $conn = new mysqli($servidor, $usuario, $password, $database);
    mysqli_set_charset($conn, 'utf8');
    // Check connection
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    $sql = "INSERT INTO $table (id, nombre, a_paterno, a_materno, telefono, celular, email, fecha_registro, mensaje) VALUES (NULL, '$nombre', '$aPaterno', '$aMaterno', '$telefono', '$celular', '$email', '$date_time', '$mensaje')";
    if ($conn->query($sql) === FALSE)
        echo "Error: " . $sql . "<br>" . $conn->error;
    $conn->close();
}