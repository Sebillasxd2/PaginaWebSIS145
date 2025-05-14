<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="estilo.css">
</head>
<body>
  <?php

  if (isset($_GET['eliminado'])) {
      if ($_GET['eliminado'] == '1') {
          echo '<div class="mensaje-flotante">Producto eliminado correctamente</div>';
      } else {
          echo '<div class="mensaje-flotante" style="background-color: #f44336;">Error al eliminar el producto</div>';
      }

      
     
      echo '<script>
              setTimeout(function() {
                  var mensajes = document.querySelectorAll(".mensaje-flotante");
                  mensajes.forEach(function(mensaje) {
                      mensaje.style.display = "none";
                  });
              }, 3000);
            </script>';
  }
  // Mensajes de actualización
if (isset($_GET['actualizado']) && $_GET['actualizado'] === '1') {
  echo '<div class="mensaje-flotante">Producto actualizado correctamente</div>';
}
if (isset($_GET['usuario_actualizado']) && $_GET['usuario_actualizado'] === '1') {
  echo '<div class="mensaje-flotante">Usuario actualizado correctamente</div>';
}
  ?>
  
  <header>
    <div class="header-title">
      <img src="Imagenes/logo.png" alt="Logo" class="header-logo">
      <h1>Admin Panel</h1>
    </div>
    <nav>
      <a href="index.html">Inicio</a>
      <a href="#nuevo-producto">Nuevo Producto</a>
      <a href="#lista-productos">Lista Productos</a>
      <a href="#lista-usuarios">Lista Usuarios</a>
      <a href="login.html">Cerrar Sesión</a>
    </nav>
  </header>

  <section id="nuevo-producto" class="welcome-content">
    <h2>Crear nuevo producto</h2>
    <form action="agregarProducto.php" method="post" enctype="multipart/form-data">
      <input type="text" name="nombre" placeholder="Nombre" required><br><br>
      <textarea name="descripcion" placeholder="Descripción" required></textarea><br><br>
      <input type="number" name="precio" placeholder="Precio" step="0.01" required><br><br>
      <input type="number" name="stock" placeholder="Stock" required><br><br>
      <input type="text" name="categoria" placeholder="Categoría" required><br><br>
      <input type="file" name="imagen" accept="image/*" required><br><br>
      <button type="submit">Agregar Producto</button>
    </form>
  </section>
  
  <section id="crud-ubicacion" class="welcome-content">
    <h2>Gestión de Ubicaciones</h2>
    <?php
    // Mensajes de éxito/error para ubicación
    if (isset($_GET['ubicacion_agregada'])) {
        if ($_GET['ubicacion_agregada'] == '1') {
            echo '<div class="mensaje-flotante">Ubicación agregada correctamente</div>';
        } else {
            echo '<div class="mensaje-flotante" style="background-color: #f44336;">Error al agregar la ubicación</div>';
        }
        echo '<script>
                setTimeout(function() {
                    var mensajes = document.querySelectorAll(".mensaje-flotante");
                    mensajes.forEach(function(mensaje) {
                        mensaje.style.display = "none";
                    });
                }, 3000);
              </script>';
    }
    ?>
    <h3>Agregar nueva ubicación</h3>
    <form action="actualizarUbicacion.php" method="post">
      <input type="text" name="descripcion" placeholder="Descripción de ubicación" required><br><br>
      <input type="text" name="url" placeholder="URL" required><br><br>
      <button type="submit">Agregar Ubicación</button>
    </form>
    <h3>Ubicaciones existentes</h3>
    <?php
    include 'conexion.php';
    $sql = "SELECT id, descripcion, url FROM ubicacion ORDER BY descripcion";
    $resultado = $conexion->query($sql);

    if (!$resultado) {
        echo "<p>Error en la consulta: " . $conexion->error . "</p>";
    } else if ($resultado->num_rows > 0) {
        echo "<!-- Registros encontrados: " . $resultado->num_rows . " -->";
        echo "<table class='tabla-usuarios'>";
        echo "<tr><th>ID</th><th>Descripción</th><th>URL</th><th>Acciones</th></tr>";
        while ($ubicacion = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $ubicacion['id'] . "</td>";
            echo "<td>" . htmlspecialchars($ubicacion['descripcion']) . "</td>";
            echo "<td>" . htmlspecialchars($ubicacion['url']) . "</td>";
            echo "<td>
                    <form action='editarUbicacion.php' method='get' style='display:inline;'>
    <input type='hidden' name='id' value='" . $ubicacion['id'] . "'>
    <button type='submit'>Editar</button>
</form>

                    <form action='eliminarUbicacion.php' method='post' onsubmit=\"return confirm('¿Eliminar esta ubicación?');\" style='display:inline;'>
                        <input type='hidden' name='id' value='" . $ubicacion['id'] . "'>
                        <button type='submit'>Eliminar</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay ubicaciones registradas.</p>";
    }
    $conexion->close();
    ?>
  </section>
  <section id="lista-productos" class="welcome-content">
    <h2>Lista de Productos</h2>
    <div id="productos">
      <?php
      include 'conexion.php';
      $sql = "SELECT * FROM productos";
      $resultado = $conexion->query($sql);

      if ($resultado->num_rows > 0) {
          echo "<ul>";
          while ($producto = $resultado->fetch_assoc()) {
            
              $mostrar = true;
              if (isset($_GET['eliminado']) && $_GET['eliminado'] == '1' && isset($_GET['id']) && $_GET['id'] == $producto['id']) {
                  $mostrar = false;
              }
              
              if ($mostrar) {
                  echo "<li>";
                  echo "<strong>" . htmlspecialchars($producto['nombre']) . "</strong><br>";
                  echo "Descripción: " . htmlspecialchars($producto['descripcion']) . "<br>";
                  echo "Precio: $" . $producto['precio'] . "<br>";
                  echo "Stock: " . $producto['stock'] . "<br>";
                  echo "Categoría: " . htmlspecialchars($producto['categoria']) . "<br>";
                  echo "<img src='Imagenes/" . htmlspecialchars($producto['imagen']) . "' width='100'><br>";

                  echo "<form action='editarProducto.php' method='get' style='display:inline;'>";
                  echo "<input type='hidden' name='id' value='{$producto['id']}'>";
                  echo "<button type='submit'>Editar</button>";
                  echo "</form> ";

                  echo "<form action='eliminarProducto.php' method='post' onsubmit=\"return confirm('¿Estás seguro de eliminar este producto?');\">";
                  echo "<input type='hidden' name='id' value='" . $producto['id'] . "'>";
                  echo "<button type='submit'>Eliminar Producto</button>";
                  echo "</form>";

                  echo "</li><hr>";
              }
          }
          echo "</ul>";
      } else {
          echo "<p>No hay productos registrados.</p>";
      }
      $conexion->close();
      ?>
    </div>
  </section>

  <section id="lista-usuarios" class="welcome-content">
    <h2>Gestión de Usuarios</h2>
    
    <?php
   
    if (isset($_GET['usuario_agregado'])) {
        if ($_GET['usuario_agregado'] == '1') {
            echo '<div class="mensaje-flotante">Usuario creado correctamente</div>';
        } else {
            echo '<div class="mensaje-flotante" style="background-color: #f44336;">Error al crear el usuario</div>';
        }
        
        echo '<script>
                setTimeout(function() {
                    var mensajes = document.querySelectorAll(".mensaje-flotante");
                    mensajes.forEach(function(mensaje) {
                        mensaje.style.display = "none";
                    });
                }, 3000);
              </script>';
    }
    ?>
    
    <h3>Crear nuevo usuario</h3>
    <form action="agregarUsuarios.php" method="post">
        <input type="text" name="nombre" placeholder="Nombre de usuario" 
               value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required><br><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br><br>
        <select name="rol" required>
            <option value="">Seleccione un rol</option>
            <option value="administrador" <?php echo (isset($_POST['rol']) && $_POST['rol'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
            <option value="cliente" <?php echo (isset($_POST['rol']) && $_POST['rol'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
        </select><br><br>
        <button type="submit">Agregar Usuario</button>
    </form>
    
    <h3>Usuarios existentes</h3>
    <?php
    include 'conexion.php';
    $sql = "SELECT id, nombre, rol FROM usuarios ORDER BY rol, nombre";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        echo "<table class='tabla-usuarios'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Rol</th><th>Acciones</th></tr>";
        while ($usuario = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $usuario['id'] . "</td>";
            echo "<td>" . htmlspecialchars($usuario['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['rol']) . "</td>";
            echo "<td>
                    <form action='editarUsuarios.php' method='get' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $usuario['id'] . "'>
                        <button type='submit'>Editar</button>
                    </form>
                    
                    <form action='eliminarUsuario.php' method='post' onsubmit=\"return confirm('¿Eliminar este usuario?');\">
                        <input type='hidden' name='id' value='" . $usuario['id'] . "'>
                        <button type='submit'>Eliminar</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay usuarios registrados.</p>";
    }
    $conexion->close();
    ?>
 </section>

 <section id="lista-proveedores" class="welcome-content">
  <h2>Gestión de Proveedores</h2>

  <?php
  if (isset($_GET['proveedor_agregado'])) {
      echo '<div class="mensaje-flotante">Proveedor agregado correctamente</div>';
  } elseif (isset($_GET['proveedor_eliminado'])) {
      echo '<div class="mensaje-flotante">Proveedor eliminado correctamente</div>';
  } elseif (isset($_GET['proveedor_actualizado'])) {
      echo '<div class="mensaje-flotante">Proveedor actualizado correctamente</div>';
  }
  ?>

  <h3>Agregar nuevo proveedor</h3>
  <form action="agregarProveedores.php" method="post" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre del proveedor" required><br><br>
    <input type="file" name="logo" accept="image/*"><br><br>
    <button type="submit">Agregar Proveedor</button>
  </form>

  <h3>Proveedores existentes</h3>
  <?php
  include 'conexion.php';
  $sql = "SELECT * FROM proveedores ORDER BY nombre";
  $resultado = $conexion->query($sql);

  if ($resultado->num_rows > 0) {
      echo "<table class='tabla-usuarios'>";
      echo "<tr><th>ID</th><th>Nombre</th><th>Logo</th><th>Acciones</th></tr>";
      while ($proveedor = $resultado->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$proveedor['id']}</td>";
          echo "<td>" . htmlspecialchars($proveedor['nombre']) . "</td>";
          echo "<td><img src='" . htmlspecialchars($proveedor['logo_url']) . "' width='80'></td>";
          echo "<td>
                  <form action='editarProveedores.php' method='get' style='display:inline;'>
                      <input type='hidden' name='id' value='{$proveedor['id']}'>
                      <button type='submit'>Editar</button>
                  </form>
                  <form action='eliminarProveedores.php' method='post' style='display:inline;' onsubmit=\"return confirm('¿Eliminar proveedor?');\">
                      <input type='hidden' name='id' value='{$proveedor['id']}'>
                      <button type='submit'>Eliminar</button>
                  </form>
                </td>";
          echo "</tr>";
      }
      echo "</table>";
  } else {
      echo "<p>No hay proveedores registrados.</p>";
  }
  $conexion->close();
  ?>
</section>

  <footer>
    <p>© 2025 Mi Tienda. Todos los derechos reservados.</p>
  </footer>
</body>
</html>