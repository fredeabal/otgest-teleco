        </div>
      </div>
    </div>
  </div>
  <script src="<?= base_url('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/simplebar/dist/simplebar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/theme/app.init.js') ?>"></script>
  <script src="<?= base_url('assets/js/theme/theme.js') ?>"></script>
  <script src="<?= base_url('assets/js/theme/app.min.js') ?>"></script>
  <script src="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const toastMessage = <?= json_encode(session()->getFlashdata('message') ?? session()->getFlashdata('success')) ?>;
      const toastError = <?= json_encode(session()->getFlashdata('error')) ?>;
      const toastErrors = <?= json_encode(session()->getFlashdata('errors')) ?>;
      
      const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
      
      const systemAlert = Swal.mixin({
        position: 'center',
        showConfirmButton: false,
        buttonsStyling: false,
        timer: 5000,
        timerProgressBar: true,
        background: isDark ? '#0b1114' : '#f8f9fa',
        color: isDark ? '#fff' : '#0b1114',
        showCloseButton: false
      });
      
      if (toastMessage) {
        systemAlert.fire({ icon: 'success', title: '¡Completado!', html: `<div class="text-center">${toastMessage}</div>`, iconColor: '#10B981' });
      }
      if (toastError) {
        systemAlert.fire({ icon: 'error', title: 'Error', html: `<div class="text-center">${toastError}</div>`, iconColor: '#b31b34' });
      }
      if (toastErrors) {
        const errorContent = typeof toastErrors === 'object' && toastErrors !== null
          ? (Array.isArray(toastErrors) ? toastErrors : Object.values(toastErrors)).join('<br>') 
          : toastErrors;
        systemAlert.fire({ icon: 'error', title: 'Error de Validación', html: `<div class="text-center">${errorContent}</div>`, iconColor: '#b31b34' });
      }
    });
  </script>
</body>
</html>
