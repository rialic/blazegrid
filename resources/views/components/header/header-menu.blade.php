<header class="header-menu justify-content-between">
  <a href="{{route('public.init')}}" class="d-flex align-items-center px-1 text-decoration-none">
    <span class="fs-24 fw-bold text-danger">blaze</span>

    &thinsp;

    <div class="position-relative d-flex">
      <h3 class="mb-0 px-1 border border-2 border-danger rounded-start fw-bold text-light zindex-2">G</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light">r</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light zindex-2">i</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light zindex-2">d</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger rounded-end fw-bold text-light">s</h3>

      <div class="logo-grid-line" style="top: 33%;"></div>

      <div class="logo-grid-line" style="top: 66%;"></div>
    </div>
  </a>

  @guest
  <ul class="nav align-items-stretch">
    <x-menu.nav-link href="{{route('public.login')}}" text="Cadastre-se" />
    <x-menu.nav-link href="{{route('public.login')}}" text="Entrar" />
  </ul>
  @endguest

  @auth
  <ul class="nav align-items-stretch">
    <x-menu.nav-link href="javascript:void(0);" text="Sair" />
  </ul>
  @endauth
</header>