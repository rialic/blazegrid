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

            <h4 class="text-light">Temporizador</h4>

            <p class="text-white-50">
              Temos um temporizador para que você saiba há quanto tempo está esperando pelo seu próximo multiplicador. O nosso temporiazador funciona de acordo com o filtro de
              velas
              informado.
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

  <section class="mt-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="text-center mb-4">
            <h3 class="font-22 mb-3">Nossos planos</h3>
          </div>
        </div>
      </div>

      <div class="row justify-content-center mb-4 px-2">
        <div class="col-lg-3 p-3 border border-danger rounded">
          <div class="text-center mb-4">
            <i class="fas fa-certificate fa-2xl text-muted"></i>
          </div>

          <h5 class="text-center fs-18 mb-3">Básico</h5>

          <p class="fs-15 text-center text-danger m-0 p-0">* No momento só estamos aceitando pix</small>

            <h2 class="pt-2 text-center">
              <sup>
                <span class="fs-18">R$</span>
              </sup>

              <span class="text-secondary">35</span>

              <span class="fs-18">/ mês</span>
            </h2>

            <hr class="my-3">

            <div class="text-muted mb-4 px-2">
              <p><i class="fas fa-check text-white me-2"></i> Histórico Crash</p>

              <p><i class="fas fa-check text-white me-2 me-2"></i> Histórico Double (Em Breve)</p>

              <p><i class="fas fa-check text-white me-2 me-2"></i> Histórico de até 3500 registros</p>

              <p><i class="fas fa-check text-white me-2 me-2"></i> Download em Excel</p>

              <p><i class="fas fa-check text-white me-2 me-2"></i> 30 dias de acesso</p>
            </div>

            @auth
            <div class="text-center py-3">
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_plan">Assinar Plano</button>
            </div>
            @endauth
        </div>
      </div>
    </div>
  </section>

  @auth
  <div id="modal_plan" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pagamento de Plano</h5>

          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
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
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>
  @endauth

  @push('scripts')
  <script src="{{url(mix('assets/js/home/app.js'))}}"></script>
  @endpush
</x-layout.guest>