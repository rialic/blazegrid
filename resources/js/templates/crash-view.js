import { format } from 'date-fns-tz'
import { differenceInMinutes } from 'date-fns'
import { userPlan, makeElement } from '../utilx'

export const {
  renderDefaultHistoryView,
  renderAdvancedHistoryView
} = (() => {
  function renderDefaultHistoryView(data, user) {
    const fragment = document.createDocumentFragment()

    data.forEach(crash => {
      const pointColor = (crash.point >= 2) ? 'success' : 'secondary'
      const parentDiv = makeElement('div', { class: 'my-1 me-1 fs-20' })
      const badgeDiv = makeElement('div', { class: `badge bg-${pointColor}` })
      const pointSpan = makeElement('span', { class: 'd-block' })
      const dateSpan = makeElement('span', { class: 'd-block mt-1' })

      parentDiv.insertAdjacentElement('beforeend', badgeDiv)
      badgeDiv.insertAdjacentElement('beforeend', pointSpan)

      pointSpan.textContent = `${crash.point}X`

      if (userPlan(user) !== 1) {
        badgeDiv.insertAdjacentElement('beforeend', dateSpan)
        dateSpan.textContent = format(new Date(crash.created_at_server), 'HH:mm:ss', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone })
      }

      fragment.appendChild(parentDiv)
    })

    this.defaultHistoryEl.innerHTML = ''
    this.defaultHistoryEl.appendChild(fragment)
  }

  function renderAdvancedHistoryView(data) {
    const tBody = document.querySelector('.table > tbody')
    const fragment = document.createDocumentFragment()
    const advancedCrashList = getAdvancedCrashList.call(this, data)

    advancedCrashList.forEach((crash, index, list) => {
      const isLastIndex = index === (list.length - 1)
      const pointColor = (crash.point >= 2) ? 'success' : 'secondary'

      // <tr>
      const tr = makeElement('tr')

      // Point <td>
      const badgeTd = makeElement('td', { class: 'text-center' })
      const badgeDiv = makeElement('div', { class: `badge bg-${pointColor} fs-14` })
      const pointSpan = makeElement('span', { class: 'd-block' })

      // Date <td>
      const dateDiv = makeElement('td', { class: 'text-center' })

      // Diff min <td>
      const diffMinTd = makeElement('td', { class: 'position-relative text-center' })
      const diffMinDiv = makeElement('div', { class: 'd-block' })
      const plusMinusIcon = makeElement('i', { class: 'fa-solid fa-plus-minus' })
      const diffMinReplyBlockDiv = makeElement('div', { class: 'd-reply-block' })
      const diffMinReplyIcon = makeElement('i', { class: 'fa-solid fa-reply fa-rotate-90 fa-sm text-light' })

      const diffStepTd = makeElement('td', { class: 'position-relative text-center' })
      const diffStepReplyBlockDiv = makeElement('div', { class: 'd-reply-block' })
      const diffStepReplyIcon = makeElement('i', { class: 'fa-solid fa-reply fa-rotate-90 fa-sm text-light' })

      tr.insertAdjacentElement('beforeend', badgeTd)
      badgeTd.insertAdjacentElement('beforeend', badgeDiv)
      badgeDiv.insertAdjacentElement('beforeend', pointSpan)
      pointSpan.textContent = `${crash.point}X`

      tr.insertAdjacentElement('beforeend', dateDiv)
      dateDiv.textContent = format(new Date(crash.created_at_server), 'dd/MM/yyy HH:mm:ss', { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone })

      tr.insertAdjacentElement('beforeend', diffMinTd)
      diffMinTd.insertAdjacentElement('beforeend', diffMinDiv)
      diffMinDiv.insertAdjacentElement('beforeend', plusMinusIcon)
      diffMinDiv.insertAdjacentText('beforeend', ` ${crash.diff_min} min`)

      tr.insertAdjacentElement('beforeend', diffStepTd)
      diffStepTd.insertAdjacentText('beforeend', crash.diff_step)

      if (!isLastIndex) {
        diffMinTd.insertAdjacentElement('beforeend', diffMinReplyBlockDiv)
        diffMinReplyBlockDiv.insertAdjacentElement('beforeend', diffMinReplyIcon)

        diffStepTd.insertAdjacentElement('beforeend', diffStepReplyBlockDiv)
        diffStepReplyBlockDiv.insertAdjacentElement('beforeend', diffStepReplyIcon)
      }

      fragment.appendChild(tr)
    })

    tBody.innerHTML = ''
    tBody.appendChild(fragment)
  }

  function getAdvancedCrashList(data) {
    return data.reduce((acc, crash, index, list) => {
      const nextRecord = getNextRecord.call(this, index, list)

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

  function getNextRecord(currentIndex, list) {
    return list.filter((_, index) => index > currentIndex).find(crash => {
      return (Number(crash.point) >= (this.startLogInput.value || 0) && Number(crash.point) <= (this.endLogInput.value || 1000000))
    })
  }

  return {
    renderDefaultHistoryView,
    renderAdvancedHistoryView
  }
})()