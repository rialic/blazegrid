<x-layout.guest>
  @section('title', 'Política de privacidade') @push('css')
  <link href="{{ url(mix('assets/css/home/app.min.css')) }}" rel="stylesheet" />
  @endpush

  <section class="mt-4">
    <div class="container">
      <div class="row justify-content-center">
        <h5 class="text-white">Política de Privacidade</h5>
        <p>
          Somos comprometidos em proteger a privacidade dos usuários finais que
          visitam nosso site. Nós valorizamos a sua privacidade e queremos que
          você se sinta seguro ao navegar e usar nossos serviços online. Por
          esta razão, informamos que nenhum dado pessoal será utilizado,
          compartilhado ou vendido para terceiros pois essa nem é a nossa
          intenção e até por isso mesmo não solicitamos nenhum informação
          sensível para acessar o nosso site.
        </p>

        <p>
          Única informação que solicitamos é o endereço de e-mail, que será
          utilizado apenas para a finalidade específica de ter um meio de
          acessar o nosso site.
        </p>

        <p>
          Caso você tenha qualquer dúvida ou preocupação sobre nossa Política de
          Privacidade ou sobre a proteção de suas informações pessoais, por
          favor, entre em contato conosco através de nosso "Fale Conosco"
          disponível em nosso site.
        </p>

        <p class="lead">
          Atenciosamente, equipe {{ config('app.name') }}
        </p>
      </div>
    </div>
  </section>
</x-layout.guest>