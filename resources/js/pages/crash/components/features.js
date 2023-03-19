import { differenceInMinutes } from 'date-fns'
import { dateTimeZone, empty } from '@/helper'

export const {
  resetAdvancedCrashHistoryList,
  setPreviousAdvancedCrashHistoryList,
  getAdvancedCrashList,
  getLastItemsFromPrevioustList,
  getNextCrashRecord,
  getNextCrashOrderSequence
} = (() => {
  function resetAdvancedCrashHistoryList(data, hasToClearList) {
    if (hasToClearList) {
      this.advancedHistoryEl.scrollTop = 0
      this.advancedHistoryEl.innerHTML = ''
      this.advancedHistoryLength = 0
      this.previousAdvancedHistoryList = getLastItemsFromPrevioustList.call(this, data)

      return
    }
  }

  function setPreviousAdvancedCrashHistoryList(data) {
    if (!empty(data)) {
      this.previousAdvancedHistoryList.forEach(crash => data.unshift(crash))
      this.previousAdvancedHistoryList.forEach(crash => this.advancedHistoryEl.querySelector(`[data-js="cr-${crash.id}"]`)?.remove())
      this.advancedHistoryLength = this.advancedHistoryLength - this.previousAdvancedHistoryList.length

      this.previousAdvancedHistoryList = getLastItemsFromPrevioustList.call(this, data)
    }
  }

  // Função que trata literalmente de todas sequências, diferenças de tempo, diferença de passos, cores e horários apresentados no histórico avançado
  function getAdvancedCrashList(data) {
    let advancedCrashList = data.reduce((acc, crash, index, list) => {
      const nextData = getNextCrashRecord.call(this, index, list)
      const isCrashFilterApproved = (Number(crash.point) >= (Number(this.startLogInput.value)) && Number(crash.point) <= (this.endLogInput.value || 1000000))

      if (isCrashFilterApproved) {
        const newCrashedItem = {
          id: crash.uuid,
          point: crash.point,
          created_at_server: dateTimeZone(crash.created_at_server, 'dd/MM/yyyy HH:mm:ss'),
          diff_min: (!nextData) ? 0 : differenceInMinutes(new Date(crash.created_at_server), new Date(nextData.created_at_server)),
          diff_step: (!nextData) ? 0 : list.findIndex(recordItem => recordItem.uuid === nextData.uuid) - index,
          sequence: getNextCrashOrderSequence.call(this, index, list)
        }

        acc.push(newCrashedItem)

        return acc
      }

      return acc
    }, [])

    // Cut of limit to not exceed 3500
    if ((this.advancedHistoryLength + advancedCrashList.length) > this.advancedHistoryLimit) {
      const acceptableLimit = this.advancedHistoryLimit - this.advancedHistoryLength
      const cutOfLimit =  acceptableLimit - advancedCrashList.length

      advancedCrashList = advancedCrashList.slice(0, cutOfLimit)
    }

    return advancedCrashList
  }

  // Função que pega os dois últimos items da lista para serem incluídos na próxima lista, isso é feito para não perder nenhum item ao carregar novos dados
  function getLastItemsFromPrevioustList(data) {
    const advancedCrashList = [...data].reverse()
    const tempAdvancedCrashList = []
    const isFirstDataPositiveCrashPoint = Number(advancedCrashList[0].point) >= 2
    const isFirstDataNegativeCrashPoint = Number(advancedCrashList[0].point) < 2

    if (isFirstDataPositiveCrashPoint) {
      for (let index = 0; index <= (advancedCrashList.length - 1); index++) {
        const isFirstData = index === 0
        const isPositiveCrashPoint = Number(advancedCrashList[index].point) >= 2

        if (isFirstData || isPositiveCrashPoint) {
          tempAdvancedCrashList.push(advancedCrashList[index])

          continue
        }

        break
      }
    }

    if (isFirstDataNegativeCrashPoint) {
      for (let index = 0; index <= (advancedCrashList.length - 1); index++) {
        const isFirstData = index === 0
        const isNegativeCrashPoint = Number(advancedCrashList[index].point) < 2

        if (isFirstData || isNegativeCrashPoint) {
          tempAdvancedCrashList.push(advancedCrashList[index])

          continue
        }

        break
      }
    }

    return tempAdvancedCrashList
  }

  function getNextCrashRecord(currentIndex, list) {
    return list.filter((_, index) => index > currentIndex).find(crash => {
      return (Number(crash.point) >= (this.startLogInput.value || 0) && Number(crash.point) <= (this.endLogInput.value || 1000000))
    })
  }

  function getNextCrashOrderSequence(currentIndex, list) {
    const reducedCrashList = list.filter((_, index) => index > currentIndex)
    const currentExternalCrash = list[currentIndex]
    let sequence = 1

    for (let index = 0; index <= (reducedCrashList.length - 1); index++) {
      const currentListCrash = reducedCrashList[index]
      const hasExternalInternalCrashPositivePoint = (Number(currentExternalCrash.point) >= 2 && Number(currentListCrash.point) >= 2)
      const hasExternalInternalCrashNegativePoint = (Number(currentExternalCrash.point) < 2 && Number(currentListCrash.point) < 2)

      if (hasExternalInternalCrashPositivePoint || hasExternalInternalCrashNegativePoint) {
        sequence += 1

        continue
      }

      break
    }

    return sequence
  }

  return {
    resetAdvancedCrashHistoryList,
    setPreviousAdvancedCrashHistoryList,
    getAdvancedCrashList,
    getLastItemsFromPrevioustList,
    getNextCrashRecord,
    getNextCrashOrderSequence
  }
})()