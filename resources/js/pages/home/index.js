import App from '@/app'
import { Modal, Dropdown } from 'bootstrap'
import { copyTextToClipboard } from '@/helper'

App.Home = (() => {
  function Home() {
    this.images = document.querySelectorAll('img.img-thumbnail')
    this.gallery = document.querySelector('.gallery')
    this.imageViewer = document.querySelector('.image-viewer')
    this.imageViewerContent = document.querySelector('.image-viewer\\:content')
    this.imageViewerClose = document.querySelector('.image-viewer .close')

    this.clipboardLink = document.querySelector('[data-clipboard-text]')
  }

  Home.prototype.init = function() {
    const isClipboardLinkVisible = this.clipboardLink

    if (isClipboardLinkVisible) {
      this.clipboardLink.addEventListener('click', () => onCopyToClipBoard.call(this))
    }

    this.gallery.addEventListener('click', (event) => onShowViewer.call(this, event))
    this.imageViewerClose.addEventListener('click', () => onHideViewer.call(this))
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

  function onShowViewer(event) {
    const element = event.target
    const isGalleryImage = Array.from(element.classList).includes('gallery:image')

    if (isGalleryImage) {
      const sourceImageURl = element.getAttribute('src')

      this.imageViewer.classList.add('image-viewer--show')
      this.imageViewerContent.setAttribute('src', sourceImageURl)
    }
  }

  function onHideViewer() {
    this.imageViewer.classList.remove('image-viewer--show')
  }

  return Home
})()

document.addEventListener('DOMContentLoaded', () => (new App.Home()).init())