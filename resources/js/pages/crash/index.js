import App from '@/app'
import { Modal, Dropdown } from 'bootstrap'

import { space, numericKeyboard, copyTextToClipboard } from '@/helper'
import { onExportExcelFile } from '@/pages/crash/features'
import { refreshDefaultCrashHistory, refreshAdvancedCrashHistory } from '@/pages/crash/actions'

import { indexUser } from '@/api/user'

/*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*\
  Classe JS que trata os dados a serem apresentados na tela de crash no arquivo crash.blade.php
\*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
App.Crash = (() => {

  // Seletores e variáveis globais recomenda-se colocar aqui
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

    this.clipboardLink = document.querySelector('[data-clipboard-text]')

    this.advancedHistoryPage = 0
    this.advancedHistoryLength = 0
    this.previousAdvancedHistoryList = null
    this.advancedHistoryLimit = 3500

    this.pgLoaderEl = document.querySelector('.pg-loader')
  }

  // Essa function é o Run inicial desse arquivo
  Crash.prototype.init = function() {
    const isClipboardLinkVisible = this.clipboardLink

    space()
    numericKeyboard()

    if (isClipboardLinkVisible) {
      this.clipboardLink.addEventListener('click', () => onCopyToClipBoard.call(this))
    }

    initCrashPage.call(this)

    this.refreshDefaultBtn.addEventListener('click', event => onRefreshHistory.call(this, event))
  }

  // Faz o init e já chama a API de user e armazena em uma variável global
  async function initCrashPage() {
    const { status, response, message, code } = await indexUser()

    if (status === 'ok') {
      this.user = response?.data?.user

      initCrashHistory.call(this)

      return
    }

    throw new Error(`message: ${message}, code: ${code}`)
  }

  // Função que chama o carregamento do histórico padrão e do histórico avançado caso o usuário seja avanaçado ou premium
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

  async function onCopyToClipBoard() {
    const code = this.clipboardLink.dataset.clipboardText
    const hasTextBeenCopied = await copyTextToClipboard(code)

    console.log(hasTextBeenCopied)

    if (hasTextBeenCopied) {
      const resultCopyEl = this.clipboardLink.nextElementSibling

      resultCopyEl.classList.remove('d-none')

      setTimeout(() => resultCopyEl.classList.add('d-none'), 5000)
    }
  }

  // Função parent que pode chamar o Histórico Avançado ou Histórico padrão, depedendo do botão que foi clicado pelo usuário
  function onRefreshHistory(event) {
    const refreshButton = event.currentTarget
    const refreshButtonName = refreshButton.getAttribute('id')
    const refreshList = { 'refresh-default': refreshDefaultCrashHistory, 'refresh-advanced': refreshAdvancedCrashHistory }

    refreshList[refreshButtonName]?.call(this) || null
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

  return Crash
})()

document.addEventListener('DOMContentLoaded', () => (new App.Crash()).init())