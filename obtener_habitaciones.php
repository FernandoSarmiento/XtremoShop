<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BD_SOFTWARE_HOTEL";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// Especificar que el contenido es JSON
header('Content-Type: application/json');

// Obtener el estado o ID de habitación
$estado = isset($_GET['estado']) ? $conn->real_escape_string($_GET['estado']) : null;
$id_habitacion = isset($_GET['id']) ? intval($_GET['id']) : null;

// Inicializar la consulta SQL
$sql = "SELECT * FROM Habitacion WHERE 1=1"; // Base para agregar condiciones
$params = [];
$types = ""; // Para los tipos de parámetros

if ($estado) {
    // Preparar la cláusula WHERE para el estado
    $sql .= " AND Estado = ?";
    $params[] = $estado;
    $types .= "s"; // 's' para string
}

if ($id_habitacion) {
    // Preparar la cláusula WHERE para el ID de habitación
    $sql .= " AND ID_HABITACION = ?";
    $params[] = $id_habitacion;
    $types .= "i"; // 'i' para integer
}

// Preparar la consulta
$stmt = $conn->prepare($sql);

if ($types) {
    // Solo enlazar parámetros si hay alguno
    $stmt->bind_param($types, ...$params);
}

// Ejecutar consulta
$stmt->execute();

// Obtener resultados
$result = $stmt->get_result();

if (!$result) {
    die(json_encode(['error' => 'Error en la consulta: ' . $stmt->error]));
}

// Procesar resultados de la consulta
$habitaciones = [];
while ($row = $result->fetch_assoc()) {
    $habitaciones[] = $row;
}

// Devolver el resultado en formato JSON
echo json_encode($habitaciones);

// Cerrar conexión
$stmt->close();
$conn->close();
?>
