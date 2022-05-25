import { format } from 'date-fns-tz'
import { differenceInMinutes } from 'date-fns'
import { makeElement } from '../utilx'

export const {
  renderDefaultCrashHistoryView,
  renderAdvancedCrashHistoryView
} = (() => {
  function renderDefaultCrashHistoryView(data) {
    const fragment = document.createDocumentFragment()

    data.forEach(crash => {
      const pointColor = (crash.point >= 2) ? 'success' : 'secondary'
      const parentDiv = makeElement('div', { class: 'my-1 me-1 fs-20' })
      const badgeDiv = makeElement('div', { class: `badge bg-${pointColor} bg-gradient` })
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

  function renderAdvancedCrashHistoryView(data) {
    const tBody = document.querySelector('.table > tbody')
    const advancedCrashList = getAdvancedCrashList.call(this, data)
    const rowsString = advancedCrashList.reduce(getAdvancedCrashRowsString, '')
    const range = document.createRange()

    range.selectNodeContents(tBody)
    tBody.innerHTML = ''
    tBody.appendChild(range.createContextualFragment(rowsString))

    this.advancedTotalRowsEl.textContent = `Total encontrado: ${advancedCrashList.length}`
  }

  function getNextCrashRecord(currentIndex, list) {
    return list.filter((_, index) => index > currentIndex).find(crash => {
      return (Number(crash.point) >= (this.startLogInput.value || 0) && Number(crash.point) <= (this.endLogInput.value || 1000000))
    })
  }

  function getAdvancedCrashList(data) {
    return data.reduce((acc, crash, index, list) => {
      const nextRecord = getNextCrashRecord.call(this, index, list)

      if (Number(crash.point) >= (this.startLogInput.value || 0) && Number(crash.point) <= (this.endLogInput.value || 1000000)) {
        const newCrashedItem = {
          point: crash.point,
          created_at_server: format(new Date(crash.created_at_server), 'dd/MM/yyyy HH:mm:ss', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone }),
          diff_min: (!nextRecord) ? 0 : differenceInMinutes(new Date(crash.created_at_server), new Date(nextRecord.created_at_server)),
          diff_step: (!nextRecord) ? 0 : list.findIndex(recordItem => recordItem.id === nextRecord.id) - index,
        }

        acc.push(newCrashedItem)

        return acc
      }

      return acc
    }, [])
  }

  function getAdvancedCrashRowsString(acc, crash, index, list) {
    const isLastIndex = index === (list.length - 1)
    const pointColor = (crash.point >= 2) ? 'success' : 'secondary'

    return acc += `
        <tr>
          <td class="text-center">
            <div class="badge bg-${pointColor} bg-gradient fs-14">
              <span class="d-block">${crash.point}</span>
            </div>
          </td>

          <td class="text-center">
            ${crash.created_at_server}
          </td>

          <td class="position-relative text-center">
            <div class="d-block">
              <i class="fa-solid fa-plus-minus"></i> ${crash.diff_min} min
            </div>
            <div class="${(!isLastIndex) ? 'd-reply-block' : 'd-none'}">
              <i class="fa-solid fa-reply fa-rotate-90 fa-xs text-light"></i>
            </div>
          </td>

          <td class="position-relative text-center">
            ${crash.diff_step}
            <div class="${(!isLastIndex) ? 'd-reply-block' : 'd-none'}">
              <i class="fa-solid fa-reply fa-rotate-90 fa-xs text-light"></i>
            </div>
          </td>
        </tr>
      `
  }

  return {
    renderDefaultCrashHistoryView,
    renderAdvancedCrashHistoryView
  }
})()