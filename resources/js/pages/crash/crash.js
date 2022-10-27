import App from '@/app'
import { Modal, Dropdown } from 'bootstrap'
import { doExport } from '@/export/export'
import { indexDefaultHistory, indexAdvancedHistory } from '@/api/crash'
import { indexUser } from '@/api/user'
import { renderDefaultCrashHistoryView, renderAdvancedCrashHistoryView } from '@/pages/crash/crash-view'

App.Crash = (function() {
  function Crash() {
    this.defaultHistoryEl = document.querySelector('[data-js="default-history"]')
    this.advancedHistoryEl = document.querySelector('[data-js="advanced-history"]')

    this.user = null

    this.startLogInput = document.querySelector('#start_log')
    this.endLogInput = document.querySelector('#end_log')
    this.limitLogInput = document.querySelector('#limit_log')
    this.defaultTotalRowsEl = document.querySelector('[data-js="default-total"]')
    this.advancedTotalRowsEl = document.querySelector('[data-js="advanced-total"]')

    this.refreshDefaultBtn = document.querySelector('#refresh-default')
    this.refreshAdvancedBtn = document.querySelector('#refresh-advanced')

    this.exportExcelBtn = document.querySelector('#export-excel')
    this.exportCsvBtn = document.querySelector('#export-csv')
  }

  Crash.prototype.init = function() {
    initCrashPage.call(this)

    this.refreshDefaultBtn.addEventListener('click', event => onRefreshHistory.call(this, event))
  }

  async function initCrashPage() {
    const { status, response, message, code } = await indexUser()

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
      this.startLogInput.addEventListener('keyup', event => onTypeCrashPoint.call(this, event))
      this.startLogInput.addEventListener('blur', event => onBlurCrashPoint.call(this, event))
      this.endLogInput.addEventListener('keyup', event => onTypeCrashPoint.call(this, event))
      this.endLogInput.addEventListener('blur', event => onBlurCrashPoint.call(this, event))
      this.limitLogInput.addEventListener('keyup', event => onTypeLimit.call(this, event))

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
    const { status, response, message, code } = await indexDefaultHistory()

    if (status === 'ok') {
      const defaultCrashHistoryList = response?.data?.default_history

      renderDefaultCrashHistoryView.call(this, defaultCrashHistoryList)

      return
    }

    throw new Error(`message: ${message}, code: ${code}`)
  }

  function onTypeCrashPoint(event) {
    const crashInput = event.target
    const crashPattern = /^[1-9]+\d*\.?\d{0,2}$/g
    const isCorretlyCrashPoint = crashPattern.test(crashInput.value)

    if (!isCorretlyCrashPoint) {
      const startWithDot = crashInput.value.startsWith('.')

      if(startWithDot) {
        crashInput.value = crashInput.value.slice(1, )

        return
      }

      crashInput.value = crashInput.value.slice(0, (crashInput.value.length - 1))
    }
  }

  function onBlurCrashPoint(event) {
    const crashInput = event.target
    const endsWithDot = crashInput.value.endsWith('.')

    if (endsWithDot) {
      crashInput.value = crashInput.value.slice(0, (crashInput.value.length - 1))
    }
  }

  function onTypeLimit(event) {
    const limitInput = event.target
    const limitPattern = /^[1-9]+\d*$/g
    const isCorretlyLimit = limitPattern.test(limitInput.value)

    if (!isCorretlyLimit) {
      limitInput.value = limitInput.value.slice(0, (limitInput.value.length - 1))
    }
  }

  async function refreshAdvancedCrashHistory() {
    showContentReloadingButton.call(this)
    const { status, response, message, code } = await indexAdvancedHistory.call(this, this.limitLogInput.value = this.limitLogInput.value || 300)

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
    const { download, linkURI } = doExport.call(this, type)

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