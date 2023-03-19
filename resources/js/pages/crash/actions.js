import { empty, parseFilters } from '@/helper'

import { indexDefaultHistory, indexAdvancedHistory } from '@/api/crash'
import { getAdvancedHistoryObserver, getLimit, showCountDownButton, showContentReloadingButton, hideContentReloadingButton, hidePgLoader } from '@/pages/crash/features'
import { renderDefaultCrashHistoryView, renderAdvancedCrashHistoryView } from '@/pages/crash/components/views'

export const {
  refreshDefaultCrashHistory,
  refreshAdvancedCrashHistory
} = (() => {
  // Função que chama a API de carregamento do Histórico padrão do Crash
  async function refreshDefaultCrashHistory() {
    showCountDownButton.call(this, this.refreshDefaultBtn)
    const { status, response, message, code } = await indexDefaultHistory()

    if (status === 'ok') {
      const defaultCrashHistoryList = response?.data?.default_history

      // Função chamada simplesmente para renderizar na tela os dados obtidos pela API do Histórico Padrão
      // Na renderização que é feito todo cálculo de horas e passos e cores dos points do crash
      renderDefaultCrashHistoryView.call(this, defaultCrashHistoryList)

      return
    }

    throw new Error(`message: ${message}, code: ${code}`)
  }

  // Função que chama a API de carregamento do Histórico avançado do Crash
  async function refreshAdvancedCrashHistory(hasToClearList = true) {
    const params = { limit: getLimit.call(this), page: ((hasToClearList) ? this.advancedHistoryPage = 1 : this.advancedHistoryPage) }
    const { data } = await getAdvancedHistoryData.call(this, params)
    const advancedCrashHistoryList = data

    // Função chamada simplesmente para renderizar na tela os dados obtidos pela API do Histórico Padrão
    // Na renderização que é feito todo cálculo de horas e passos e cores dos points do crash
    renderAdvancedCrashHistoryView.call(this, advancedCrashHistoryList, hasToClearList)
    hideContentReloadingButton.call(this)
    hidePgLoader.call(this)

    if (this.advancedHistoryEl.querySelectorAll('div.card').length <= 5) {
      return
    }

    if (!empty(data) && this.advancedHistoryLength < 3500) {
      getAdvancedHistoryObserver.call(this)
    }
  }

  // Função que faz a busca na API de históricos avançados e retorna esses dados
  async function getAdvancedHistoryData(params) {
    showContentReloadingButton.call(this)
    const { status, response, message, code } = await indexAdvancedHistory.call(this, parseFilters(params))

    if (status === 'ok') {
      return response?.data.advanced_history
    }

    hideContentReloadingButton.call(this)
    hidePgLoader.call(this)
    throw new Error(`message: ${message}, code: ${code}`)
  }

  return {
    refreshDefaultCrashHistory,
    refreshAdvancedCrashHistory
  }
})()