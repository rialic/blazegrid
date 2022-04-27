@props(['text', 'classIcon', 'href' => 'javascript:void(0);'])

<li>
  <a href="{{$href}}" {{$attributes->merge(['class' => 'd-flex align-items-center px-2 fw-semibold fs-15 text-decoration-none h-100 text-light waves-effect waves-light'])}}>
    <div class="d-block mb-1 text-center">

      <i class="{{$classIcon}}"></i>

    </div>

    {{$text}}
  </a>
<li>