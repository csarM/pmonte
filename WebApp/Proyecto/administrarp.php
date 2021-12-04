<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['ciudad'])) {
    $consultaSQL = "SELECT * FROM propiedades WHERE ciudad LIKE '%" . $_POST['ciudad'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM propiedades";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $propiedades = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['ciudad']) ? 'Lista de propiedades (' . $_POST['ciudad'] . ')' : 'Lista de propiedades';
?>

<?php include "templates/header.php"; ?>

<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
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
      <a href="crearp.php"  class="btn btn-primary mt-4">Nueva propiedad</a>
      <a href="index.html"class="btn btn-primary mt-4">Volver</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="ciudad" name="ciudad" placeholder="Buscar por ciudad" class="form-control">
        </div>
        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
        <button type="submit" name="submit" class="btn btn-primary">Ver</button>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3"><?= $titulo ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Ciudad</th>
            <th>Tipo</th>
            <th>Direccion</th>
            <th>Metros</th>
            <th>Precio</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($propiedades && $sentencia->rowCount() > 0) {
            foreach ($propiedades as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id"]); ?></td>
                <td><?php echo escapar($fila["nombre"]); ?></td>
                <td><?php echo escapar($fila["ciudad"]); ?></td>
                <td><?php echo escapar($fila["tipo"]); ?></td>
                <td><?php echo escapar($fila["direccion"]); ?></td>
                <td><?php echo escapar($fila["metros"]); ?></td>
                <td><?php echo escapar($fila["precio"]); ?></td>
                <td>
                  <a href="<?= 'borrarp.php?id=' . escapar($fila["id"]) ?>">Borrar</a>
                  <a href="<?= 'editarp.php?id=' . escapar($fila["id"]) ?>">Editar</a>
                </td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>
</div>a

<?php include "templates/footer.php"; ?>