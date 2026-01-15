            <footer>
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">© 2026 Desarollador Daniel E Sanchez | Mister Bolsa C.A - Tienen los derechos </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                        <a href="#" class="text-decoration-none text-muted me-3">Privacy</a>
                        <a href="#" class="text-decoration-none text-muted">Terms</a>
                    </div>
                </div>
            </footer>
        </div>
    </main>


    <!-- ||||||||||||||||||  MODAL DE CIERRE |||||||||||||||||||||||-->  
<div class="modal fade" id="modalcerrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <div class="modal-body text-center p-4">

            <i class="fa-solid fa-circle-question text-dark mb-4"
              style="font-size: 7rem;"></i>

            <h4 class="fw-bold mb-4">
                ¿Confirmas que deseas cerrar sesión?
            </h4>

            <div class="d-flex justify-content-center gap-3">

                <form action="?pagina=login" method="POST" autocomplete="off">
                    <button type="submit" class="btn btn-success btn-lg px-4" name="cerrar">
                        Sí
                    </button>
                </form>

                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                    No
                </button>
            </div>
        </div>
      
    </div>
  </div>
</div>
  
  <script src="assets/js/datetable/data.js"></script>

  <script src="assets/js/sweetalert2.js"></script>

  <script src="assets/librerias/bootstrap5/js/bootstrap.min.js"></script>
  
  <script src="assets/librerias/bootstrap5/js/bootstrap.bundle.min.js"></script>

  

  <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const hamburgerBtn = document.getElementById('hamburgerBtn');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            hamburgerBtn.classList.toggle('active');
        }

        // Cerrar al cambiar tamaño de pantalla si está abierto
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                hamburgerBtn.classList.remove('active');
            }
        });
    </script>