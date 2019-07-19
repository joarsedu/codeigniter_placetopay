<div class="py-5 text-center">
  <h2>Productos</h2>
  <p class="lead">Seleccione el producto a comprar.</p>
</div>

<div class="card-deck mb-3 text-center">
  <?php
  foreach($productos AS $producto) {
  ?>
  <div class="card mb-4 shadow-sm">
    <div class="card-header">
      <h4 class="my-0 font-weight-normal"><?=$producto->nombre?></h4>
    </div>
    <div class="card-body">
      <h1 class="card-title pricing-card-title"><?=$producto->precio?> <small class="text-muted">/ mes</small></h1>
      <ul class="list-unstyled mt-3 mb-4">
        <?=$producto->descripcion?>
      </ul>
      <a href=<?php echo "/orden/nueva/".$producto->id;?> class="btn btn-lg btn-block btn-primary">Comprar</a>
    </div>
  </div>

  <?php
  }
  ?>


</div>
<div class="row">
  <div class="col-md-4 offset-md-4">
    <a href="<?php echo site_url("orden/all");?>" class="btn btn-lg btn-block btn-primary">Ver Todas las Ordenes</a>
  </div>
</div>