import App from '@/app'
import { Modal, Dropdown } from 'bootstrap'
import { doExport } from '@/export/export'
import { indexDefaultHistory, indexAdvancedHistory } from '@/api/crash'
import { indexUser } from '@/api/user'
import { empty, space, numericKeyboard, parseFilters } from '@/utilx'
import { renderDefaultCrashHistoryView, renderAdvancedCrashHistoryView } from '@/pages/crash/crash-view'

App.Crash = (function() {
  function Crash() {
    this.defaultHistoryEl = document.querySelector('[data-js="default-history"]')
    this.advancedHistoryEl = document.querySelector('[data-js="advanced-history"]')

    this.user = null

    this.startLogInput = document.querySelector('#start_log')
    this.endLogInput = document.querySelector('#end_log')
    this.defaultTotalRowsEl = document.querySelector('[data-js="default-total"]')
    this.advancedTotalRowsEl = document.querySelector('[data-js="advanced-total"]')

    this.refreshDefaultBtn = document.querySelector('#refresh-default')
    this.refreshAdvancedBtn = document.querySelector('#refresh-advanced')

    this.exportExcelBtn = document.querySelector('#export-excel')
    this.exportCsvBtn = document.querySelector('#export-csv')

    this.advancedHistoryPage = 0
    this.advancedHistoryLength = 0
    this.previousAdvancedHistoryList = null
    this.advancedHistoryLimit = 3500

    this.pgLoaderEl = document.querySelector('.pg-loader')
  }

  Crash.prototype.init = function() {
    space()
    numericKeyboard()

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

      this.refreshAdvancedBtn.addEventListener('click', event => onRefreshHistory.call(this, event))

      this.exportExcelBtn.addEventListener('click', event => onExportExcelFile.call(this, event, 'excel'))
      this.exportCsvBtn.addEventListener('click', event => onExportExcelFile.call(this, event, 'csv'))

      refreshAdvancedCrashHistory.call(this)
    }
  }

  function initAdvancedHistoryObserver() {
    const observerEl = this.advancedHistoryEl.querySelector('div.card:last-child')
    const intersectionObserver = new IntersectionObserver(onObserveAdvancedHistory.call(this), { root: this.advancedHistoryEl, rootMargin: '0px', threshold: 0.3 })

    if (!empty(observerEl)) {
      intersectionObserver.observe(observerEl)
    }
  }

  function onObserveAdvancedHistory() {
    return (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          showPgLoader.call(this)
          getNextPage.call(this)

          observer.unobserve(entry.target)
        }
      })
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

  async function refreshAdvancedCrashHistory(hasToClearList = true) {
    const params = { limit: getLimit.call(this), page: ((hasToClearList) ? this.advancedHistoryPage = 1 : this.advancedHistoryPage) }
    const { data } = await getAdvancedHistoryData.call(this, params)
    const advancedCrashHistoryList = data

    renderAdvancedCrashHistoryView.call(this, advancedCrashHistoryList, hasToClearList)
    hideContentReloadingButton.call(this)
    hidePgLoader.call(this)

    if (this.advancedHistoryEl.querySelectorAll('div.card').length <= 5) {
      return
    }

    if (!empty(data) && this.advancedHistoryLength < 3500) {
      initAdvancedHistoryObserver.call(this)
    }
  }

  function getNextPage() {
    const hasToClearList = false

    this.advancedHistoryPage += 1
    refreshAdvancedCrashHistory.call(this, hasToClearList)
  }

  function getLimit(){
    const gt = (element, num) => (Number(element.value) > num && Number(element.value) !== 0) // Greater Than
    const lte = (element, num) => (Number(element.value) <= num && Number(element.value) !== 0) // Less Than or Equal

    if (empty(this.startLogInput.value) && empty(this.endLogInput.value)) {
      return 350
    }

    if (lte(this.startLogInput, 10) || lte(this.endLogInput, 10)) {
      return 875
    }

    if ((gt(this.startLogInput, 10) && lte(this.startLogInput, 20)) || (gt(this.endLogInput, 10) && lte(this.endLogInput, 20))) {
      return 1750
    }

    return this.advancedHistoryLimit
  }

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

  function showPgLoader() {
    this.pgLoaderEl.classList.add('pg-loader--show')
  }

  function hidePgLoader() {
    this.pgLoaderEl.classList.remove('pg-loader--show')
  }

  return Crash
})()

document.addEventListener('DOMContentLoaded', () => (new App.Crash()).init())