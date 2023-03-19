import { dateTimeZone } from '@/helper'
import { getNextCrashOrderSequence } from '@/pages/crash/components/features'


export default (() => {
  function getDefaultCrashHTMLString() {
    return (acc, crash, index, list) => {
      const isFirstData = index === 0
      const { point, created_at_server } = crash
      const crashColor = (crash.point >= 2) ? 'success' : 'secondary'

      return acc += `
      <div class="position-relative mt-1 ${isFirstData ? 'ms-1 me-3' : 'mx-1'} fs-20">
        <div class="px-1 badge bg-${crashColor} bg-gradient">
          <span class="d-block">${point}</span>
          <span class="d-block mt-1">${dateTimeZone(created_at_server, 'HH:mm:ss')}</span>
        </div>

        ${this.user.plan.name.toLowerCase() !== 'basic' ? getCrashOrderSequenceHTMLString.call(this, crashColor, index, list) : ''}
      </div>
      `
    }
  }

  function getCrashOrderSequenceHTMLString(crashColor, index, list) {
    return `
    <div class="position-absolute top-0 start-100 translate-middle fw-bold text-white bg-${crashColor} border border-2 border-white px-1 rounded fs-12 border-${crashColor}">
      ${getNextCrashOrderSequence.call(this, index, list)}
    </div>
    `
  }

  return getDefaultCrashHTMLString
})()