<footer class="py-3 mt-auto bg-dark">

    <div class="container-fluid">

        <div class="d-flex flex-wrap justify-content-between text-white-50 fs-14 fw-semibold">

            <div class="d-flex justify-content-center justify-content-sm-start flex-fill mx-1">

                {{date('Y')}} ©

                <a href="{{route('guest.init')}}" class="text-white-50 text-decoration-none">

                    &nbsp;{{config('app.name')}}&nbsp;

                </a> - Históricos para Crash e Double.

            </div>

            <div class="d-flex flex-wrap justify-content-around justify-content-sm-end flex-fill mx-1">

                {{-- <a class="d-none text-white-50 text-decoration-none mx-1">
                    Quem Somos
                </a> --}}

                {{-- <a class="d-none text-white-50 text-decoration-none mx-1">
                    Termos de Uso
                </a> --}}

                <a href="https://wa.me/5567999316800" class="text-white-50 text-decoration-none mx-1">
                    Fale Conosco
                </a>

            </div>

        </div>

    </div>

</footer>