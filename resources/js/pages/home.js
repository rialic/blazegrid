import App from '../app'
import { Modal, Dropdown } from 'bootstrap'
import { copyTextToClipboard } from '../utilx'

App.Home = (function() {

  function Home() {
    this.clipboardLink = document.querySelector('[data-clipboard-text]')
    this.images = document.querySelectorAll('img.img-thumbnail')
    this.modalImage = document.querySelector('.modal-image')
    this.modalImageClose = document.querySelector('.modal-image-close')
    this.modalImageContent = document.querySelector('.modal-image\\:content')
  }

  Home.prototype.init = function() {
    const isClipboardLinkVisible = this.clipboardLink

    if (isClipboardLinkVisible) {
      this.clipboardLink.addEventListener('click', () => onCopyToClipBoard.call(this))
    }

    this.images.forEach(image => image.addEventListener('click', event => onShowImageModal.call(this, event)))
    this.modalImageClose.addEventListener('click', () => onHideImageModal.call(this))
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

  function onShowImageModal(event) {
    const image = event.target

    this.modalImage.style.display = 'block'
    this.modalImageContent.src = image.src
  }

  function onHideImageModal() {
    this.modalImage.style.display = 'none'
  }

  return Home
})()

document.addEventListener('DOMContentLoaded', () => (new App.Home()).init())