<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Tasa Dolar | Mister Bolsa C.A</title>
   <!-- <link rel="stylesheet" href="assets/css/for.css" /> -->
</head>

<body>
 
  <?php require_once 'vista/complementos/nav2.php'; ?>
  <?php require_once 'vista/complementos/sidebar2.php'; ?>
 
  <div class="row">
    <div class="d-sm-flex align-items-center justify-content-between mb-5">
        <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-money-bill-trend-up me-2" style="color: #E63946;"></i>
          Configuracion del Tasa del Dolar</h1>
        
    </div>
  </div>
  
  <div class="transaction-list shadow-sm border-0 DivOscuro1">
  

  <div class="conteiner">
    <div class="row">
      <div class="col">
            <div class="card DivOscuro2">
              <div class="card-header text-dark TextOscuro">
                <i class="fa-solid fa-floppy-disk me-2"></i> Tasa del Dolar BCV - Guardada
              </div>
              <div class="card-body">
                <h5 class="card-title TextOscuroTitel">Tasa del Dolar (Guardada) </h5>
                  <?php foreach ($consulta as $dolar){  ?>
                <h3 class="text-dark TextOscuro"><b> <?php echo $dolar['precios']?> Bs</b></h3>
                  <?php } ?>
                <p class="card-text TextOscuro">Actualmente estás es la tasa de cambio de USD a Bolívares (Bs) guardada en nuestra base de datos.
                    puedes modificarla manualmente en cualquier momento según tu preferencia o la tasa vigente.
                </p>

                <form action="?pagina=venta" id="dolarform" autocomplete="off">

                  <label class="form-label text-dark TextOscuro"> Modificarla manualmente la tasa del $ a Bs</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control text-dark" placeholder="0.00" id="inputtasadolar" name="precios">
                    <button class="btn btn-success" type="button" id="actualizar">
                      <i class="fa-solid fa-money-bill-transfer me-2"></i>Actualizar Tasa</button>
                  </div>
                  <span id="textotasadolar"  class="text-error mt-0"></span> 
                  <input type="hidden" name="id_dolar" value="1">

                  
                </form>

              </div>
            </div>
      </div>

      <div class="col">
        <div class="card DivOscuro2">
          <div class="card-header text-dark TextOscuro">
           <i class="fa-solid fa-wifi me-2"></i> Tasa del Dolar BCV - Internet
          </div>
          <div class="card-body TextOscuro">
            <h5 class="card-title TextOscuroTitel">Tasa del Dolar (Actual - Via Internet) </h5>
            <h3 class="text-dark TextOscuro"><b id="inputdolar"></b></h3>
            <p class="card-text ">
              Estás utilizando la tasa de cambio USD a Bs obtenida automáticamente desde internet.
              Si lo prefieres, puedes sincronizar esta tasa y actualizar la que está guardada en la base de datos.
            </p>
         <form action="?pagina=tasa" method="POST" autocomplete="off" id="formdolar">
          <input type="hidden" name="id_dolar" value="1">
          <input type="hidden" name="precios" id="preciobcv">
           <button  class="btn btn-primary" id="sincronizar" type="button"> <i class="fa-solid fa-rotate me-2"></i> Sincronizar y actualizar</button>
        </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  


  </div>

        
   
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/modulotasa.js"></script>
  <?php require_once 'vista/complementos/footer2.php'; ?>  
 
</body>
</html>