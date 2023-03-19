@props(['text', 'classIcon' => '', 'href' => 'javascript:void(0);'])

<li>
  <a href="{{$href}}" {{$attributes->merge(['class' => 'd-flex align-items-center px-2 fw-semibold text-decoration-none h-100 fs-15 text-light'])}}>
    <div class="d-block mb-1 text-center">
      <i class="{{$classIcon}} pe-1 pt-2"></i>
    </div>

    {{$text}}
  </a>
<li>