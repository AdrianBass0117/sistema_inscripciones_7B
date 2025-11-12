<?php
// Conectar a MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_inscripciones";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Datos que tú ingresas
$email = "josueisaacmorenol@gmail.com";
$password_plain = "Morenol18";

// Generar hash BCrypt
$password_hash = password_hash($password_plain, PASSWORD_BCRYPT);

// Insertar en la base de datos
$sql = "INSERT INTO comite (email, password_hash) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password_hash);

if ($stmt->execute()) {
    echo "✅ Usuario insertado correctamente\n";
    echo "Email: $email\n";
    echo "Contraseña: $password_plain\n";
    echo "Hash generado: $password_hash\n";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
