import getDefaultCrashHTMLString from '@/pages/crash/components/default-crash-card'
import getAdvancedCrashHTMLString from '@/pages/crash/components/advanced-crash-card'
import { resetAdvancedCrashHistoryList, setPreviousAdvancedCrashHistoryList, getAdvancedCrashList } from '@/pages/crash/components/features'

/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*\
  Classe JS que renderiza o histórico padrão e o histórico avançado
\*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
export const {
  renderDefaultCrashHistoryView,
  renderAdvancedCrashHistoryView
} = (() => {
  // Renderiza o histórico padrão com toda lógica para apresentar os horários, cores e sequências de crash
  function renderDefaultCrashHistoryView(data) {
    const defaultCrashHTMLString = data.reduce(getDefaultCrashHTMLString.call(this), '')

    this.defaultHistoryEl.insertAdjacentHTML('beforeend', defaultCrashHTMLString)
    this.defaultTotalRowsEl.textContent = `Total: ${data.length}`
  }

  // Renderiza o histórico avançado com toda lógica para apresentar os horários, cores e sequências de crash
  function renderAdvancedCrashHistoryView(data, hasToClearList) {
    resetAdvancedCrashHistoryList.call(this, data, hasToClearList)
    setPreviousAdvancedCrashHistoryList.call(this)
    const advancedCrashList = getAdvancedCrashList.call(this, data)
    const advancedCrashHTMLString = advancedCrashList.reduce(getAdvancedCrashHTMLString.call(this), '')

    this.advancedHistoryEl.insertAdjacentHTML('beforeend', advancedCrashHTMLString)
    this.advancedHistoryLength += advancedCrashList.length
    this.advancedTotalRowsEl.textContent = `Total encontrado: ${this.advancedHistoryLength}`
  }

  return {
    renderDefaultCrashHistoryView,
    renderAdvancedCrashHistoryView
  }
})()