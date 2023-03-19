import { doExport } from '@/export'
import { empty } from '@/helper'
import { refreshAdvancedCrashHistory } from '@/pages/crash/actions'

export const {
  getAdvancedHistoryObserver,
  getLimit,
  onExportExcelFile,
  showCountDownButton,
  showContentReloadingButton,
  showPgLoader,
  hideContentReloadingButton,
  hidePgLoader
} = (() => {
  function onExportExcelFile(event, type) {
    const exportBtn = event.currentTarget
    const { download, linkURI } = doExport.call(this, type)

    exportBtn.removeAttribute('href')
    exportBtn.removeAttribute('target')

    exportBtn.setAttribute('href', linkURI)
    exportBtn.setAttribute('target', '_blank')
    exportBtn.download = download
  }

  // Retorna o limite de página que podem ser recuperadas de acordo com o que o usuário informou na pesquisa do crash
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

  function showPgLoader() {
    this.pgLoaderEl.classList.add('pg-loader--show')
  }

  function hideContentReloadingButton() {
    this.refreshAdvancedBtn.innerHTML = ''
    this.refreshAdvancedBtn.insertAdjacentHTML('beforeend', this.innerHTML)
    this.refreshAdvancedBtn.classList.toggle('disabled')
    this.refreshAdvancedBtn.removeAttribute('disabled')
    this.innerHTML = null
  }

  function hidePgLoader() {
    this.pgLoaderEl.classList.remove('pg-loader--show')
  }

  // Função que faz o carregamento do infinity scroll do histórico avançado
  function getAdvancedHistoryObserver() {
    const observerEl = this.advancedHistoryEl.querySelector('div.card:last-child')
    const intersectionObserver = new IntersectionObserver(onObserveAdvancedHistory.call(this), { root: this.advancedHistoryEl, rootMargin: '0px', threshold: 0.3 })

    if (!empty(observerEl)) {
      intersectionObserver.observe(observerEl)
    }
  }

  // Função que faz parte da função acima de infinity scroll
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

  // Próxima página a ser carregada no infinity scroll
  function getNextPage() {
    const hasToClearList = false

    this.advancedHistoryPage += 1
    refreshAdvancedCrashHistory.call(this, hasToClearList)
  }

  return {
    getAdvancedHistoryObserver,
    getLimit,
    onExportExcelFile,
    showCountDownButton,
    showContentReloadingButton,
    showPgLoader,
    hideContentReloadingButton,
    hidePgLoader
  }
})()