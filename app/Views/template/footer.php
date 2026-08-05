        </div>
      </div>
    </div>
  <!-- =====================================================================
       PIE DE PÁGINA Y SCRIPTS GLOBALES (FOOTER)
       ===================================================================== -->
  <!-- Importar Archivos Js -->
  <script src="<?= base_url('assets/') ?>libs/jquery/dist/jquery.min.js"></script>
  <script src="<?= base_url('assets/') ?>libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('assets/') ?>libs/simplebar/dist/simplebar.min.js"></script>
  <script src="<?= base_url('assets/') ?>js/theme/app.init.js"></script>
  <script src="<?= base_url('assets/') ?>js/theme/theme.js"></script>
  <script src="<?= base_url('assets/') ?>js/theme/app.min.js"></script>
  <script src="<?= base_url('assets/') ?>js/theme/sidebarmenu.js"></script>
  <!-- Bootstrap Datepicker JS -->
  <script src="<?= base_url('assets/') ?>libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <script src="<?= base_url('assets/') ?>libs/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js"></script>
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Bootstrap Datepicker (Modernize template)
        if ($.fn.datepicker) {
            $('.mydatepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'es'
            });
        }

        // Auto-resize para textareas
        const tx = document.getElementsByTagName("textarea");
        for (let i = 0; i < tx.length; i++) {
            tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
            tx[i].addEventListener("input", function() {
                this.style.height = "auto";
                this.style.height = (this.scrollHeight) + "px";
            }, false);
        }
    });
  </script>

  <!-- solar icons -->
  <script src="<?= base_url('assets/') ?>libs/iconify/iconify-icon.min.js"></script>

  <!-- highlight.js (code view) -->
  <script src="<?= base_url('assets/') ?>js/highlights/highlight.min.js"></script>
  <script>
  hljs.initHighlightingOnLoad();


  document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
    codeBlock.textContent = codeBlock.innerHTML;
  });
</script>

  <!-- Persistencia de tema en localStorage -->
  <script>
    document.querySelectorAll('.dark-layout').forEach(function(el) {
      el.addEventListener('click', function() {
        localStorage.setItem('theme', 'dark');
      });
    });
    document.querySelectorAll('.light-layout').forEach(function(el) {
      el.addEventListener('click', function() {
        localStorage.setItem('theme', 'light');
      });
    });

    // Auto-dismiss standard alerts
    setTimeout(function() {
      var alerts = document.querySelectorAll('.alert-dismissible');
      alerts.forEach(function(alertElement) {
        if (typeof bootstrap !== 'undefined') {
          var bsAlert = new bootstrap.Alert(alertElement);
          bsAlert.close();
        } else {
          alertElement.style.display = 'none';
        }
      });
    }, 3000);

    // SweetAlert2 global submit interceptor (for forms)
    document.addEventListener("submit", function(e) {
      let form = e.target;
      if (form.hasAttribute("data-confirm") && !form.dataset.confirmed) {
        e.preventDefault();
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        Swal.fire({
          title: '¿Confirmas esta acción?',
          text: form.getAttribute("data-confirm"),
          icon: 'warning',
          background: isDark ? '#0b1114' : '#f8f9fa',
          color: isDark ? '#ffffff' : '#0b1114',
          iconColor: '#F38020',
          showCancelButton: true,
          reverseButtons: true,
          customClass: {
            confirmButton: 'btn btn-primary ms-2',
            cancelButton: 'btn btn-outline-primary'
          },
          buttonsStyling: false,
          confirmButtonText: 'Sí, confirmar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            form.dataset.confirmed = "true";
            form.submit();
          }
        });
      }
    });

    // SweetAlert2 global click interceptor (for links/buttons con data-confirm)
    document.addEventListener("click", function(e) {
      let confirmEl = e.target.closest("[data-confirm]");
      if (confirmEl) {
        let form = confirmEl.closest("form");
        
        if (!form || !form.hasAttribute("data-confirm")) {
          e.preventDefault();
          const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
          Swal.fire({
            title: '¿Confirmas esta acción?',
            text: confirmEl.getAttribute("data-confirm"),
            icon: 'warning',
            background: isDark ? '#0b1114' : '#f8f9fa',
            color: isDark ? '#ffffff' : '#0b1114',
            iconColor: '#F38020',
            showCancelButton: true,
            reverseButtons: true,
            customClass: {
              confirmButton: 'btn btn-primary ms-2',
              cancelButton: 'btn btn-outline-primary'
            },
            buttonsStyling: false,
            confirmButtonText: 'Sí, confirmar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              if (form) {
                form.dataset.confirmed = "true";
                form.submit();
              } else if (confirmEl.tagName === 'A') {
                window.location.href = confirmEl.href;
              }
            }
          });
        }
      }
    });

    // Si el sistema cambia de tema y el usuario no ha elegido manualmente, seguir al sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
      if (!localStorage.getItem('theme')) {
        var theme = e.matches ? 'dark' : 'light';
        document.documentElement.setAttribute('data-bs-theme', theme);
      }
    });

    document.addEventListener("DOMContentLoaded", function() {
      const toastMessage = <?= json_encode(session()->getFlashdata('message') ?? session()->getFlashdata('success')) ?>;
      const toastError = <?= json_encode(session()->getFlashdata('error')) ?>;
      const toastErrors = <?= json_encode(session()->getFlashdata('errors')) ?>;
      
      const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
      
      window.systemAlert = Swal.mixin({
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
        window.systemAlert.fire({ icon: 'success', title: '¡Completado!', html: `<div class="text-center">${toastMessage}</div>`, iconColor: '#10B981' });
      }
      if (toastError) {
        window.systemAlert.fire({ icon: 'error', title: 'Error', html: `<div class="text-center">${toastError}</div>`, iconColor: '#b31b34' });
      }
      if (toastErrors) {
        const errorContent = typeof toastErrors === 'object' && toastErrors !== null
          ? (Array.isArray(toastErrors) ? toastErrors : Object.values(toastErrors)).join('<br>') 
          : toastErrors;
        window.systemAlert.fire({ icon: 'error', title: 'Error de Validación', html: `<div class="text-center">${errorContent}</div>`, iconColor: '#b31b34' });
      }
    });
  </script>
  <script src="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.js') ?>"></script>

  <!-- Modal genérico para escáner QR / Código de barras -->
  <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content overflow-hidden border-0 shadow-lg">
        <div class="modal-header border-bottom border-dark">
          <h5 class="modal-title" id="scannerModalLabel"><i class="ti ti-barcode text-primary"></i> Escanear Código</h5>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-primary" id="switchCameraBtn" title="Cambiar Cámara">
                <i class="ti ti-camera-rotate"></i>
            </button>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeScannerBtn"></button>
          </div>
        </div>
        <div class="modal-body p-0 bg-dark" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
          <div id="qr-reader" style="width:100%; border:none; background: #000;">
              <!-- El feed de la cámara aparecerá aquí -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/') ?>libs/html5-qrcode/html5-qrcode.min.js"></script>
  <script>
    let html5QrCode = null;
    let isScanning = false;
    let currentFacingMode = "environment";
    let qrSuccessCb = null;

    function openScanner(targetElementId) {
        const modalEl = document.getElementById('scannerModal');
        const switchCamBtn = document.getElementById('switchCameraBtn');
        // Reset loader UI
        document.getElementById('qr-reader').innerHTML = '<div class="p-5 text-center text-muted"><div class="spinner-border text-primary mb-3" role="status"></div><p>Iniciando cámara...</p></div>';
        
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        const stopScanner = (andHide = false) => {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    html5QrCode.clear();
                    if(andHide) modal.hide();
                }).catch(e => {
                    console.error("Error deteniendo escáner:", e);
                    if(andHide) modal.hide();
                });
            } else if (andHide) {
                modal.hide();
            }
        };

        qrSuccessCb = (decodedText, decodedResult) => {
            const targetEl = document.getElementById(targetElementId);
            if (targetEl) {
                if (targetEl.value.trim() !== '') {
                    targetEl.value += ' ' + decodedText;
                } else {
                    targetEl.value = decodedText;
                }
                if (typeof autoResizeTextarea === 'function') autoResizeTextarea(targetEl);
            }
            
            // Beep
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioCtx.currentTime);
                oscillator.connect(audioCtx.destination);
                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.1);
            } catch (e) {}

            stopScanner(true);
        };

        const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

        const startCamera = (facingMode) => {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    html5QrCode.clear();
                    startActualScanner(facingMode);
                }).catch(e => console.error(e));
            } else {
                startActualScanner(facingMode);
            }
        };

        const startActualScanner = (facingMode) => {
            if (!html5QrCode) html5QrCode = new Html5Qrcode("qr-reader");

            html5QrCode.start({ facingMode: facingMode }, config, qrSuccessCb)
            .then(() => {
                isScanning = true;
                currentFacingMode = facingMode;
                // Auto-detectar la cámara física real que se está usando
                const videoEl = document.querySelector('#qr-reader video');
                if (videoEl && videoEl.srcObject) {
                    const tracks = videoEl.srcObject.getVideoTracks();
                    if (tracks.length > 0) {
                        const settings = tracks[0].getSettings();
                        const label = tracks[0].label.toLowerCase();
                        
                        // Determinar si es frontal leyendo los ajustes del hardware o el nombre
                        const isFrontal = (settings.facingMode === 'user') || 
                                          label.includes('front') || 
                                          label.includes('frontal') || 
                                          label.includes('user') || 
                                          label.includes('webcam') || 
                                          label.includes('facetime');
                                          
                        if (isFrontal) {
                            videoEl.style.transform = "scaleX(-1)";
                        } else {
                            videoEl.style.transform = "none";
                        }
                    }
                }
            })
            .catch((err) => {
                console.error("Error iniciando cámara:", err);
                document.getElementById('qr-reader').innerHTML = `<div class="p-5 text-center text-danger"><i class="ti ti-alert-circle fs-1 mb-3 d-block"></i><p>No se pudo acceder a la cámara. Asegúrate de dar permisos.</p></div>`;
            });
        };
        
        switchCamBtn.onclick = () => {
            const newMode = currentFacingMode === "environment" ? "user" : "environment";
            startCamera(newMode);
        };

        setTimeout(() => {
            currentFacingMode = "environment";
            startCamera(currentFacingMode);
        }, 300);
        
        modalEl.addEventListener('hidden.bs.modal', function () {
            stopScanner(false);
        }, { once: true });
    }
  </script>
</body>

</html>