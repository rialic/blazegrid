export const {
  defaultHistory,
  advancedHistory
} = (() => {
  const APP_URL = () => window.location.href.substring(0, window.location.href.lastIndexOf('/'))

  async function fetchData(url) {
    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})

    if (!response?.ok) {
      return { status: 'error', message: response.statusText, code: response.status }
    }

    return { status: 'ok', response: await response.json() }
  }

  async function defaultHistory() {
    const url = `${APP_URL()}/crash/default-history`

    return await fetchData.call(this, url)
  }

  async function advancedHistory(limit) {
    const url = `${APP_URL()}/crash/advanced-history/${limit}`

    return await fetchData.call(this, url)
  }

  return {
    defaultHistory,
    advancedHistory
  }
})()