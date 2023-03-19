import { VueRenderer } from '@tiptap/vue-2'
import tippy from 'tippy.js'
import BaseMentionList from '../components/BaseMentionList'
import spacetime from 'spacetime'
import { isFirstNode } from '@helpers/functions'

// store
import store from '@/store'
import axios from 'axios'

let isFirstNodeSelected = false
let isValidDate = false
let array = []

let currentQuery = ''
let queryLength = 0
let found = false
let timeout = null

const authorization = store.getters['ModuleAuth/token']
const { uuid: kbUuid } = store.getters['ModuleKbs/currentKb']

export default {
  char: '@',
  allowSpaces: true,
  items: async ({ query }) => {
    queryLength = query.length

    return array
      .filter((item) => item.title.toLowerCase().startsWith(query.toLowerCase()))
      .slice(0, 10)
  },
  command: ({ editor, range, props }) => {
    if (props.id) {
      found = true

      editor
        .chain()
        .focus()
        .deleteRange(range)
        .insertMentionItem(props.id)
        .run()
    } else {
      if (!props.length) removeEmpty(editor, range, props.query)
    }
  },
  render: () => {
    let component
    let popup

    return {
      onStart: (props) => {
        const { editor, range } = props
        const { state } = editor

        isFirstNodeSelected = isFirstNode(state)

        if (!props.clientRect || isFirstNodeSelected) {

        } else {
          component = new VueRenderer(BaseMentionList, {
            propsData: props,
            editor: props.editor
          })

          setInitialList(props, component)

          popup = tippy('body', {
            getReferenceClientRect: props.clientRect,
            appendTo: () => document.body,
            content: component.element,
            showOnCreate: true,
            interactive: true,
            trigger: 'manual',
            placement: 'bottom-start',
            onCreate (instance) {
              instance.popper.classList.add('mention-popper')
            },
            onHidden () {
              if (!found) {
                if (!currentQuery) {
                  editor
                    .chain()
                    .focus()
                    .deleteRange(range)
                    .run()
                }

                return true
              }
            }
          })
        }
      },
      onUpdate (props) {
        const { query } = props

        currentQuery = query

        if (!props.clientRect) {

        } else {
          popup[0].setProps({
            getReferenceClientRect: props.clientRect
          })

          if (!query) {
            setInitialList(props, component)
          }

          clearTimeout(timeout)

          timeout = setTimeout(async () => {
            searchMentions(query, props, component)
          }, 500)

          component.updateProps(props)
        }
      },
      onKeyDown (props) {
        const { event } = props

        if (event.key === 'Escape') {
          found = false

          popup[0].hide()

          return true
        }

        return component.ref?.onKeyDown(props)
      },
      onExit () {
        popup[0].destroy()
        component.destroy()
      }
    }
  }
}

// Dates
function parseDate (props) {
  const { query } = props
  const spplitedDate = query.split(' ')

  // periods
  const month = spplitedDate[0]
  let day = spplitedDate[1]
  let year = spplitedDate[2]
  let hour = spplitedDate[3]

  let currentDateQuery = ''
  let completeDate = ''

  // check contains day
  if (!spplitedDate[1]) day = 1
  else day = spplitedDate[1].replace(/,/g, '')

  // verifica se tem ano
  if (!spplitedDate[2]) year = spacetime.now().format('year')
  else year = spplitedDate[2].replace(/,/g, '')

  if (!spplitedDate[3]) hour = '9:00AM'
  else hour = spplitedDate[3].replace(/,/g, '')

  currentDateQuery = `${month} ${day}, ${year} ${hour}`

  hour = spacetime(currentDateQuery).format('time')

  completeDate = `${month} ${day}, ${year} ${hour}`

  isValidDate = spacetime(completeDate).isValid()

  const teste = `${year}-${month}-${day} ${hour}`
  const isoDate = spacetime(teste).format('iso')

  return { completeDate, isoDate }
}

// Search queries for articles and users
async function searchMentions (query, props, component) {
  const { completeDate, isoDate } = parseDate(props)

  let result = []

  if (isValidDate) {
    const newDate = {
      title: completeDate,
      type: 'date',
      isoDate,
      id: result.length + 1
    }

    result.push(newDate)
  } else {
    // Setting initital dates
    const initialDate = []
    const match = ['today', 'tomorrow']

    const dates = match.filter(item => item.toLowerCase().includes(query.toLowerCase()))

    dates.forEach(item => {
      switch (item) {
        case 'today':
          const today = spacetime.now().unixFmt('MM dd, yyyy')

          initialDate.push({
            title: `Today ${today}`,
            isoDate: spacetime.now().format('iso'),
            type: 'date'
          })
          break
        case 'tomorrow':
          initialDate.push({
            title: 'Tommorrow',
            isoDate: spacetime.now().add(1, 'day').format('iso'),
            type: 'date'
          })
          break
      }
    })

    initialDate.forEach((item, index) => { result.push({ ...item, id: result.length + index }) })
  }

  if (query.length) {
    await axios.get(`/api/v1/kb/${kbUuid}/users`, {
      headers: { authorization },
      params: { search: query }
    }).then(async response => {
      result = resultPush(response, result)
    })

    await axios.get(`/api/v1/kb/${kbUuid}/articles`, {
      headers: { authorization },
      params: { search: query }
    }).then(async response => {
      responseData(response, result, component, props)
    })
  }
}

function resultPush (response, result) {
  const resultArray = result
  response.data.forEach((item, index) => {
    const newuser = {
      title: item.full_name,
      email: item.email,
      image: item.avatar,
      type: 'user',
      id: array.length + index
    }
    resultArray.push(newuser)
  })
  return resultArray
}

async function setInitialList (props, component) {
  resetVariables()

  let result = []

  // Setting initital dates
  const today = spacetime.now().unixFmt('MM dd, yyyy')

  const initialDate = [
    {
      title: `Today ${today}`,
      isoDate: spacetime.now().format('iso'),
      type: 'date'
    },
    {
      title: 'Tommorrow',
      isoDate: spacetime.now().add(1, 'day').format('iso'),
      type: 'date'
    }
  ]

  initialDate.forEach((item, index) => { result.push({ ...item, id: result.length + index }) })

  await axios.get(`/api/v1/kb/${kbUuid}/users`, {
    headers: { authorization },
    params: {
      order: 'ASC',
      limit: 4
    }
  }).then(async response => {
    result = resultPush(response, result)
  })

  await axios.get(`/api/v1/kb/${kbUuid}/articles`, {
    headers: { authorization },
    params: {
      order: 'ASC',
      limit: 4
    }
  }).then(async response => {
    responseData(response, result, component, props)
  })
}

function resetVariables () {
  isFirstNodeSelected = false
  isValidDate = false
  array = []
  currentQuery = ''
  queryLength = 0
  found = false
  timeout = null
}

function removeEmpty (editor, range, text) {
  editor
    .chain()
    .focus()
    .deleteRange(range)
    .insertContent(text)
    .run()
}

function responseData (response, result, component, props) {
  response.data.forEach((item, index) => {
    const newObj = {
      uuid: item.uuid,
      title: item.title,
      type: 'article',
      path: `/system/kb/${kbUuid}/articles/${item.uuid}`,
      id: array.length + index
    }
    result.push(newObj)
  })

  component.updateProps({
    ...props,
    items: [
      ...props.items,
      ...(result.map((item, index) => ({ ...item, id: array.length + index })))]
  })
}