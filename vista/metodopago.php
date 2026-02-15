<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Metodo Pago | Mister Bolsa C.A</title>
    <!-- <link rel="stylesheet" href="assets/css/for.css" /> -->
</head>

<body>
 
  <?php require_once 'complementos/nav2.php'; ?>
  <?php require_once 'complementos/sidebar2.php'; ?>
 
  <div class="row">
      <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-credit-card me-2" style="color: #E63946;"></i>
        Metódo Pago</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-primary" id="Teayuda">
                  <i class="fa-solid fa-circle-question me-2"></i>
                  Ayuda
            </button>
      
      </div>
  </div>
  
  <div class="transaction-list shadow-sm border-0 DivOscuro1 TA1">
    <div class="table-responsive TextOscuro">

      <table class="table table-hover" style="width:100%">
        <thead class="table-color">
          <tr>
            <th>#</th>
            <th>NOMBRE</th>
            <th>ESTATUS</th>
            <th>ACCION</th>
          </tr>
        </thead>

        <tbody id="tablametodopago">
        
        
        </tbody>
      </table>

    </div>
  </div>


        

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  
  <script src="assets/js/metodopago.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 
  
 <script>
    const driver = window.driver.js.driver;
        const driverObj = driver({
            showProgress: true,
            // Textos en ESPAÑOL
            nextBtnText: 'Siguiente →',
            prevBtnText: '← Atrás',
            doneBtnText: '¡Entendido!',
            progressText: 'Paso {{current}} de {{total}}',
            steps: [
                { 
                    // Usamos el punto (.) para referirnos a la clase
                    element: '.TA1',  popover: { title: 'Tabla de registro', 
                        description: 'Lista de todas las formas en que tus clientes pueden pagarte', side: "top" 
                    } 
                },
                { 
                    element: '.TA2', popover: { title: 'Desactivar/Activar', 
                        description: 'Al desactivar, quitas este método de la pantalla de cobro para que no aparezca al vender, y al activarlo vuelve aparacer en la pantalla de cobro', side: "top" 
                    } 
                }
                
            ]
        });

document.querySelector('#Teayuda').addEventListener('click', () => {
  driverObj.drive();
});  
</script>
</body>
</html>