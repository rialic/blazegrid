import Fetch from '@/fetch'
import { APP_URL } from '@/helper'

export const {
  indexUser
} = (() => ({
  indexUser: async () => await Fetch.get.call(this, `${APP_URL()}/user`)
}))()