<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BD_SOFTWARE_HOTEL";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$dni = $_POST['dni'];
$cargo = $_POST['cargo']; // Cambio de 'token' a 'cargo'
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$turno = $_POST['turno'];
$salario = $_POST['salario'];
$clave = $_POST['password-register'];

// Determinar si el usuario es recepcionista o administrador
if ($cargo === 'recepcionista') {
    // Llamar al procedimiento almacenado para recepcionistas
    $sql = "CALL RegistrarRecepcionista(?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $dni, $nombre, $apellido, $email, $telefono, $clave, $turno, $salario);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso. Tu credencial es: {$nombre[0]}{$apellido[0]}{$dni[0]}{$dni[1]}{$dni[2]}{$dni[3]}');</script>";
        echo "<script>window.location.href = 'login_registro.html';</script>";
    } else {
        echo "<script>alert('Error en el registro');</script>";
    }

    $stmt->close();

} elseif ($cargo === 'administrador') {
    // Procedimiento para el rol de administrador
    // Cambia `RegistrarAdministrador` si tienes otro procedimiento para administradores
    $sql = "CALL RegistrarAdministrador(?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $dni, $nombre, $apellido, $email, $telefono, $clave, $turno, $salario);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso como Administrador. Tu credencial es: {$nombre[0]}{$apellido[0]}{$dni[0]}{$dni[1]}{$dni[2]}{$dni[3]}');</script>";
        echo "<script>window.location.href = 'login_registro.html';</script>";
    } else {
        echo "<script>alert('Error en el registro');</script>";
    }

    $stmt->close();

} else {
    echo "<script>alert('Cargo no válido');</script>";
}

$conn->close();
?>
