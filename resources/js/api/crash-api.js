export const {
  defaultHistory,
  advancedHistory
} = (() => {
  const APP_URL = () => window.location.href.substring(0, window.location.href.lastIndexOf('/'))

  async function fetchData(url) {
    const response = await fetch(url)

    if (!response?.ok) {
      throw new Error(`message: ${response.statusText}, code: ${response.status}`)
    }

    return await response.json()
  }

  function defaultHistory() {
    const url = `${APP_URL()}/crash/default-history`

    return fetchData(url)
  }

  function advancedHistory(limit) {
    const url = `${APP_URL()}/crash/advanced-history/${limit}`

    return fetchData(url)
  }

  return {
    defaultHistory,
    advancedHistory
  }
})()