import { format } from 'date-fns-tz'

export const {
  empty,
  serialize,
  parseFilters,
  copyTextToClipboard,
  makeElement,
  APP_URL,
  dateTimeZone,
  space,
  upperCase,
  upperCaseFirst,
  numericKeyboard
} = (() => {
  const APP_URL = () => {
    const protocol = window.location.href.slice(0, window.location.href.indexOf(':'))
    const protocolIndex = protocol === 'https' ? 8 : 7
    const baseURl = window.location.href.slice(protocolIndex).slice(0, window.location.href.slice(protocolIndex).indexOf('/'))

    return `${protocol}://${baseURl}`
  }

  const dateTimeZone = (date, dateFormat) => {
    return format(new Date(`${date}Z`), dateFormat, { timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone })
  }

  /**
   * Return true if variable is empty or false if not.
   * @param {Object} val - Value to be evaluated
   * @returns {boolean}
   */
  const empty = val => {
    if (typeof val === 'object') {
      if (val !== null) {
        const getObjectInstanceVal = () => Object.prototype.toString.call(val)
        const getArrayLength = () => val.length
        const getObjectLength = () => Object.keys(val).length
        const _default = () => val.valueOf()
        const getValueOf = () => {
          let valueOf
          const instanceList = [String, Boolean, Number]

          for (let instan of instanceList) {
            if (val instanceof instan) {
              valueOf = val.valueOf()
              break
            }
          }

          return valueOf
        }

        const objectList = {
          '[object Array]': getArrayLength,
          '[object Object]': getObjectLength,
          '[object NodeList]': getObjectLength,
          '[object RadioNodeList]': getObjectLength,
          '[object HTMLCollection]': getObjectLength,
          '[object DOMTokenList]': getObjectLength,
          '[object NamedNodeMap]': getObjectLength,
          '[object String]': getValueOf,
          '[object Boolean]': getValueOf,
          '[object Number]': getValueOf,
          'default': _default
        }

        val = (objectList[getObjectInstanceVal()] || objectList['default'])()
      }
    }

    return !val
  }

  /**
   * Serialize a json and return.
   * @param {Object} object - JSON to be serialized
   * @returns {string} - String serialized from object
   */
  const serialize = obj => {
    let encodedString = ''

    for (const prop in obj) {
      if (obj.hasOwnProperty(prop)) {
        const isEncodedStringEmpty = (encodedString.length === 0)

        encodedString += (isEncodedStringEmpty) ? '?' : '&'
        encodedString += encodeURI(prop + '=' + obj[prop])
      }
    }

    return encodedString
  }

  /**
   * Clear all fields inside a form
   * @param {Object} form - HTML Form to clear all fields
   */
  const cleanForm = form => {
    const isFormTag = form.tagName === 'FORM'

    if (isFormTag) form.reset()
  }

  const parseFilters = filters => {
    const keys = Object.keys(filters)
    const parsedFilters = {}

    keys.forEach(key => {
      if (key !== 'limit' || key !== 'page') {
        parsedFilters[key] = filters[key]
      }

      if (!empty(filters[key]) && key !== 'limit' && key !== 'page') {
        parsedFilters[`filter:${key}`] = filters[key]
      }
    })

    return serialize(parsedFilters)
  }


  /**
   * Return Promise with value true or false.
   * @typedef {Boolean} - true or false
   * @param {string} text - Given text to copy to clipboard.
   * @returns {Promise} - Promise
   */
  const copyTextToClipboard = (text) => {
    return new Promise((resolve, reject) => {
      if (!navigator.clipboard) {
        (async() => await fallbackCopyTextToClipboard.call(this, text))()
          .then(() => resolve(true))
          .catch(() => reject(false))

        return
      }

      navigator.clipboard.writeText(text)
        .then(() => resolve(true))
        .catch(() => reject(false))
    })
  }

  function fallbackCopyTextToClipboard(text) {
    return new Promise((resolve, reject) => {
      const textArea = document.createElement('textarea')
      textArea.value = text

      textArea.style.top = '0'
      textArea.style.left = '0'
      textArea.style.position = 'fixed'

      document.body.appendChild(textArea)
      textArea.focus()
      textArea.select()

      try {
        const successful = document.execCommand('copy')

        if (successful) {
          resolve(true)
        }
      } catch (err) {
        reject(false)
      }

      document.body.removeChild(textArea)
    })
  }

  /**
   * Return HTML element with attributes and return it.
   * @typedef {Object} HTML - HTML
   * @param {string} elementName - Given name to html element.
   * @param {Object.<string, string|number>} attributes - Attributes from HTML element.
   * @returns {HTML} - HTML element made
   */
  const makeElement = (elementName, attributes = {}) => {
    const isValidStringEl = typeof elementName === 'string' && Boolean(elementName)
    const isValidObjectAttr = typeof attributes === 'object' && Boolean(attributes)

    if (isValidStringEl && isValidObjectAttr) {
      const element = document.createElement(elementName)
      const attributeList = Object.entries(attributes)
      const defineElementAttr = ([key, value]) => element.setAttribute(key, value)

      attributeList.forEach(defineElementAttr)

      return element
    }

    return
  }

  /**
   * It convert the first letter of text in Upper Case
   */
  const upperCaseFirst = () => {
    const inputsEl = document.querySelectorAll('[data-js="first-uppercase"]')

    const patternUpperCaseFirst = /^[a-zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž∂ð]/
    const hasUpperCaseFirstInputsEl = inputsEl.length !== 0

    if (hasUpperCaseFirstInputsEl) {
      const defineUpperCaseFirstListener = inputEl => {
        const isInputEl = inputEl.tagName === 'INPUT'
        const isTextAreaEl = inputEl.tagName === 'TEXTAREA'

        if (isInputEl || isTextAreaEl) {
          inputEl.addEventListener('input', handleConvertToUpperCase)
        }
      }

      const handleConvertToUpperCase = event => {
        const inputEl = event.target
        const hasLetter = patternUpperCaseFirst.test(inputEl.value)

        if (hasLetter) {
          const focusStartPos = inputEl.selectionStart
          const focusEndPos = inputEl.selectionEnd

          inputEl.value = inputEl.value.replace(patternUpperCaseFirst, letter => letter.toUpperCase())

          inputEl.setSelectionRange(focusStartPos, focusEndPos)
        }
      }

      inputsEl.forEach(defineUpperCaseFirstListener)
    }
  }

  /**
   * It convert all text in Upper Case letters
   */
  const upperCase = () => {
    const inputsEl = document.querySelectorAll('[data-js="uppercase"]')
    const hasUpperCaseInputs = inputsEl.length !== 0

    if (hasUpperCaseInputs) {
      const handleConvertToUpperCase = event => {
        event.target.value = event.target.value.toUpperCase()
      }

      const defineUpperCaseListener = inputEl => {
        const isInputEl = inputEl.tagName === 'INPUT'
        const isTextAreaEl = inputEl.tagName === 'TEXTAREA'

        if (isInputEl || isTextAreaEl) inputEl.addEventListener('input', handleConvertToUpperCase)
      }

      inputsEl.forEach(defineUpperCaseListener)
    }
  }

  /**
   * It show numeric keyboard on mobile phones
   */
  const numericKeyboard = () => {
    const inputsEl = document.querySelectorAll('[data-js="numeric-keyboard"]')

    const isAppleBrowser = /iPhone|iPad|iPod/i.test(navigator.userAgent)
    const isFirefoxBrowser = /firefox/i.test(navigator.userAgent)
    const hasNumericInputs = inputsEl.length !== 0

    if (hasNumericInputs) {
      const handleNumericAttributes = inputEl => {
        const isInputEl = inputEl.tagName === 'INPUT'
        const isTextAreaEl = inputEl.tagName === 'TEXTAREA'

        if (isInputEl || isTextAreaEl) {
          inputEl.setAttribute('pattern', '[0-9\-]*')
          inputEl.setAttribute('inputmode', 'numeric')
          inputEl.removeAttribute('type')

          if (isAppleBrowser) {
            inputEl.removeAttribute('inputmode')
          }

          if (isFirefoxBrowser) {
            inputEl.removeAttribute('inputmode')
            inputEl.removeAttribute('pattern')
            inputEl.setAttribute('type', 'tel')
          }
        }
      }

      inputsEl.forEach(handleNumericAttributes)
    }
  }

  /**
   * It remove spaces in the begin and end of input text and textarea while typing.
   */
  const space = () => {
    const inputsEl = document.querySelectorAll('textarea, input[type="text"]')

    const defineInputEventListener = inputEl => {
      const handleRemoveSpaceOnInput = () => {
        const spacePattern = /^\s+/g
        const hasSpaceInBeginning = spacePattern.test(inputEl.value)

        if (hasSpaceInBeginning) {
          inputEl.value = inputEl.value.replace(spacePattern, '')
        }
      }

      const handleRemoveSpaceOnBlur = () => {
        inputEl.value = inputEl.value.trim()
      }

      inputEl.addEventListener('input', handleRemoveSpaceOnInput)
      inputEl.addEventListener('blur', handleRemoveSpaceOnBlur)
    }

    inputsEl.forEach(defineInputEventListener)
  }


  return {
    empty,
    serialize,
    cleanForm,
    parseFilters,
    copyTextToClipboard,
    makeElement,
    dateTimeZone,
    APP_URL: APP_URL,
    space: space,
    upperCase: upperCase,
    upperCaseFirst: upperCaseFirst,
    numericKeyboard: numericKeyboard
  }
})()