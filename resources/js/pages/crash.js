import App from '../app'
import { empty } from '../utilx'
import { defaultHistory } from '../api/crash-api'
import { renderDefaultHistoryView } from '../templates/crash-view'

App.Crash = (function() {
  function Crash() {
    this.defaultHistoryEl = document.querySelector('[data-js="default-history"]')
    this.refreshDefaultBtn = document.querySelector('#refresh-default')
  }

  Crash.prototype.init = function() {
    this.refreshDefaultBtn.addEventListener('click', event => refreshHistory.call(this, event))
  }

  function refreshHistory(event) {
    const refreshButton = event.target
    const refreshButtonName = refreshButton.getAttribute('id')
    const refreshList = { 'refresh-default': refreshDefault, 'refresh-advanced': refreshAdvanced }

    if(empty(refreshButtonName)) {
      return
    }

    countdownButton.call(this, refreshButton)
    refreshList[refreshButtonName]?.call(this) || null
  }

  async function refreshDefault() {
    const response = await defaultHistory()
    const data = response?.data

    if (!empty(data)) {
      renderDefaultHistoryView.call(this, data)
    }
  }

  function refreshAdvanced() {

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