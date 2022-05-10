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

        <button id="refresh-default" class="btn btn-danger btn-sm mt-2 me-3 fw-bold">
          Atualizar <i class="fa-solid fa-arrows-rotate"></i>
        </button>
      </div>

      <div class="card-body p-3">
        <div data-js="default-history" class="d-flex flex-wrap w-100">
          @foreach ($crashDefaultHistory as $crash)
          <div class="m-1 fs-20">
            <div class="badge bg-{{ ($crash->point >= 2) ? 'success' : 'secondary' }}">
              <span class="d-block">{{ $crash->point }}X</span>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="card mb-4">
      <h5 class="card-title px-4 pt-2 text-white">Histórico Avançado</h5>

      <div class="card-body p-4">
        This is some text within a card body.
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="{{url(mix('assets/js/crash/app.js'))}}"></script>
  @endpush
</x-layout.priv>