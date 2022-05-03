import App from '../app'
import { Modal } from 'bootstrap'
import { copyTextToClipboard } from '../utilx'

App.Home = (function() {

  function Home() {
    this.clipboardLink = document.querySelector('[data-clipboard-text]')
  }

  Home.prototype.init = function() {
    const isClipboardLinkVisible = this.clipboardLink

    if (isClipboardLinkVisible) {
      this.clipboardLink.addEventListener('click', () => onCopyToClipBoard.call(this))
    }
  }

  async function onCopyToClipBoard() {
    const code = this.clipboardLink.dataset.clipboardText
    const hasTextBeenCopied = await copyTextToClipboard(code)

    if (hasTextBeenCopied) {
      const resultCopyEl = this.clipboardLink.nextElementSibling

      resultCopyEl.classList.remove('d-none')

      setTimeout(() => resultCopyEl.classList.add('d-none'), 5000)
    }
  }

  return Home
})()

document.addEventListener('DOMContentLoaded', () => (new App.Home()).init())