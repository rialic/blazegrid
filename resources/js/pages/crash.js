import App from '../app'
import { empty } from '../utilx'
import { defaultHistory, advancedHistory } from '../api/crash-api'
import { renderDefaultHistoryView, renderAdvancedHistoryView } from '../templates/crash-view'

App.Crash = (function() {
  function Crash() {
    this.defaultHistoryEl = document.querySelector('[data-js="default-history"]')

    this.startLogInput = document.querySelector('#start_log')
    this.endLogInput = document.querySelector('#end_log')
    this.limitLogInput = document.querySelector('#limit_log')

    this.refreshDefaultBtn = document.querySelector('#refresh-default')
    this.refreshAdvancedBtn = document.querySelector('#refresh-advanced')
  }

  Crash.prototype.init = function() {
    initCrashHistory.call(this)

    this.refreshDefaultBtn.addEventListener('click', event => refreshHistory.call(this, event))
    this.refreshAdvancedBtn.addEventListener('click', event => refreshHistory.call(this, event))
  }

  function initCrashHistory() {
    refreshDefault.call(this)
    refreshAdvanced.call(this)
  }

  function refreshHistory(event) {
    const refreshButton = event.currentTarget
    const refreshButtonName = refreshButton.getAttribute('id')
    const refreshList = { 'refresh-default': refreshDefault, 'refresh-advanced': refreshAdvanced }

    if (refreshButtonName === 'refresh-default') {
      countdownButton.call(this, refreshButton)
    }
    refreshList[refreshButtonName]?.call(this, (this.limitLogInput.value || 300)) || null
  }

  async function refreshDefault() {
    const response = await defaultHistory()
    const defaultHistoryList = response?.data?.default_history
    const user = response?.data?.user

    if (!empty(defaultHistoryList)) {
      renderDefaultHistoryView.call(this, defaultHistoryList, user)
    }
  }

  async function refreshAdvanced(limit = 300) {
    const response = await advancedHistory(limit)
    const advancedHistoryList = response?.data?.advanced_history

    if (!empty(advancedHistoryList)) {
      renderAdvancedHistoryView.call(this, advancedHistoryList)
    }
  }

  function countdownButton(button) {
    let counter = 5
    const innerHTMLButton = button.innerHTML.trim()
    const invertalId = setInterval(() => {
      button.textContent = `Carregando ${counter--}`
    }, 1000)

    button.textContent = `Carregando ${counter--}`
    button.classList.toggle('disabled')

    setTimeout(() => {
      clearInterval(invertalId)
      button.innerHTML = innerHTMLButton
      button.classList.toggle('disabled')
    }, 5000)
  }

  return Crash
})()

document.addEventListener('DOMContentLoaded', () => (new App.Crash()).init())