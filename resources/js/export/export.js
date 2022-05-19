export const {
  exportFromTable
} = (() => {
  const exportFromTable = (table, type) => {
    const tableContentString = getTableContent.call(this, table)
    const exportTypeList = {
      'excel': generateXLSXURILink,
      'csv': generateCSVURILink
    }

    return exportTypeList[type].call(this, tableContentString)
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

  function getTableContent(table) {
    const tableRows = Array.from(table.querySelectorAll('tr'))
    const tableContentString = tableRows.map((row, index) => {
      const isFirstIndex = index === 0

      if (isFirstIndex) {
        return Array.from(row.cells).map((cell, index) => {
          const isSecondIndex = index === 1
          const isThirdIndex = index === 2
          const isFourthIndex = index === 3

          if (isSecondIndex) {
            return 'Data'
          }

          if (isThirdIndex) {
            return 'Diferença em minutos'
          }

          if (isFourthIndex) {
            return 'Diferença em operações'
          }

          return cell.textContent.trim()
        }).join(',')
      }

      return Array.from(row.cells).map(cell => cell.textContent.trim()).join(',')
    }).join('\n')

    return tableContentString
  }

  return {
    exportFromTable
  }
})()