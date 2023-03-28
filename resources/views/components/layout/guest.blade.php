<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  {!! seo() !!}

  <title>@yield('title')</title>

  @stack('css')
</head>

<body>
  @include('components.header.header-menu')

  <main class="d-flex flex-column flex-fill">
    {{$slot}}
  </main>

  @include('components.footer.footer')

  @stack('scripts')
</body>

</html>