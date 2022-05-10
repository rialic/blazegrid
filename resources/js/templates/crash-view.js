import { makeElement } from '../utilx'

export const {
  renderDefaultHistoryView
} = (() => {
  function renderDefaultHistoryView(data) {
    const fragment = document.createDocumentFragment()

    data.forEach(crash => {
      const pointColor = (crash.point >= 2) ? 'success' : 'secondary'
      const parentDiv = makeElement('div', { class: 'm-1 fs-20' })
      const badgeDiv = makeElement('div', { class: `badge bg-${pointColor}` })
      const pointSpan = makeElement('span', { class: 'd-block' })

      parentDiv.insertAdjacentElement('beforeend', badgeDiv)
      badgeDiv.insertAdjacentElement('beforeend', pointSpan)

      pointSpan.textContent = `${crash.point}X`

      fragment.appendChild(parentDiv)
    })

    this.defaultHistoryEl.innerHTML = ''
    this.defaultHistoryEl.appendChild(fragment)
  }

  return {
    renderDefaultHistoryView
  }
})()