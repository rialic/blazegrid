export const {
  user,
} = (() => {
  const APP_URL = () => window.location.href.substring(0, window.location.href.lastIndexOf('/'))

  async function fetchData(url) {
    const response = await fetch(url)

    if (!response?.ok) {
      return { status: 'error', message: response.statusText, code: response.status }
    }

    return { status: 'ok', response: await response.json() }
  }

  async function user() {
    const url = `${APP_URL()}/user`

    return await fetchData.call(this, url)
  }

  return {
    user,
  }
})()