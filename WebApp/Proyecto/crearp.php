<?php

include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'La propiedad ' . escapar($_POST['nombre']) . ' ha sido agregada con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $propiedad = [
      "nombre"   => $_POST['nombre'],
      "ciudad" => $_POST['ciudad'],
      "tipo"    => $_POST['tipo'],
      "direccion" => $_POST['direccion'],
      "metros"    => $_POST['metros'],
      "precio"     => $_POST['precio'],
    ];

    $consultaSQL = "INSERT INTO propiedades (nombre, ciudad, tipo, direccion, metros, precio)";
    $consultaSQL .= "values (:" . implode(", :", array_keys($propiedad)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($propiedad);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
?>

<?php include 'templates/header.php'; ?>

<?php
if (isset($resultado)) {
  ?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-4">Registrar una nueva propiedad</h2>
      <hr>
      <form method="post">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" id="nombre" class="form-control">
        </div>
        <div class="form-group">
          <label for="ciudad">Ciudad</label>
          <input type="text" name="ciudad" id="ciudad" class="form-control">
        </div>
        <div class="form-group">
          <label for="tipo">Tipo de Propiedad</label>
          <input type="text" name="tipo" id="tipo" class="form-control">
        </div>
        <div class="form-group">
          <label for="direccion">Direccion</label>
          <input type="text" name="direccion" id="direccion" class="form-control">
        </div>
        <div class="form-group">
          <label for="metros">Metros</label>
          <input type="text" name="metros" id="metros" class="form-control">
        </div>
        <div class="form-group">
          <label for="precio">Precio</label>
          <input type="text" name="precio" id="precio" class="form-control">
        </div>
        <div class="form-group">
          <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
          <input type="submit" name="submit" class="btn btn-primary" value="Crear">
          <a class="btn btn-primary" href="administrarp.php">Volver</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>