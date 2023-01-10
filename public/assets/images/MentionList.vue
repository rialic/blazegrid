<template>
  <div class="items">
    <div v-if="items.length">
      <div class="reminder" v-if="filteredReminder.length">
        <p class="list-type-title">Date</p>
        <button
          class="mention-list-item"
          :id="`item-${item.type}-${index}`"
          :class="{ 'is-selected': item.id === selectedIndex }"
          v-for="(item, index) in filteredReminder"
          :key="index"
          @click="selectItem(index, item.type)"
        >
          <span class="ml-2">{{ item.title }}</span>
        </button>
      </div>

      <div class="users" v-if="filteredUsers.length">
        <p class="list-type-title">Users</p>
        <button
          class="mention-list-item"
          v-tippy
          :content="item.email"
          :id="`item-${item.type}-${index}`"
          :class="{ 'is-selected':  item.id === selectedIndex }"
          v-for="(item, index) in filteredUsers"
          :key="index"
          @click="selectItem(index, item.type)"
        >
          <span class="d-flex">
            <BaseAvatarIcon
              propSizeMediaQuery="sm"
              :propUsername="item.title"
              :propSrcImage="item.image"
            />
            <span class="ml-2">{{ item.title }}</span>
          </span>
        </button>
      </div>

      <div class="articles" v-if="filteredArticles.length">
        <p class="list-type-title">Articles</p>
        <button
          class="mention-list-item"
          v-tippy
          :content="item.title"
          :id="`item-${item.type}-${index}`"
          :class="{ 'is-selected':  item.id === selectedIndex }"
          v-for="(item, index) in filteredArticles"
          :key="index"
          @click="selectItem(index, item.type)"
        >
          <span class="d-flex">
            <span class="ml-2">{{ item.title }}</span>
          </span>
        </button>
      </div>
    </div>

    <div class="no-result" v-else>
      <span class="list-type-title">No results</span>
    </div>
  </div>
</template>

<script>
import '@/plugins/vue-tippy'

export default {
  name: 'base-mention-list',
  props: {
    items: {
      type: Array,
      required: true
    },
    command: {
      type: Function,
      required: true
    },
    query: {
      type: String,
      default: ''
    }
  },
  mixins: [],
  data () {
    return {
      selectedIndex: 0,
      filtered: [],
      isHandler: false
    }
  },
  computed: {
    filteredReminder () {
      return this.items.filter((item) => item.type === 'date')
    },
    filteredUsers () {
      return this.items.filter((item) => item.type === 'user')
    },
    filteredArticles () {
      return this.items.filter((item) => item.type === 'article')
    }
  },
  watch: {
    query (value) {
      this.isFiltering = false
      if (value) this.isFiltering = true
    },
    items (value) {
      this.filtered = value
      this.selectedIndex = 0
    }
  },
  mounted () {
    this.filtered = this.items
  },
  methods: {
    onKeyDown ({ event }) {
      if (event.key === 'ArrowUp') {
        this.upHandler()
        this.isHandler = false
        return true
      }

      if (event.key === 'ArrowDown') {
        this.downHandler()
        this.isHandler = false
        return true
      }

      if (event.key === 'Enter') {
        this.enterHandler()
        return true
      }

      return false
    },
    upHandler () {
      this.selectedIndex = ((this.selectedIndex + this.items.length) - 1) % this.items.length
    },
    downHandler () {
      this.selectedIndex = (this.selectedIndex + 1) % (this.items.length + 1)
    },
    enterHandler () {
      this.isHandler = true
      this.selectItem(this.selectedIndex)
    },
    async selectItem (index, type) {
      let item = null

      if (this.isHandler) {
        item = this.filtered[index]

        if (item) {
          this.command({ id: item })
          this.isHandler = false
        } else {
          this.command({ id: null, query: this.query, length: this.filtered.length })
        }
      } else {
        if (type === 'user') item = this.filteredUsers[index]
        else if (type === 'date') item = this.filteredReminder[index]
        else item = this.filteredArticles[index]

        if (item) {
          this.command({ id: item })
          this.isHandler = false
        }
      }
    }
  },
  components: {
    BaseAvatarIcon: () => import('@components/fragments/BaseAvatarIcon')
  }
}
</script>