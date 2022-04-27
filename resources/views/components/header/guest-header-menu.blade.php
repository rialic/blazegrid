<header class="header-menu">
  <a href="{{route('public.init')}}" class="d-flex align-items-center px-1 text-decoration-none">
    {{-- <img src="" alt="blazegrid-logo" height="65"> --}}
    <span class="fs-24 fw-bold text-danger">blaze</span>

    &thinsp;

    <div class="position-relative d-flex">
      <h3 class="mb-0 px-1 border border-2 border-danger rounded-start fw-bold text-light zindex-2">G</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light">r</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light zindex-2">i</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light">d</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger rounded-end fw-bold text-light zindex-2">s</h3>

      <div class="logo-grid-line" style="top: 33%;"></div>

      <div class="logo-grid-line" style="top: 66%;"></div>
    </div>
  </a>

  <ul class="nav col col-sm align-items-stretch">
    <li>
      <a data-js="hamburguer" href="javascript:void(0);" class="nav-link d-flex d-sm-none align-items-center h-100 text-white waves-effect waves-light">
        <div class="hamburguer-icon">
          <div class="hamburguer-dash-icon"></div>

          <div class="hamburguer-dash-icon"></div>

          <div class="hamburguer-dash-icon"></div>
        </div>
      </a>
    </li>
  </ul>

  <ul class="nav align-items-stretch">
    @guest
    <x-menu.nav-link href="#" text="Cadastro" classIcon="" />

    <x-menu.nav-link href="#" text="Entrar" classIcon="" />
    @endguest
  </ul>
</header>