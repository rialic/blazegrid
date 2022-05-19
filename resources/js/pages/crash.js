import App from '../app'
import { Modal, Dropdown } from 'bootstrap'
import { exportFromTable } from '../export/export'
import { defaultHistory, advancedHistory } from '../api/crash-api'
import { user } from '../api/user-api'
import { renderDefaultCrashHistoryView, renderAdvancedCrashHistoryView } from '../templates/crash-view'

App.Crash = (function() {
  function Crash() {
    this.defaultHistoryEl = document.querySelector('[data-js="default-history"]')

    this.user = null

    this.startLogInput = document.querySelector('#start_log')
    this.endLogInput = document.querySelector('#end_log')
    this.limitLogInput = document.querySelector('#limit_log')
    this.totalRowsEl = document.querySelector('[data-js="total"]')

    this.refreshDefaultBtn = document.querySelector('#refresh-default')
    this.refreshAdvancedBtn = document.querySelector('#refresh-advanced')

    this.exportExcelBtn = document.querySelector('#export-excel')
    this.exportCsvBtn = document.querySelector('#export-csv')

    this.crashTable = document.querySelector('[data-js="table-crash"]')
  }

  Crash.prototype.init = function() {
    initCrashPage.call(this)

    this.refreshDefaultBtn.addEventListener('click', event => onRefreshHistory.call(this, event))
  }

  async function initCrashPage() {
    const { status, response, message, code } = await user()

    if (status === 'ok') {
      this.user = response?.data?.user

      initCrashHistory.call(this)

      return
    }

    throw new Error(`message: ${message}, code: ${code}`)
  }

  function initCrashHistory() {
    refreshDefaultCrashHistory.call(this)

    if(this.user.plan.name.toLowerCase() !== 'basic') {
      this.refreshAdvancedBtn.addEventListener('click', event => onRefreshHistory.call(this, event))
      this.exportExcelBtn.addEventListener('click', event => onExportExcelFile.call(this, event, 'excel'))
      this.exportCsvBtn.addEventListener('click', event => onExportExcelFile.call(this, event, 'csv'))

      refreshAdvancedCrashHistory.call(this)
    }
  }

  function onRefreshHistory(event) {
    const refreshButton = event.currentTarget
    const refreshButtonName = refreshButton.getAttribute('id')
    const refreshList = { 'refresh-default': refreshDefaultCrashHistory, 'refresh-advanced': refreshAdvancedCrashHistory }

    refreshList[refreshButtonName]?.call(this) || null
  }

  async function refreshDefaultCrashHistory() {
    showCountDownButton.call(this, this.refreshDefaultBtn)
    const { status, response, message, code } = await defaultHistory()

    if (status === 'ok') {
      const defaultCrashHistoryList = response?.data?.default_history

      renderDefaultCrashHistoryView.call(this, defaultCrashHistoryList)

      return
    }

    throw new Error(`message: ${message}, code: ${code}`)
  }

  async function refreshAdvancedCrashHistory() {
    // this.limitLogInput.value = 300
    showContentReloadingButton.call(this)
    const { status, response, message, code } = await advancedHistory.call(this, this.limitLogInput.value = this.limitLogInput.value || 300)

    if (status === 'ok') {
      const advancedCrashHistoryList = response?.data?.advanced_history

      renderAdvancedCrashHistoryView.call(this, advancedCrashHistoryList)
      hideContentReloadingButton.call(this)

      return
    }

    hideContentReloadingButton.call(this)

    throw new Error(`message: ${message}, code: ${code}`)
  }

  function onExportExcelFile(event, type) {
    const exportBtn = event.currentTarget
    const { download, linkURI } = exportFromTable.call(this, this.crashTable, type)

    exportBtn.removeAttribute('href')
    exportBtn.removeAttribute('target')

    exportBtn.setAttribute('href', linkURI)
    exportBtn.setAttribute('target', '_blank')
    exportBtn.download = download
  }

  function showCountDownButton(button) {
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

  function showContentReloadingButton() {
    this.innerHTML = this.refreshAdvancedBtn.innerHTML.trim()
    const reloadingIcon = '<i class="fa-solid fa-arrows-rotate fa-spin"></i>'

    this.refreshAdvancedBtn.innerHTML = ''
    this.refreshAdvancedBtn.setAttribute('disabled', 'disabled')
    this.refreshAdvancedBtn.classList.toggle('disabled')
    this.refreshAdvancedBtn.insertAdjacentHTML('beforeend', reloadingIcon)
  }

  function hideContentReloadingButton() {
    this.refreshAdvancedBtn.innerHTML = ''
    this.refreshAdvancedBtn.insertAdjacentHTML('beforeend', this.innerHTML)
    this.refreshAdvancedBtn.classList.toggle('disabled')
    this.refreshAdvancedBtn.removeAttribute('disabled')
    this.innerHTML = null
  }

  return Crash
})()

document.addEventListener('DOMContentLoaded', () => (new App.Crash()).init())