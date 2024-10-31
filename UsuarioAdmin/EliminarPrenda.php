<?php
    session_start();

    // Verifica si el usuario está autenticado
    if (!isset($_SESSION['usuario'])) {
        echo '
        <script>
            alert("Por favor, inicie sesión");
            window.location = "../LoginRegistro.php";
        </script>
        ';
        session_destroy();
        die();
    }

    // Conexión a la base de datos
    $conexion = mysqli_connect("localhost", "root", "", "ecomers");

    if (!$conexion) {
        die("No se ha podido conectar al servidor de Base de datos: " . mysqli_connect_error());
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Codigo = $_POST['Codigo'];

        $queryEliminar = "DELETE FROM Prenda WHERE Codigo = $Codigo";
        $resultadoEliminar = mysqli_query($conexion, $queryEliminar);

        if (mysqli_query($conexion, $queryEliminar)) {
            header("Location: CategoriasAdmin.php");
        } else {
            echo '
            <script>
            alert("Algo falló, inténtelo de nuevo");
            window.history.back();
            </script>
            ';
        }
    }
      

    mysqli_close($conexion);
?>