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

        <button id="refresh-default" type="button" class="btn btn-sm btn-danger bg-gradient mt-2 me-3 fw-bold">
          Atualizar <i class="fa-solid fa-arrows-rotate"></i>
        </button>
      </div>

      <div class="card-body p-3">
        <div style="max-height: 450px; overflow-y: auto;">
          <div data-js="default-history" class="d-flex flex-row-reverse justify-content-end flex-wrap w-100"></div>
        </div>
      </div>
    </div>

    @if(lcfirst($user->plan->name) === 'basic')
    <x-miscellaneous.price-table />
    @else
    <div class="card mb-4">
      <h5 class="card-title px-3 pt-2 text-white">Histórico Avançado</h5>
      <div class="d-none bg-success"></div>
      <div class="d-none bg-secondary"></div>

      <div class="d-flex justify-content-center px-3">
        <span class="text-white px-1"><i class="fa-solid fa-hourglass"></i> - Diferença em minutos</span>
        <span class="text-white px-1"><i class="fa-solid fa-dice"></i> - Diferença entre jogadas</span>
      </div>

      <form action="#" method="GET" class="px-3 mt-3">
        <div class="d-flex justify-content-center">
          <div class="row gx-2 gx-sm-3">
            <div class="col-4 col-sm-3">
              <input id="start_log" type="text" class="form-control form-control-sm" placeholder="Vela inicial">
            </div>

            <div class="col-4 col-sm-3">
              <input id="end_log" type="text" class="form-control form-control-sm" placeholder="Vela final">
            </div>

            <div class="col-2 col-sm-3">
              <input id="limit_log" type="text" class="form-control form-control-sm" placeholder="Limite da consulta">
            </div>

            <div class="d-flex justify-content-end col-2 col-sm-3">
              <button id="refresh-advanced" type="button" class="btn btn-sm btn-danger bg-gradient w-100 fw-bold">
                <span class="d-none d-sm-inline-block">Atualizar</span> <i class="fa-solid fa-arrows-rotate"></i>
              </button>
            </div>
          </div>
        </div>
      </form>

      <div class="card-body p-3">
        <div class="d-flex justify-content-between mb-2 p-1 border border-primary rounded">
          <div class="row">
            <span data-js="total" class="align-self-center text-white">Total de linhas</span>
          </div>
          <div class="row gx-2">
            <div class="col">
              <a id="export-excel" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-file-excel fa-lg"></i>
              </a>
            </div>

            <div class="col">
              <a id="export-csv" class="btn btn-sm btn-outline-success">
                <i class="fa-solid fa-file-csv fa-lg"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
          <table data-js="table-crash" class="table table-striped table-bordered table-hover">
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
    @endif

    @if(lcfirst($user->plan->name) === 'basic')
    <x-modal.default>
      <x-slot name="title">
        Pagameto de Plano
      </x-slot>

      <div class="w-100">
        <h4 class="text-center mb-3">Use o QR Code do Pix para pagar</h4>

        <p>Abra o app em que vai fazer a transferência, escaneie a imagem ou copie o código do QR Code</p>

        <img class="d-block mx-auto img-fluid" src="{{url(mix('assets/images/qr-code-35.png'))}}" alt="qrcode">

        <p class="mt-3 text-center">
          <strong>R$ 35,00</strong>
        </p>

        <div class="text-center">
          <a href="javascript:void(0)" data-clipboard-text="37604520-ec8f-4537-9ced-a43a332462a6">Copiar chave do QR Code</a>

          <p class="d-none text-white mt-2 mb-0">Código copiado!</p>
        </div>
      </div>
    </x-modal.default>
    @endif
  </div>

  @push('scripts')
  <script src="{{url(mix('assets/js/crash/app.js'))}}"></script>
  @endpush
</x-layout.priv>