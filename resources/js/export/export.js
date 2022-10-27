export const {
  doExport
} = (() => {
  function doExport(type) {
    const cardContentString = getCardContent.call(this)
    const exportTypeList = { 'excel': generateXLSXURILink, 'csv': generateCSVURILink }

    return exportTypeList[type].call(this, cardContentString)
  }

  function generateXLSXURILink(tableContentString) {
    const download = 'excel-data.xlsx'
    const dataType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    const linkURI = `data:${dataType}chartset=uft-8,${encodeURIComponent(tableContentString)}`

    return { download, linkURI }
  }

  function generateCSVURILink(tableContentString) {
    const download = 'excel-data.csv'
    const dataType = 'text/csv'
    const linkURI = `data:${dataType}chartset=uft-8,${encodeURIComponent(tableContentString)}`

    return { download, linkURI }
  }

  function getCardContent() {
    const crashCardListEl = Array.from(this.advancedHistoryEl.querySelectorAll('.card'))
    const dataContentString = crashCardListEl.map((card, index) => {
      const isFirstIndex = index === 0
      const crashPoint = card.querySelector('[data-js="crash-point"]').textContent.trim()
      const crashDate = card.querySelector('[data-js="crash-date"]').textContent.trim()
      const crashDiffMin = card.querySelector('[data-js="crash-diff-min"]').textContent.trim()
      const crashDiffStep = card.querySelector('[data-js="crash-diff-step"]').textContent.trim()

      if (isFirstIndex) {
        const contentHeader = 'Crash,Data,Diferença em minutos,Diferença em jogadas'
        return `${contentHeader}\n${crashPoint},${crashDate},${crashDiffMin},${crashDiffStep}`
      }

      return `${crashPoint},${crashDate},${crashDiffMin},${crashDiffStep}`
    }).join('\n')

    return dataContentString
  }

  return {
    doExport
  }
})()