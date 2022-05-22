<x-layout.guest>
  @section('title', 'Blaze Grid')

  @push('css')
  <link href="{{url(mix('assets/css/home/app.css'))}}" rel="stylesheet" />
  @endpush

  <section class="mt-4 border-bottom border-primary">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="text-center my-4">
            <h3 class="mb-3 text-light">Histórico de operações detalhados para Crash e Double</h3>

            <p class="text-white-50">Na blazeGrids você encontra históricos para Crash e Double bem mais detalhados para você definir sua estratégia de operações.</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-4 col-sm-6">
          <div class="text-center mt-3 p-3">
            <div class="mb-3">
              <div class="d-inline-block irregular-circle-alt circle--primary">
                <i class="fas fa-filter text-danger fa-2xl"></i>
              </div>
            </div>

            <h4 class="text-light">Filtro de velas para Crash</h4>

            <p class="text-white-50">
              Em nossa tabela de histórico do Crash você poderá filtrar pelas velas que deseja para ter uma melhor visualização de como o gráfico do Crash opera.
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="text-center mt-3 p-3">
            <div class="mb-3">
              <div class="d-inline-block irregular-circle circle--primary">
                <i class="fas fa-list-ol text-danger fa-2xl"></i>
              </div>
            </div>

            <h4 class="text-light">Intervalo entre operações</h4>

            <p class="text-white-50">
              Com a blazeGrids é possível conferir a quantidade de jogadas entre as velas que você filtrar. Afinal de quantas em quantas vezes o gráfico Crash apresenta uma
              vela de 10.0X?
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="text-center mt-3 p-3">
            <div class="mb-3">
              <div class="d-inline-block irregular-circle circle--primary">
                <i class="fas fa-clock-rotate-left text-danger fa-2xl"></i>
              </div>
            </div>

            <h4 class="text-light">Intervalo de tempo</h4>

            <p class="text-white-50">
              Ter um histórico com o intervalo de tempo entre as velas mais desejadas é uma ótima estratégia para você fazer sua próxima operação.
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="text-center mt-3 p-3">
            <div class="mb-3">
              <div class="d-inline-block irregular-circle-alt circle--primary">
                <i class="fas fa-clock text-danger fa-2xl"></i>
              </div>
            </div>

            <h4 class="text-light">Data</h4>

            <p class="text-white-50">
              Nossos históricos mostram a data e hora das operações de acordo com o seu local.
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="text-center mt-3 p-3">
            <div class="mb-3">
              <div class="d-inline-block irregular-circle circle--primary">
                <i class="fas fa-file-excel text-danger fa-2xl"></i>
              </div>
            </div>

            <h4 class="text-light">Excel</h4>

            <p class="text-white-50">
              Baixe os resultados para excel e manipule-os como desejar.
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="text-center mt-3 p-3">
            <div class="mb-3">
              <div class="d-inline-block irregular-circle circle--primary">
                <i class="fas fa-lightbulb text-danger fa-2xl"></i>
              </div>
            </div>

            <h4 class="text-light">Recursos Novos</h4>

            <p class="text-muted">
              Nosso site acabou de nascer, e se for bem aceito pela comunidade, traremos recursos novos com novas análises para o apostador.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  @if(lcfirst(optional(optional($user)->plan)->name) === 'basic' || empty($user))
  <section class="mt-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="text-center mb-4">
            <h3 class="font-22 mb-3">Nossos planos</h3>
          </div>
        </div>
      </div>

      <x-miscellaneous.price-table />
    </div>
  </section>
  @endif

  @auth
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

      <div class="mt-3 text-white">
        Ao realizar a transferência, por favor, nos mande o comprovante PIX via <a href="javascript:void(0)" class="fw-bold text-danger">Whatsapp</a> e nos informe seu
        <span class="fw-bold text-danger">email</span> para que possamos realizar a ativação de sua conta. A ativação de sua conta ocorre em até 24H depois da confirmação
        da transferência PIX.
      </div>
    </div>
  </x-modal.default>
  @endif
  @endauth

  @push('scripts')
  <script src="{{url(mix('assets/js/home/app.js'))}}"></script>
  @endpush
</x-layout.guest>