import { format } from 'date-fns-tz'
import { differenceInMinutes } from 'date-fns'
import { makeElement } from '@/utilx'

export const {
  renderDefaultCrashHistoryView,
  renderAdvancedCrashHistoryView
} = (() => {
  function renderDefaultCrashHistoryView(data) {
    const fragment = document.createDocumentFragment()

    data.forEach(crash => {
      const crashPointColor = (crash.point >= 2) ? 'success' : 'secondary'
      const parentDiv = makeElement('div', { class: 'my-1 me-1 fs-20' })
      const badgeDiv = makeElement('div', { class: `badge bg-${crashPointColor} bg-gradient` })
      const pointSpan = makeElement('span', { class: 'd-block' })
      const dateSpan = makeElement('span', { class: 'd-block mt-1' })

      parentDiv.insertAdjacentElement('beforeend', badgeDiv)
      badgeDiv.insertAdjacentElement('beforeend', pointSpan)

      pointSpan.textContent = `${crash.point}X`

      if (this.user.plan.name.toLowerCase() !== 'basic') {
        badgeDiv.insertAdjacentElement('beforeend', dateSpan)
        dateSpan.textContent = format(new Date(crash.created_at_server), 'HH:mm:ss', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone })
      }

      fragment.appendChild(parentDiv)
    })

    this.defaultHistoryEl.innerHTML = ''
    this.defaultHistoryEl.appendChild(fragment)

    this.defaultTotalRowsEl.textContent = `Total: ${data.length}`
  }

  function renderAdvancedCrashHistoryView(data, clearList) {
    if (clearList) {
      this.advancedHistoryEl.scrollTop = 0
      this.advancedHistoryEl.innerHTML = ''
      this.advancedHistoryLength = 0
    }

    const advancedCrashList = getAdvancedCrashList.call(this, data)
    const cardsString = advancedCrashList.reduce(getAdvancedCrashCardsString, '')

    this.advancedHistoryEl.insertAdjacentHTML('beforeend', cardsString)
    this.advancedHistoryLength += advancedCrashList.length
    this.advancedTotalRowsEl.textContent = `Total encontrado: ${this.advancedHistoryLength}`
  }

  function getAdvancedCrashList(data) {
    let advancedCrashList = data.reduce((acc, crash, index, list) => {
      const nextRecord = getNextCrashRecord.call(this, index, list)

      if (Number(crash.point) >= (this.startLogInput.value || 0) && Number(crash.point) <= (this.endLogInput.value || 1000000)) {
        const newCrashedItem = {
          point: crash.point,
          created_at_server: format(new Date(crash.created_at_server), 'dd/MM/yyyy HH:mm:ss', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone }),
          diff_min: (!nextRecord) ? 0 : differenceInMinutes(new Date(crash.created_at_server), new Date(nextRecord.created_at_server)),
          diff_step: (!nextRecord) ? 0 : list.findIndex(recordItem => recordItem.id === nextRecord.id) - index,
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

  function getAdvancedCrashCardsString(acc, crash) {
    const {point, created_at_server, diff_min, diff_step, sequence } = crash
    const crashPointColor = (crash.point >= 2) ? 'success' : 'danger'

    return acc += `
    <div class="card text-bg-dark">
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
              <i class="fa-solid fa-plus-minus me-1 fw-semibold"></i>

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
      <div class="card-footer bg-transparent">
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

  return {
    renderDefaultCrashHistoryView,
    renderAdvancedCrashHistoryView
  }
})()