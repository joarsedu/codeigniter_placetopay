<div class="py-5 text-center">
  <h2>Resumen</h2>
</div>

<div class="row">
  <div class="col-md-4 order-md-2 mb-4">
    <div class="card-deck mb-3 text-center">
      <div class="card mb-4 shadow-sm">
        <div class="card-header">
          <h4 class="my-0 font-weight-normal"><?=$producto->nombre?></h4>
        </div>
        <div class="card-body">
          <h1 class="card-title pricing-card-title"><?=$producto->precio?> <small class="text-muted">/ mes</small></h1>
          <ul class="list-unstyled mt-3 mb-4">
            <?=$producto->descripcion?>
          </ul>
        </div>
      </div>
    </div>


  </div>
  <div class="col-md-8 order-md-1">
        <h4 class="mb-3">Datos</h4>
        <form action="/orden/guardar" method="post" class="needs-validation" novalidate>
          <input type="hidden" id="producto_id" name="producto_id" value="<?=$producto->id?>">

          <div class="mb-3">
            <label for="address">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre Completo" required>
          </div>

          <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com">
          </div>

          <div class="mb-3">
            <label for="address2">Celular</label>
            <input type="text" class="form-control" id="celular" name="celular" placeholder="Celular">
          </div>

          <hr class="mb-4">

          <h4 class="mb-3">Pago</h4>

          <div class="d-block my-3">
            <div class="custom-control custom-radio">
              <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked required>
              <label class="custom-control-label" for="credit">Place To Pay</label>
            </div>
          </div>

          <hr class="mb-4">
          <button class="btn btn-primary btn-lg btn-block" type="submit">Continuar</button>
        </form>
      </div>
</div>