<footer class="bg-dark mt-auto py-3">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-D5LSKYMYZ5"></script>

    <script>
        window.dataLayer = window.dataLayer || []

        function gtag() {
            dataLayer.push(arguments)
        }
        gtag('js', new Date())

        gtag('config', 'G-D5LSKYMYZ5')
    </script>

    <div class="container-fluid">
        <div class="d-flex justify-content-between text-white-50 fs-14 fw-semibold flex-wrap">
            <div class="d-flex justify-content-center justify-content-sm-start flex-fill mx-1 flex-wrap">
                <a href="{{ route('guest.init') }}" class="text-white-50 text-decoration-none">
                    &nbsp;{{ config('app.name') }}&nbsp; - Históricos para Crash e Double.
                </a>
            </div>

            <div class="d-flex justify-content-around justify-content-sm-end flex-fill mx-1 flex-wrap">
                <a href="{{ route('guest.privacy-policy') }}" class="text-white-50 text-decoration-none mx-sm-0 mx-md-1">
                    Política de Privacidade
                </a>

                <a href="mailto:{{ config('app.mail_support') }}" class="text-white-50 text-decoration-none mx-sm-0 mx-md-1">
                    Email
                </a>

                <a href="https://wa.me/5567999521765" class="text-white-50 text-decoration-none mx-sm-0 mx-md-1">
                    Fale Conosco
                </a>
            </div>
        </div>
    </div>
</footer>