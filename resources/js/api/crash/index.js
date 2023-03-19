import Fetch from '@/fetch'
import { APP_URL } from '@/helper'

export const {
  indexDefaultHistory,
  indexAdvancedHistory
} = (() => ({
  indexDefaultHistory: async () => await Fetch.get.call(this, `${APP_URL()}/crash/default-history`),
  indexAdvancedHistory: async (params) => await Fetch.get.call(this, `${APP_URL()}/crash/advanced-history/${params}`)
}))()