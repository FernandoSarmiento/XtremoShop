<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BD_SOFTWARE_HOTEL";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// Consulta para obtener los clientes registrados
$sql = "SELECT DNI_CLIENTE, Nombres, Apellidos, Telefono, Observacion FROM CLIENTE";
$result = $conn->query($sql);

$clientes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
}

$conn->close();

// Retorna los datos de los clientes en formato JSON
echo json_encode($clientes);
?>
