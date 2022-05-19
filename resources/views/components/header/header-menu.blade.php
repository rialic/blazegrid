<header class="header-menu justify-content-between">
  <a href="{{route('guest.init')}}" class="d-flex align-items-center px-1 text-decoration-none">
    <span class="fs-24 fw-bold text-danger">blaze</span>

    &thinsp;

    <div class="position-relative d-flex">
      <h3 class="mb-0 px-1 border border-2 border-danger rounded-start fw-bold text-light zindex-2">G</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light">r</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light zindex-2">i</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger fw-bold text-light zindex-2">d</h3>

      <h3 class="mb-0 px-1 border border-start-0 border-2 border-danger rounded-end fw-bold text-light">s</h3>

      <div class="logo-grid-line" style="top: 33%;"></div>

      <div class="logo-grid-line" style="top: 66%;"></div>
    </div>
  </a>

  @guest
  <ul class="nav align-items-stretch">
    <x-menu.nav-link href="{{route('login')}}" text="Cadastre-se" />
    <x-menu.nav-link href="{{route('login')}}" text="Entrar" />
  </ul>
  @endguest

  @auth
  <ul class="nav align-items-stretch">
    <li class="nav-item">
      <a href="javascript:void(0)" class="d-flex align-items-center dropdown-toggle h-100 waves-effect waves-light" role="button" data-bs-toggle="dropdown">
        {{ Str::limit(auth()->user()->name, 10) }}
      </a>
      <ul class="dropdown-menu dropdown-menu-dark">
        <li class="py-1 px-3">
          <a href="{{route('priv.crash')}}" class="d-block text-decoration-none">
            <div class="d-flex justify-content-between">
              <svg width="19" height="18" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.14 5C12.69 5.45 12.14 5.64 11.9 5.41C11.66 5.18 11.9 4.63 12.32 4.17C12.74 3.71 13.32 3.53 13.56 3.76C13.8 3.99 13.6 4.53 13.14 5Z" fill="#f12c4c">
                </path>
                <path
                  d="M18 0H2C1.46957 0 0.96086 0.210714 0.585787 0.585786C0.210714 0.960859 0 1.46957 0 2V20C6.28 20 10.6 15.76 12.25 10.67C12.8089 10.8838 13.4016 10.9956 14 11C14.11 11 14.2 11 14.31 11C13.2234 14.7898 10.7158 18.0139 7.31 20H18C18.5304 20 19.0391 19.7893 19.4142 19.4142C19.7893 19.0391 20 18.5304 20 18V2C20 1.46957 19.7893 0.960859 19.4142 0.585786C19.0391 0.210714 18.5304 0 18 0V0ZM14 9C13.4067 9 12.8266 8.82405 12.3333 8.49441C11.8399 8.16476 11.4554 7.69623 11.2284 7.14805C11.0013 6.59987 10.9419 5.99667 11.0576 5.41473C11.1734 4.83279 11.4591 4.29824 11.8787 3.87868C12.2982 3.45912 12.8328 3.1734 13.4147 3.05764C13.9967 2.94189 14.5999 3.0013 15.1481 3.22836C15.6962 3.45542 16.1648 3.83994 16.4944 4.33329C16.8241 4.82664 17 5.40666 17 6C17 6.79565 16.6839 7.55871 16.1213 8.12132C15.5587 8.68393 14.7957 9 14 9Z"
                  fill="#f12c4c"></path>
                <path
                  d="M19.94 1.54006C19.94 1.45006 19.94 1.35006 19.86 1.26006C19.8414 1.22776 19.8247 1.19436 19.81 1.16006L19.69 0.940059L19.62 0.830059L19.51 0.660059C19.4821 0.618792 19.4485 0.581747 19.41 0.550059L19.22 0.420059L19.08 0.320059C19.0366 0.29081 18.9895 0.267283 18.94 0.250059C18.8034 0.172451 18.6594 0.108797 18.51 0.0600586C18.5241 0.206388 18.5241 0.353729 18.51 0.500059V16.7501C18.51 17.2142 18.3256 17.6593 17.9975 17.9875C17.6693 18.3157 17.2241 18.5001 16.76 18.5001H9.41002C8.74004 19.0592 8.02418 19.561 7.27002 20.0001H18C18.5305 20.0001 19.0392 19.7893 19.4142 19.4143C19.7893 19.0392 20 18.5305 20 18.0001V2.00006C20.0158 1.87054 20.0158 1.73958 20 1.61006C19.9847 1.58307 19.9644 1.5593 19.94 1.54006Z"
                  fill="#f12c4c"></path>
              </svg>

              <span>Crash</span>
            </div>
          </a>
        </li>

        <li class="py-1 px-3">
          <a href="javascript:void(0)" class="d-block disabled text-decoration-none">
            <div class="d-flex justify-content-between">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M1 6H0V14H1C1.26522 14 1.51957 13.8946 1.70711 13.7071C1.89464 13.5196 2 13.2652 2 13V7C2 6.73478 1.89464 6.48043 1.70711 6.29289C1.51957 6.10536 1.26522 6 1 6Z"
                  fill="#f12c4c"></path>
                <path
                  d="M19 6C18.7348 6 18.4804 6.10536 18.2929 6.29289C18.1054 6.48043 18 6.73478 18 7V13C18 13.2652 18.1054 13.5196 18.2929 13.7071C18.4804 13.8946 18.7348 14 19 14H20V6H19Z"
                  fill="#f12c4c"></path>
                <path
                  d="M9 14V4H6C5.46957 4 4.96086 4.21071 4.58579 4.58579C4.21071 4.96086 4 5.46957 4 6V14C4 14.5304 4.21071 15.0391 4.58579 15.4142C4.96086 15.7893 5.46957 16 6 16H9V14Z"
                  fill="#f12c4c"></path>
                <path
                  d="M14 4H11V16H14C14.5304 16 15.0391 15.7893 15.4142 15.4142C15.7893 15.0391 16 14.5304 16 14V6C16 5.46957 15.7893 4.96086 15.4142 4.58579C15.0391 4.21071 14.5304 4 14 4Z"
                  fill="#f12c4c"></path>
                <path
                  d="M9 19C9 19.2652 9.10536 19.5196 9.29289 19.7071C9.48043 19.8946 9.73478 20 10 20C10.2652 20 10.5196 19.8946 10.7071 19.7071C10.8946 19.5196 11 19.2652 11 19V16H9V19Z"
                  fill="#f12c4c"></path>
                <path
                  d="M11 1C11 0.734784 10.8946 0.48043 10.7071 0.292893C10.5196 0.105357 10.2652 0 10 0C9.73478 0 9.48043 0.105357 9.29289 0.292893C9.10536 0.48043 9 0.734784 9 1V4H11V1Z"
                  fill="#f12c4c"></path>
              </svg>

              <span class="mx-3">Double</span>

              <span>(Breve)</span>
            </div>
          </a>
        </li>

        <li class="py-1 px-3">
          <a href="{{route('priv.logout')}}" class="d-block text-decoration-none">
            <div class="d-flex justify-content-between">
                <i class="fa-solid fa-arrow-right-to-bracket align-self-center text-danger"></i>

                <span>Sair</span>
            </div>
          </a>
        </li>
      </ul>
  </ul>
  @endauth
</header>