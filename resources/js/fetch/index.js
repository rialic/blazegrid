export default (() => {
  async function get(url) {
    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})

    if (!response?.ok) {
      return { status: 'error', message: response.statusText, code: response.status }
    }

    return { status: 'ok', response: await response.json() }
  }

  return {
    get
  }
})()