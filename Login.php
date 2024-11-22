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
$dni = $_POST['credencial'];
$password = $_POST['password'];

// Validar las credenciales
$sql = "SELECT cargo FROM USUARIO WHERE dni = ? AND clave = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $dni, $password);
$stmt->execute();
$stmt->bind_result($cargo);
$stmt->fetch();

if ($cargo) {
    if ($cargo == "Recepcionista") {
        // Redirigir a la página de recepcionista
        echo "<script>window.location.href = 'index1.html';</script>";
    } else {
        // Redirigir a otra página si el cargo es diferente (por ejemplo, administrador)
        echo "<script>window.location.href = 'Administrador.html';</script>";
    }
} else {
    // Mostrar mensaje de error si las credenciales no coinciden
    echo "<script>alert('DNI o contraseña incorrectos');</script>";
    echo "<script>window.location.href = 'index.html';</script>";
}

$stmt->close();
$conn->close();
?>
