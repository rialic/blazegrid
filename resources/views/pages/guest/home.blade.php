<x-layout.guest>
  @section('title', 'Blaze Grid')

  @push('css')
  <link href="{{url(mix('assets/css/home/app.css'))}}" rel="stylesheet" />
  @endpush

  <section>
    Here 1
  </section>

  <section class="section mt-4" id="services">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="title text-center my-4">
            <h3 class="mb-3 text-light">Histórico de operações detalhados para Crash e Double</h3>

            <p class="text-white-50">Na blazeGrids você encontra históricos para Crash e Double bem mais detalhados para você definir sua estratégia de operações.</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-4 col-sm-6">
          <div class="services-box text-center mt-3 p-3">
            <div class="services-icon mb-3">
              <i class="pe-7s-display1 h2 mt-0"></i>
            </div>

            <h4 class="text-light">Filtro de velas para Crash</h4>

            <p class="text-white-50">
                Em nossa tabela de histórico do Crash você poderá filtrar pelas velas que deseja para ter uma melhor visualização de como o gráfico do Crash opera.
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="services-box text-center mt-3 p-3">
            <div class="services-icon mb-3">
              <i class="pe-7s-science h2 mt-0"></i>
            </div>

            <h4 class="text-light">Intervalo entre operações</h4>

            <p class="text-white-50">
                Com a blazeGrids é possível conferir a quantidade de jogadas entre as velas que você filtrar. Afinal de quantas em quantas vezes o gráfico Crash apresenta uma
                vela de 10.0X?
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="services-box text-center mt-3 p-3">
            <div class="services-icon mb-3">
              <i class="pe-7s-phone h2 mt-0"></i>
            </div>

            <h4 class="text-light">Intervalo de tempo</h4>

            <p class="text-muted">
                Ter um histórico com o intervalo de tempo entre as velas mais desejadas é uma ótima estratégia para você fazer sua próxima operação.
            </p>
          </div>
        </div>

        <div class="col-lg-4 col-sm-6">
          <div class="services-box text-center mt-3 p-3">
            <div class="services-icon mb-3">
              <i class="pe-7s-exapnd2 h2 mt-0"></i>
            </div>
            <h4>Contador</h4>
            <p class="text-muted">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis.</p>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="services-box text-center mt-3 p-3">
            <div class="services-icon mb-3">
              <i class="pe-7s-headphones h2 mt-0"></i>
            </div>
            <h4>Excel</h4>
            <p class="text-muted">Et harum quidem rerum facilis est et expedita distinctio nam soluta nobis est.</p>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6">
          <div class="services-box text-center mt-3 p-3">
            <div class="services-icon mb-3">
              <i class="pe-7s-upload h2 mt-0"></i>
            </div>
            <h4>Novos recursos</h4>
            <p class="text-muted">Ut enim ad minima veniam, quis nostrum ullam corporis suscipit.</p>
          </div>
        </div>

      </div>
      <!-- end row -->
    </div>
    <!-- end container -->
  </section>

</x-layout.guest>