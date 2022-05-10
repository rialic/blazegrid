export const {
  defaultHistory,
  advancedHistory
} = (() => {
  const APP_URL = () => window.location.href.substring(0, window.location.href.lastIndexOf('/'))

  async function defaultHistory() {
    const url = `${APP_URL()}/crash/default-history`
    const response = await fetch(url)

    if (!response?.ok) {
      throw new Error(`message: ${response.statusText}, code: ${response.status}`)
    }

    return await response.json()
  }

  return {
    defaultHistory
  }
})()