<x-layout.priv>
  @section('title', 'Crash')

  @push('css')
  <link href="{{url(mix('assets/css/crash/app.css'))}}" rel="stylesheet" />
  @endpush

  @push('scripts')
  <script src="{{url(mix('assets/js/crash/app.js'))}}"></script>
  @endpush
</x-layout.priv>