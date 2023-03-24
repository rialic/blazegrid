<x-layout.priv>
  @section('title', 'Crash')

  @push('css')
  <link href="{{url(mix('assets/css/crash/app.min.css'))}}" rel="stylesheet" />
  @endpush

  <div datajs="maintain-css" class="d-none border-success text-success"></div>

  <div class="container-fluid">
    <div class="d-flex justify-content-between">
      <h4 class="text-white fw-semibold">Crash</h4>

      @if(lcfirst($user->plan->name) === 'premium')
      <div class="align-self-center text-white fw-semibold fs:min-14:max-16">Expira em: {{\Carbon\Carbon::parse($user->expiration_plan_date)->format('d/m/Y')}}</div>
      @endif
    </div>

    <div class="card mb-4">
      <div class="d-flex justify-content-between w-100">
        <div class="card-title px-3 pt-2 text-white fw-semibold fs:min-16:max-20">Histórico Padrão</div>

        <button id="refresh-default" type="button" class="btn btn-sm btn-danger mt-2 me-3 fw-bold">
          Atualizar <i class="fa-solid fa-arrows-rotate"></i>
        </button>
      </div>

      <div class="card-body p-3">
        <div class="d-flex justify-content-between mb-2 p-1 border border-primary rounded">
          <div class="row">
            <span data-js="default-total" class="align-self-center text-white fw-semibold fs:min-14:max-16">Total: </span>
          </div>
        </div>

        <div data-js="default-history" class="d-flex flex-row-reverse w-100 py-2 gap-1 overflow-auto"></div>
      </div>
    </div>

    @if(lcfirst($user->plan->name) === 'basic')
    <x-miscellaneous.price-table />
    @else
    <div class="card mb-4">
      <h5 class="card-title px-3 pt-2 text-white fw-semibold fs:min-16:max-20">Histórico Avançado</h5>
      <div class="d-none bg-success"></div>
      <div class="d-none bg-secondary"></div>

      <div class="d-flex justify-content-center px-3">
        <span class="text-white px-1 fs:min-14:max-16"><i class="fa-solid fa-hourglass"></i> - Diferença em minutos</span>
        <span class="text-white px-1 fs:min-14:max-16"><i class="fa-solid fa-dice"></i> - Diferença entre jogadas</span>
      </div>

      <form action="#" method="GET" class="px-3 mt-3">
        <div class="d-flex justify-content-center">
          <div class="row gx-2 gx-sm-3">
            <div class="col-4 col-sm-3">
              <input id="start_log" type="text" class="form-control form-control-sm" placeholder="Crash inicial">
            </div>

            <div class="col-4 col-sm-3">
              <input id="end_log" type="text" class="form-control form-control-sm" placeholder="Crash final">
            </div>

            <div class="d-flex justify-content-end col-2 col-sm-3">
              <button id="refresh-advanced" type="button" class="btn btn-sm btn-danger w-100 fw-bold">
                <span class="d-none d-sm-inline-block">Atualizar</span> <i class="fa-solid fa-arrows-rotate"></i>
              </button>
            </div>
          </div>
        </div>
      </form>

      <div class="card-body p-3">
        <div class="d-flex justify-content-between mb-2 p-1 border border-2 border-primary rounded">
          <div class="row">
            <span data-js="advanced-total" class="align-self-center text-white fw-semibold fs:min-14:max-16">
              Total encontrado:
            </span>
          </div>
          <div class="row gx-2">
            <div class="col">
              <a id="export-excel" class="btn btn-sm btn-success">
                <i class="fa-solid fa-file-excel fa-lg"></i>
              </a>
            </div>

            <div class="col">
              <a id="export-csv" class="btn btn-sm btn-success">
                <i class="fa-solid fa-file-csv fa-lg"></i>
              </a>
            </div>
          </div>
        </div>

        <div data-js="advanced-history" class="d-flex flex-column vh-50 pe-2 gap-2 overflow-auto"></div>
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

        <img class="d-block mx-auto img-fluid" src="{{url(mix('assets/images/qr-code-40.png'))}}" alt="qrcode">

        <p class="mt-3 text-center">
          <strong>R$ 40,00</strong>
        </p>

        <div class="text-center">
          <a href="javascript:void(0)" data-clipboard-text="37604520-ec8f-4537-9ced-a43a332462a6">Copiar chave do QR Code</a>

          <p class="d-none text-white mt-2 mb-0">Código copiado!</p>
        </div>

        <div class="mt-3 text-white">
          Ao realizar a transferência, por favor, nos mande o comprovante PIX via
          <a href="https://wa.me/5567999521765?text=Olá%20eu%20fiz%20um%20pix%20e%20gostaria%20de%20liberar%20o%20meu%20acesso%20para%20poder%20visualizar%20o%20Histórico%20Avançado%20na%20Blazegrids"
            target="_blank" class="fw-bold text-danger">Whatsapp</a> e nos informe seu <span class="fw-bold text-danger">email</span> para que possamos realizar a ativação de sua conta. A ativação
             de sua conta ocorre em até 24H depois da confirmação da transferência PIX.
        </div>
      </div>
    </x-modal.default>
    @endif

    <div class="pg-loader">
      <div class="pg-loader:circle"></div>
      <div class="pg-loader:circle"></div>
      <div class="pg-loader:circle"></div>
    </div>
  </div>

  @push('scripts')
  <script src="{{url(mix('assets/js/crash/app.min.js'))}}"></script>
  @endpush
</x-layout.priv>