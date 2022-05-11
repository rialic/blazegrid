<x-layout.priv>
  @section('title', 'Crash')

  @push('css')
  <link href="{{url(mix('assets/css/crash/app.css'))}}" rel="stylesheet" />
  @endpush

  <div class="container-fluid">
    <h4 class="text-white">Crash</h4>

    <div class="card mb-4">
      <div class="d-flex justify-content-between w-100">
        <h5 class="card-title px-3 pt-2 text-white">Histórico Padrão</h5>

        <button id="refresh-default" type="button" class="btn btn-danger btn-sm mt-2 me-3 fw-bold">
          Atualizar <i class="fa-solid fa-arrows-rotate"></i>
        </button>
      </div>

      <div class="card-body p-3">
        <div style="max-height: 450px; overflow-y: auto;">
          <div data-js="default-history" class="d-flex flex-wrap w-100"></div>
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <h5 class="card-title px-3 pt-2 text-white">Histórico Avançado</h5>

      <div class="d-flex justify-content-center px-3">
        <span class="text-white px-1"><i class="fa-solid fa-hourglass"></i> - Diferença em minutos</span>
        <span class="text-white px-1"><i class="fa-solid fa-dice"></i> - Diferença entre jogadas</span>
      </div>

      <form action="#" method="GET" class="px-3 mt-3">
        <div class="d-flex justify-content-center">
          <div class="row gx-2 gx-sm-3">
            <div class="col-4 col-sm-3">
              <input id="start_log" type="text" class="form-control form-control-sm">
            </div>

            <div class="col-4 col-sm-3">
              <input id="end_log" type="text" class="form-control form-control-sm">
            </div>

            <div class="col-2 col-sm-3">
              <input id="limit_log" type="text" class="form-control form-control-sm">
            </div>

            <div class="d-flex justify-content-end col-2 col-sm-3">
              <button id="refresh-advanced" type="button" class="btn btn-danger btn-sm w-100 fw-bold">
                <span class="d-none d-sm-inline-block">Atualizar</span> <i class="fa-solid fa-arrows-rotate"></i>
              </button>
            </div>
          </div>
        </div>
      </form>

      <div class="card-body p-3">
        <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Crash</th>
                <th class="text-center"><i class="fa-solid fa-clock"></i></th>
                <th class="text-center"><i class="fa-solid fa-hourglass"></i></th>
                <th class="text-center"><i class="fa-solid fa-dice"></i></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="{{url(mix('assets/js/crash/app.js'))}}"></script>
  @endpush
</x-layout.priv>