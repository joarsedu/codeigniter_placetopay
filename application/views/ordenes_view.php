
    <div class="py-5 text-center">
      <h2>Ordenes</h2>
    </div>

    <div class="row">
      <div class="col-md-8 offset-md-2">
        <ul class="list-group mb-3">

          <?php
          foreach($ordenes AS $orden) {
          ?>

          <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
              <h6 class="my-0"><?=$orden->nombre?></h6>
              <small class="text-muted"><?=$orden->precio?></small>
            </div>
            <span class="text-muted">
              <?php
              if($orden->status == "CREATED") {
                ?>
                <a href=<?php echo "/orden/comprobar/".$orden->orden_id;?> class="btn btn-lg btn-block btn-primary">Comprobar</a>
                <?php
              } elseif($orden->status == "PAYED") {
                echo "PAGADA";
              } elseif($orden->status == "REJECTED") {
                ?>
                <a href=<?php echo "/orden/nueva/".$orden->producto_id;?> class="btn btn-lg btn-block btn-primary">Reintentar</a>
                <?php
              }
              ?>
            </span>
          </li>

          <?php
          }
          ?>
        </ul>
        <a href="<?php echo site_url("/");?>" class="btn btn-lg btn-block btn-primary">Volver</a>
      </div>
    </div>

