<x-layout.guest>
  @section('title', 'Login')

  @push('css')
  <link href="{{url(mix('assets/css/login/app.min.css'))}}" rel="stylesheet" />
  @endpush

  <div class="d-flex flex-col justify-content-center align-items-center h-100">
    <div class="card mx-2">
      <div class="card-body p-4">
        <div class="text-center w-75 m-auto">
          <div class="d-flex justify-content-center">
            <span class="fs-22 fw-bold text-white">blaze</span>

            &thinsp;

            <div class="position-relative d-flex">
              <h4 class="mb-0 px-1 border border-2 border-danger rounded-start fw-bold text-light zindex-2">G</h4>

              <h4 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light">r</h4>

              <h4 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light zindex-2">i</h4>

              <h4 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light zindex-2">d</h4>

              <h4 class="mb-0 px-1 border border-start-0 border-2 border-danger rounded-end fw-bold text-light">s</h4>

              <div class="first-line-logo"></div>

              <div class="second-line-logo"></div>
            </div>
          </div>

          <p class="text-muted mb-4 mt-3"> Acesse nosso site com as opções de login abaixo.</p>
        </div>

        <div class="text-center">
          <p class="fs-18 mt-3 mb-4 text-muted">Entrar com</p>

          <a href="{{route('guest.socialite.login')}}" class="btn btn-sm btn-danger w-50" type="button">
            <div class="d-flex justify-content-center">
              <span class="me-1 fw-bold">Gmail</span>

              <i class="fa-brands fa-google fa-lg ms-1 align-self-center"></i>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</x-layout.guest>