import { empty } from '@/helper'

export default function getAdvancedCrashHTMLString() {
  return (acc, crash) => {
    const { id, point, created_at_server, diff_min, diff_step, sequence } = crash
    const crashColor = (crash.point >= 2) ? 'success' : 'danger'

    return acc += `
      <div data-js='cr-${id}' class="card text-bg-dark">
        <div class="card-body p-2">
          <div class="d-flex justify-content-around gap-2">
            <div class="d-flex flex-column">
              <div class="d-flex justify-content-center align-items-center w-75 mx-auto border border-2 border-${crashColor} rounded">
                <div data-js="crash-point" class="mb-0 text-${crashColor} fw-semibold fs:min-14:max-18">
                  ${point}
                </div>

                <i class="fa-solid ${crashColor === 'success' ? 'fa-arrow-trend-up' : 'fa-xmark'} fa-lg ms-2 text-${crashColor}"></i>
              </div>

              <div class="d-flex mt-1 align-items-center">
                <i class="fa-solid fa-clock me-1 fw-semibold fs:min-14:max-16"></i>

                <div data-js="crash-date" class="ms-1 fw-semibold fs:min-14:max-16">
                  ${created_at_server}
                </div>
              </div>
            </div>

            <div class="d-flex flex-column">
              <div class="d-flex align-items-center p-1 border border-2 border-primary rounded">
                <i class="fa-solid fa-hourglass me-1 fw-semibold me-1"></i>
                <i class="fa-solid fa-plus-minus fa-xs me-1 fw-semibold ms-1"></i>

                <div class="ms-1 fw-semibold fs:min-14:max-16">
                  <div data-js="crash-diff-min" class="d-none">${diff_min}</div>
                  ${diff_min} minutos de diferença do crash anterior
                </div>
              </div>

              <div class="d-flex mt-1 align-items-center p-1 border border-2 border-primary rounded">
                <i class="fa-solid fa-dice me-1 fw-semibold fs:min-14:max-16"></i>

                <div data-js="crash-diff-steps" class="ms-1 fw-semibold fs:min-14:max-16">
                  <div data-js="crash-diff-step" class="d-none">${diff_step}</div>
                  ${diff_step} jogada(s) anterior(es) até esse crash
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer ${!empty(this.startLogInput.value) || !empty(this.endLogInput.value) ? 'd-none' : ''} bg-transparent">
          <div class="d-flex justify-content-around">
            <span class="fw-bold fs:min-14:max-16">
              ${sequence - 1} crash ${crashColor === 'success' ? 'positivo(s)' : 'negativo(s)'} anterior(es) a este
            </span>

            <span class="fw-bold fs:min-14:max-16">Sequência de Nº ${sequence} <i class="fa-solid fa-circle mt-1 ms-1 text-${crashColor}"></i></span>
          </div>
        </div>
      </div>
      `
  }
}