<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    echo '
    <script>
        alert("Por favor, inicie sesión");
        window.location = "LoginRegistro.php";
    </script>
    ';
    session_destroy();
    die();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "ecomers");

if ($conexion->connect_error) {
    die("No se ha podido conectar al servidor de Base de datos: " . $conexion->connect_error);
}

// Obtener el correo del usuario desde la sesión
$correoUsuario = $_SESSION['usuario'];

// Consulta para obtener los detalles del usuario
$queryUsuario = "SELECT Nombre, Apellido, Correo, Img FROM Usuario WHERE Correo = '$correoUsuario'";
$resultadoUsuario = mysqli_query($conexion, $queryUsuario);

if (!$resultadoUsuario) {
    die("Error en la consulta de usuario: " . mysqli_error($conexion));
}

$usuario = mysqli_fetch_assoc($resultadoUsuario);

$nombre = $usuario['Nombre'];
$apellido = $usuario['Apellido'];
$imgData = $usuario['Img'];

// Consulta para obtener publicaciones del blog
$queryBlog = "SELECT * FROM Blog ORDER BY fecha DESC LIMIT 10";
$resultadoBlog = mysqli_query($conexion, $queryBlog);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../styleIndex.css">
    <title>INFOATLAS</title>
    <style>
    .hidden {
        display: none;
    }
    </style>
</head>
<body>
    <main>
        <!-- MENU -->
        <div class="contenedor-header">
            <header>
                <div class="logo">
                    <h1>TODO<span class="txtAzul">MODA</span></h1>
                </div>
                <nav id="nav">
                    <a href="#inicio" onclick="seleccionar()">Inicio</a>
                    <a href="CategoriasAdmin.php" onclick="seleccionar()">Categorías</a>
                    <a href="AlmacenAdmin.php" onclick="seleccionar()">Almacén</a>
                    <a href="UsuariosAdmin.php" onclick="seleccionar()">Usuarios</a>
                </nav>
                <div class="nav-responsive" id="icono-var" onclick="mostrarOcultarMenu()">
                    <i class="fa-solid fa-bars"></i>
                </div>
                <div class="busqueda">
                    <form action="BuscarAdmin.php" method="GET" class="busqueda-form">
                        <input type="text" name="query" placeholder="Buscar..." class="busqueda-input">
                        <button type="submit" class="busqueda-btn">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="perfil">
                    <img src="data:image/jpeg;base64,<?= base64_encode($imgData) ?>" alt="Imagen de perfil" class="img_perfil" onclick="toggleProfileMenu()">
                    <div class="perfil-info">
                        <div id="profileMenu" class="perfil-links hidden">
                            <p><strong>¡Hola! <?= $nombre ?></strong></p>
                            <a href="Editar_Perfil.php">Editar Perfil</a>
                            <a href="../cerrar_sesion.php">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </header>
        </div>

        <!-- Sección de inicio -->
        <section id="inicio" class="inicio">
            <div class="contenido-seccion">
                <div class="info">
                    <h2>TODO<span class="txtAzul">MODA</span></h2>
                    <p>Moda para todos, estilo para ti</p>
                    <p>Versión Administrador</p>
                </div>
                <div class="opciones">
                    <div class="opcion">
                        01.CONFIANZA
                    </div>
                    <div class="opcion">
                        02.CALIDAD
                    </div>
                    <div class="opcion">
                        03.BUEN PRECIO
                    </div>
                    <div class="opcion">
                        04.SERVICIO AL CLIENTE
                    </div>
                    <div class="opcion">
                        05.GARANTIA
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección de Blog -->
        <section id="blog" class="blog">
            <div class="contenido-blog">
                <h2 class="animable">Últimas Noticias</h2>
                <div class="btn-blogs">
                    <a href="BlogDetalleAdmin.php"><button class="animable">Ver Blogs</button></a>
                    <button class="animable" onclick="mostrarSeccionIndex('crear-blog')">Crear Blog</button>
                </div>
                <div class="blog-grid">
                    <?php if (mysqli_num_rows($resultadoBlog) > 0): ?>
                        <?php while ($entrada = mysqli_fetch_assoc($resultadoBlog)): ?>
                            <article class="entrada-blog">
                                <h3 class="animable"><?= $entrada['Titulo'] ?></h3>
                                <p class="animable"><?= substr($entrada['Contenido'], 0, 150) . '...'; ?></p>
                                <a href="BlogDetalleAdmin.php?Id=<?= $entrada['Id'] ?>"><button class="animable">Leer más</button></a>
                            </article>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No hay entradas en el blog en este momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Crear Blog -->
        <section id="crear-blog" class="form-blog hidden">
            <div class="contenedor-form">
                <div class="volver">
                    <a href="#" onclick="mostrarSeccionIndex('blog'); return false;"><i class="fa-solid fa-arrow-left"></i></a>
                </div>
                <form action="CrearBlog.php" method="POST" enctype="multipart/form-data">
                    <h3>CREAR BLOG</h3>
                    <div class="form-group">
                        <label for="Titulo">TITULO:</label>
                        <input type="text" name="Titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="comentario">CONTENIDO:</label>
                        <textarea name="Contenido" id="Contenido" rows="20" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file" class="custom-file-upload">
                            <i class="fa fa-cloud-upload"></i> Suba la foto del blog
                        </label>
                        <input type="file" id="file" name="Img" accept="image/*" required> 
                        <p id="file-message" class="file-message">Archivo seleccionado: Ninguno</p>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn-subir">SUBIR BLOG</button>
                    </div>
                </form>
            </div>
        </section>

    </main>

    <footer>
        <div class="info">
            <div class="seccion">
                <h4>Sobre Nosotros</h4>
                <p>Somos una tienda de moda comprometida con ofrecer productos de alta calidad a precios accesibles. Nuestro objetivo es que todos encuentren su estilo perfecto.</p>
            </div>
            <div class="seccion">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="CategoriasAdmin.php">Categorías</a></li>
                    <li><a href="AlmacenAdmin.php">Almacén</a></li>
                    <li><a href="UsuariosAdmin.php">Usuarios</a></li>
                    <li><a href="Editar_Perfil.php">Editar Perfil</a></li>
                    <li><a href="../cerrar_sesion.php">Cerrar Sesión</a></li>
                </ul>
            </div>
            <div class="seccion">
                <h4>Contacto</h4>
                <p>2024 - <span class="txtAzul">TODOMODA</span> Todos los derechos reservados</p>
                <p>Teléfono: <span class="txtAzul">3318237277</span></p>
                <p>Email: <span class="txtAzul">contacto@todamoda.com</span></p>
            </div>
            <div class="seccion">
                <h4>Redes Sociales</h4>
                <div class="redes-sociales">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="../Archivos js/menu-responsive.js"></script>
    <script src="../Archivos js/loginregistro.js"></script>
    <script src="../Archivos js/animaciones.js"></script>

</body>
</html>