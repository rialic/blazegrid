import { format, zonedTimeToUtc, formatInTimeZone } from 'date-fns-tz'
import { differenceInMinutes } from 'date-fns'
import { empty, makeElement } from '@/utilx'

/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*\
  Classe JS que renderiza o histórico padrão e o histórico avançado
  Recoemenda-se não mexer nesse arquivo a não ser que você sabia o que está fazendo
\*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
export const {
  renderDefaultCrashHistoryView,
  renderAdvancedCrashHistoryView
} = (() => {
  // Renderiza o histórico padrão com toda lógica para apresentar os horários, cores e sequências de crash
  function renderDefaultCrashHistoryView(data) {
    const fragment = document.createDocumentFragment()

    data.forEach((crash, index, list) => {
      const utcDate = new Date(zonedTimeToUtc(crash.created_at_server, 'America/Sao_Paulo'))
      const isFirstData = index === 0
      const crashPointColor = (crash.point >= 2) ? 'success' : 'secondary'
      const parentDiv = makeElement('div', { class: `position-relative mt-1 ${isFirstData ? ' ms-1 me-3' : 'mx-1'} fs-20` })
      const badgeDiv = makeElement('div', { class: `px-1 badge bg-${crashPointColor} bg-gradient` })
      const pointSpan = makeElement('span', { class: 'd-block' })
      const dateSpan = makeElement('span', { class: 'd-block mt-1' })
      const sequenceDiv = makeElement('div', { class: `position-absolute top-0 start-100 translate-middle fw-bold text-white bg-${crashPointColor} border border-2 border-white px-1 rounded fs-12 border-${crashPointColor}` })

    //   function convertTZ(date, tzString) {
    //     return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));
    // }

      parentDiv.insertAdjacentElement('beforeend', badgeDiv)
      badgeDiv.insertAdjacentElement('beforeend', pointSpan)

      pointSpan.textContent = `${crash.point}X`

      if (this.user.plan.name.toLowerCase() !== 'basic') {
        parentDiv.insertAdjacentElement('beforeend', sequenceDiv)
        badgeDiv.insertAdjacentElement('beforeend', dateSpan)


        // dateSpan.textContent = format(new Date(crash.created_at_server), 'HH:mm:ss', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone })
        dateSpan.textContent = format(utcDate, 'HH:mm:ss', { timeZone: 'America/Acre' })
        sequenceDiv.textContent = getNextCrashSequence.call(this, index, list)
      }

      fragment.appendChild(parentDiv)
    })

    this.defaultHistoryEl.innerHTML = ''
    this.defaultHistoryEl.appendChild(fragment)

    this.defaultTotalRowsEl.textContent = `Total: ${data.length}`
  }

  // Renderiza o histórico avançado com toda lógica para apresentar os horários, cores e sequências de crash
  // Essa função ainda é mais sensível pois refatora a lista de crash para trazer toda sequência, quantidade de jogadas e atrasos que existem entre as jodadas
  function renderAdvancedCrashHistoryView(data, hasToClearList) {
    handleAdvancedCrashHistoryHTML.call(this, data, hasToClearList)
    const advancedCrashList = getAdvancedCrashList.call(this, data)
    const cardsString = advancedCrashList.reduce(getAdvancedCrashCardsString.call(this), '')

    this.advancedHistoryEl.insertAdjacentHTML('beforeend', cardsString)
    this.advancedHistoryLength += advancedCrashList.length
    this.advancedTotalRowsEl.textContent = `Total encontrado: ${this.advancedHistoryLength}`
  }

  function handleAdvancedCrashHistoryHTML(data, hasToClearList) {
    if (hasToClearList) {
      this.advancedHistoryEl.scrollTop = 0
      this.advancedHistoryEl.innerHTML = ''
      this.advancedHistoryLength = 0
      this.previousAdvancedHistoryList = (Number(this.startLogInput.value) >= 2) ? null : filterPreviousAdvancedList.call(this, data)

      return
    }

    if (!empty(data)) {
      this.previousAdvancedHistoryList.forEach(crash => data.unshift(crash))
      this.previousAdvancedHistoryList.forEach(crash => this.advancedHistoryEl.querySelector(`[data-js="cr-${crash.id}"]`)?.remove())
      this.advancedHistoryLength = this.advancedHistoryLength - this.previousAdvancedHistoryList.length

      this.previousAdvancedHistoryList = filterPreviousAdvancedList.call(this, data)
    }
  }

  // Função que trata literalmente de todas sequências, diferenças de tempo, diferença de passos, cores e horários apresentados no histórico avançado
  function getAdvancedCrashList(data) {
    let advancedCrashList = data.reduce((acc, crash, index, list) => {
      const utcDate = zonedTimeToUtc(crash.created_at_server, 'America/Sao_Paulo')
      const nextData = getNextCrashRecord.call(this, index, list)
      const isCrashFilterApproved = (Number(crash.point) >= (Number(this.startLogInput.value)) && Number(crash.point) <= (this.endLogInput.value || 1000000))

      if (isCrashFilterApproved) {
        const newCrashedItem = {
          id: crash.uuid,
          point: crash.point,
          created_at_server: format(new Date(utcDate.toISOString()), 'dd/MM/yyyy HH:mm:ss', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone }),
          diff_min: (!nextData) ? 0 : differenceInMinutes(new Date(crash.created_at_server), new Date(nextData.created_at_server)),
          diff_step: (!nextData) ? 0 : list.findIndex(recordItem => recordItem.uuid === nextData.uuid) - index,
          sequence: getNextCrashSequence.call(this, index, list)
        }

        acc.push(newCrashedItem)

        return acc
      }

      return acc
    }, [])

    // Cut of limit to not exceed 3500
    if ((this.advancedHistoryLength + advancedCrashList.length) > this.advancedHistoryLimit) {
      const acceptableLimit = this.advancedHistoryLimit - this.advancedHistoryLength
      const cutOfLimit =  acceptableLimit - advancedCrashList.length

      advancedCrashList = advancedCrashList.slice(0, cutOfLimit)
    }

    return advancedCrashList
  }

  function filterPreviousAdvancedList(data) {
    const advancedCrashList = [...data].reverse()
    const tempAdvancedCrashList = []
    const isFirstDataPositiveCrashPoint = Number(advancedCrashList[0].point) >= 2
    const isFirstDataNegativeCrashPoint = Number(advancedCrashList[0].point) < 2

    if (isFirstDataPositiveCrashPoint) {
      for (let index = 0; index <= (advancedCrashList.length - 1); index++) {
        const isFirstData = index === 0
        const isPositiveCrashPoint = Number(advancedCrashList[index].point) >= 2

        if (isFirstData || isPositiveCrashPoint) {
          tempAdvancedCrashList.push(advancedCrashList[index])

          continue
        }

        break
      }
    }

    if (isFirstDataNegativeCrashPoint) {
      for (let index = 0; index <= (advancedCrashList.length - 1); index++) {
        const isFirstData = index === 0
        const isNegativeCrashPoint = Number(advancedCrashList[index].point) < 2

        if (isFirstData || isNegativeCrashPoint) {
          tempAdvancedCrashList.push(advancedCrashList[index])

          continue
        }

        break
      }
    }

    return tempAdvancedCrashList
  }

  function getNextCrashRecord(currentIndex, list) {
    return list.filter((_, index) => index > currentIndex).find(crash => {
      return (Number(crash.point) >= (this.startLogInput.value || 0) && Number(crash.point) <= (this.endLogInput.value || 1000000))
    })
  }

  function getNextCrashSequence(currentIndex, list) {
    const reducedCrashList = list.filter((_, index) => index > currentIndex)
    const currentExternalCrash = list[currentIndex]
    let sequence = 1

    for (let index = 0; index <= (reducedCrashList.length - 1); index++) {
      const currentListCrash = reducedCrashList[index]
      const hasExternalInternalCrashPositivePoint = (Number(currentExternalCrash.point) >= 2 && Number(currentListCrash.point) >= 2)
      const hasExternalInternalCrashNegativePoint = (Number(currentExternalCrash.point) < 2 && Number(currentListCrash.point) < 2)

      if (hasExternalInternalCrashPositivePoint || hasExternalInternalCrashNegativePoint) {
        sequence += 1

        continue
      }

      break
    }

    return sequence
  }

  function getAdvancedCrashCardsString() {
    return (acc, crash) => {
      const { id, point, created_at_server, diff_min, diff_step, sequence } = crash
      const crashPointColor = (crash.point >= 2) ? 'success' : 'danger'

      return acc += `
      <div data-js='cr-${id}' class="card text-bg-dark">
        <div class="card-body p-2">
          <div class="d-flex justify-content-around gap-2">
            <div class="d-flex flex-column">
              <div class="d-flex justify-content-center align-items-center w-75 mx-auto border border-2 border-${crashPointColor} rounded">
                <div data-js="crash-point" class="mb-0 text-${crashPointColor} fw-semibold fs:min-14:max-18">
                  ${point}
                </div>

                <i class="fa-solid ${crashPointColor === 'success' ? 'fa-arrow-trend-up' : 'fa-xmark'} fa-lg ms-2 text-${crashPointColor}"></i>
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
              ${sequence - 1} crash ${crashPointColor === 'success' ? 'positivo(s)' : 'negativo(s)'} anterior(es) a este
            </span>

            <span class="fw-bold fs:min-14:max-16">Sequência de Nº ${sequence} <i class="fa-solid fa-circle mt-1 ms-1 text-${crashPointColor}"></i></span>
          </div>
        </div>
      </div>
      `
    }
  }

  return {
    renderDefaultCrashHistoryView,
    renderAdvancedCrashHistoryView
  }
})()