<!-- =====================================================================
     CABECERA PRINCIPAL (HEADER)
     ===================================================================== -->
<!DOCTYPE html>
<html lang="es" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
<?php 
  $currentUser = auth()->user();
  $avatarUrl = base_url('assets/images/profile/user-default.png');
  if ($currentUser && !empty($currentUser->profile_pic)) {
      $avatarUrl = base_url('uploads/profile/' . $currentUser->profile_pic);
  }
  $displayName = $currentUser ? (!empty($currentUser->name) ? $currentUser->name : $currentUser->username) : 'Usuario';
  $displayEmail = $currentUser ? ($currentUser->getIdentities()[0]->secret ?? '') : '';
  $userTheme = $currentUser->theme ?? 'system';
  $userGroups = $currentUser ? $currentUser->getGroups() : [];
  $displayRole = !empty($userGroups) ? (config('AuthGroups')->groups[$userGroups[0]]['title'] ?? ucfirst($userGroups[0])) : 'Usuario';

  // Fetch pending imputations for the current user
  $pendingImputations = 0;
  if ($currentUser) {
      $orderModel = new \App\Models\OrderModel();
      if ($currentUser->can('orders.view_all')) {
          $pendingImputations = $orderModel->where('ot_imputada', 0)->countAllResults();
      } else {
          $pendingImputations = $orderModel->where('ot_imputada', 0)->where('ot_usr', $currentUser->id)->countAllResults();
      }
  }

  // Fetch pending tasks count for the current user
  $pendingTodosCount = 0;
  if ($currentUser) {
      try {
          $todoModel = new \App\Models\TodoModel();
          $pendingTodosCount = $todoModel->where('todo_usr', $currentUser->id)->where('todo_completed', 0)->countAllResults();
      } catch (\Throwable $e) {
          $pendingTodosCount = 0;
      }
  }
?>
<script>
  // Tema DB > localStorage > preferencia del sistema
  (function() {
    var dbTheme = '<?= $userTheme ?>';
    var theme = 'light';
    
    if (dbTheme === 'dark' || dbTheme === 'light') {
        theme = dbTheme;
        localStorage.setItem('theme', dbTheme);
    } else {
        localStorage.removeItem('theme');
        theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }
    
    document.documentElement.setAttribute('data-bs-theme', theme);
  })();
</script>

<head>
  <!-- Etiquetas meta requeridas y SEO -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="OtGest - Plataforma avanzada de gestión." />
  <meta name="keywords" content="OtGest, Gestión, Órdenes de Trabajo" />
  <meta name="author" content="OtGest" />

  <!-- Icono Favicon -->
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/') ?>images/logos/favicon.png" />

  <!-- CSS Principal -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>css/styles.css?v=<?= filemtime(FCPATH . 'assets/css/styles.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/') ?>css/custom.css?v=<?= filemtime(FCPATH . 'assets/css/custom.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/libs/sweetalert2/dist/sweetalert2.min.css') ?>" />
  
  <!-- Bootstrap Datepicker -->
  <link rel="stylesheet" href="<?= base_url('assets/') ?>libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <style>
    /* Estilos para el Bootstrap Datepicker */
    .datepicker {
        z-index: 9999 !important;
    }
    /* Día de hoy: Borde primario (usando box-shadow para no perder el border-radius), fondo transparente */
    .datepicker table tr td.day.today:not(.active) {
        background-color: transparent !important;
        box-shadow: inset 0 0 0 1px var(--bs-primary) !important;
        border: none !important;
        color: var(--bs-primary) !important;
        border-radius: 6px !important;
    }
    .datepicker table tr td.day.today:not(.active):hover {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
    /* Día seleccionado (orden): Fondo primario sólido */
    .datepicker table tr td.day.active,
    .datepicker table tr td.day.active:hover {
        background-color: var(--bs-primary) !important;
        border: none !important;
        box-shadow: none !important;
        color: #fff !important;
        border-radius: 6px !important;
        text-shadow: none;
    }
  </style>

  <title><?= isset($title) ? $title . ' | OtGest' : 'OtGest | Sistema de Gestión' ?></title>
</head>

<body>

  <div id="main-wrapper">
    <?= view("template/aside") ?>
      <!-- Inicio de la Cabecera -->
      <header class="topbar">
        <div class="with-vertical"><!-- ---------------------------------- -->
          <!-- Inicio de la Cabecera de Layout Vertical -->
          <!-- ---------------------------------- -->
          <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
              <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>

            <ul class="navbar-nav quick-links d-none d-lg-flex align-items-center">
              <!-- Los elementos de menú se han eliminado - solo se mantiene el menú hamburguesa -->
            </ul>

            <div class="d-block d-lg-none py-4">
              <a href="<?= base_url() ?>" class="text-nowrap logo-img">
                <img src="<?= base_url('assets/') ?>images/logos/dark-logo.svg" class="dark-logo" alt="Logo-Dark" />
                <img src="<?= base_url('assets/') ?>images/logos/light-logo.svg" class="light-logo" alt="Logo-light" />
              </a>
            </div>
            <div class="d-flex align-items-center justify-content-end ms-auto" id="navbarNav">
              <div class="d-flex align-items-center justify-content-between">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                  <!-- ------------------------------- -->
                  <!-- end language Dropdown -->
                  <!-- ------------------------------- -->

                  <?php 
                  $totalAlerts = $pendingImputations + $pendingTodosCount;
                  if($totalAlerts > 0): 
                  ?>
                  <li class="nav-item dropdown">
                    <a class="nav-link nav-icon-hover position-relative" href="javascript:void(0)" id="dropNotifications" data-bs-toggle="dropdown" aria-expanded="false" title="Tienes <?= $totalAlerts ?> notificaciones pendientes" data-bs-toggle="tooltip">
                      <i class="ti ti-bell-ringing text-danger fs-6"></i>
                      <span class="badge rounded-pill bg-danger header-notification-badge">
                        <?= $totalAlerts ?>
                      </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up py-3 px-3 notification-dropdown-width" aria-labelledby="dropNotifications">
                      <h6 class="mb-3 fw-semibold">Notificaciones</h6>
                      <div class="message-body">
                        <?php if($pendingImputations > 0): ?>
                        <a href="<?= base_url('orders?imputada=0') ?>" class="d-flex align-items-center gap-3 py-2 text-decoration-none dropdown-item rounded-2">
                          <span class="d-flex align-items-center justify-content-center bg-danger-subtle rounded-circle p-2 text-danger">
                            <i class="ti ti-alert-triangle fs-5"></i>
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-3 fw-semibold">Órdenes sin imputar</h6>
                            <span class="fs-2 d-block text-muted">Tienes <?= $pendingImputations ?> órdenes sin imputar</span>
                          </div>
                        </a>
                        <?php endif; ?>
                        
                        <?php if($pendingTodosCount > 0): ?>
                        <a href="<?= base_url('todos') ?>" class="d-flex align-items-center gap-3 py-2 text-decoration-none dropdown-item rounded-2">
                          <span class="d-flex align-items-center justify-content-center bg-warning-subtle rounded-circle p-2 text-warning">
                            <i class="ti ti-list-check fs-5"></i>
                          </span>
                          <div class="w-100">
                            <h6 class="mb-0 fs-3 fw-semibold">Tareas pendientes</h6>
                            <span class="fs-2 d-block text-muted">Tienes <?= $pendingTodosCount ?> tareas pendientes</span>
                          </div>
                        </a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </li>
                  <?php endif; ?>

                  <!-- ------------------------------- -->
                  <!-- start profile Dropdown -->
                  <!-- ------------------------------- -->
                  <li class="nav-item dropdown">
                    <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                      <div class="d-flex align-items-center">
                        <div class="user-profile-img">
                          <img src="<?= $avatarUrl ?>" class="rounded-circle object-fit-cover" width="35" height="35" alt="Avatar" />
                        </div>
                      </div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                      <div class="profile-dropdown position-relative" data-simplebar>
                        <div class="py-3 px-7 pb-0">
                          <h5 class="mb-0 fs-5 fw-semibold">Mi Perfil</h5>
                        </div>
                        <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                          <img src="<?= $avatarUrl ?>" class="rounded-circle object-fit-cover" width="80" height="80" alt="Avatar" />
                          <div class="ms-3">
                            <h5 class="mb-1 fs-3"><?= esc($displayName) ?></h5>
                            <span class="mb-1 d-block text-muted"><?= esc($displayRole) ?></span>
                            <p class="mb-0 d-flex align-items-center gap-2">
                              <i class="ti ti-mail fs-4"></i> <?= esc($displayEmail) ?>
                            </p>
                          </div>
                        </div>
                        <div class="message-body">
                          <a href="<?= base_url('profile') ?>" class="py-8 px-7 mt-8 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-light-primary rounded p-2 text-primary">
                              <i class="ti ti-user-circle fs-7"></i>
                            </span>
                            <div class="w-100 ps-3">
                              <h6 class="mb-1 fs-3 fw-semibold lh-base">Mi Perfil</h6>
                              <span class="fs-2 d-block text-body-secondary">Ajustes de cuenta</span>
                            </div>
                          </a>
                        </div>
                        <div class="d-grid py-4 px-7 pt-8">
                          <a href="<?= url_to('logout') ?>" class="btn btn-outline-primary w-100">Cerrar Sesión</a>
                          <div class="text-center mt-4 fs-2 text-muted">
                            <small>OtGest - 2.0</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <!-- ------------------------------- -->
                  <!-- end profile Dropdown -->
                  <!-- ------------------------------- -->
                </ul>
              </div>
            </div>
          </nav>
          <!-- ---------------------------------- -->
          <!-- End Vertical Layout Header -->
          <!-- ---------------------------------- -->

        </div>
      </header>
      <!-- Fin de la Cabecera -->
      <div class="body-wrapper">
        <div class="container-fluid">
