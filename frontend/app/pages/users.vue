<template>
  <UDashboardPage>
    <UDashboardPanel grow>
      <UDashboardNavbar
        title="Users"
        :badge="users.length"
      >
        <template #right>
          <UInput
            ref="input"
            v-model="q"
            icon="i-heroicons-funnel"
            autocomplete="off"
            placeholder="Filter users..."
            class="hidden lg:block"
            @keydown.esc="$event.target.blur()"
          >
            <template #trailing>
              <UKbd value="/" />
            </template>
          </UInput>

          <UButton
            label="New user"
            trailing-icon="i-heroicons-plus"
            color="gray"
            @click="isNewUserModalOpen = true"
          />
        </template>
      </UDashboardNavbar>

      <UDashboardToolbar>
        <template #left>
          <USelectMenu
            v-model="selectedStatuses"
            icon="i-heroicons-check-circle"
            placeholder="Status"
            multiple
            :options="defaultStatuses"
            :ui-menu="{ option: { base: 'capitalize' } }"
          />
          <USelectMenu
            v-model="selectedLocations"
            icon="i-heroicons-map-pin"
            placeholder="Location"
            :options="defaultLocations"
            multiple
          />
        </template>

        <template #right>
          <USelectMenu
            v-model="selectedColumns"
            icon="i-heroicons-adjustments-horizontal-solid"
            :options="defaultColumns"
            multiple
            class="hidden lg:block"
          >
            <template #label>
              Display
            </template>
          </USelectMenu>
        </template>
      </UDashboardToolbar>

      <UDashboardModal
        v-model="isNewUserModalOpen"
        title="New user"
        description="Add a new user to your database"
        :ui="{ width: 'sm:max-w-md' }"
      >
        <!-- ~/components/users/UsersForm.vue -->
        <UsersForm @close="isNewUserModalOpen = false" />
      </UDashboardModal>

      <UTable
        v-model="selected"
        v-model:sort="sort"
        :rows="users"
        :columns="columns"
        :loading="pending"
        sort-mode="manual"
        class="w-full"
        :ui="{ divide: 'divide-gray-200 dark:divide-gray-800' }"
        @select="onSelect"
      >
        <template #name-data="{ row }">
          <div class="flex items-center gap-3">
            <span class="text-gray-900 dark:text-white font-medium">
              {{ `${row.firstName} ${row.lastName}` }}
            </span>
          </div>
        </template>

        <template #position-data="{ row }">
          <div class="flex items-center gap-3">
            <span class="text-gray-900 dark:text-white font-medium">
              {{ `${row.userTypeDescription}` }}
            </span>
          </div>
        </template>

        <template #status-data="{ row }">
          <UBadge
            :label="Number(row.status) === 1 ? 'Active' : 'Inactive'"
            :color="Number(row.status) === 1 ? 'green' : 'red'"
            variant="subtle"
            class="capitalize"
          />
        </template>
      </UTable>
    </UDashboardPanel>
  </UDashboardPage>
</template>

<script>
const api = useApi()

definePageMeta({
  layout: 'user'
})

defineShortcuts({
  '/': () => {
    input.value?.input?.focus()
  }
})

const defaultColumns = [{
  key: 'id',
  label: '#'
}, {
  key: 'name',
  label: 'Name',
  sortable: true
}, {
  key: 'username',
  label: 'Username',
  sortable: true
}, {
  key: 'position',
  label: 'Position'
}, {
  key: 'status',
  label: 'Status'
}]

export default {
  data() {
    return {
      q: "",
      selected: [],
      selectedColumns: defaultColumns,
      selectedStatuses: [],
      selectedLocations: [],
      sort: { column: 'id', direction: 'asc'},
      input: "",
      isNewUserModalOpen: false,
      users: []
    }
  },
  computed: {
    columns(){
      return defaultColumns.filter(column => this.selectedColumns.includes(column))
    }
  },
  created(){
    this.getList();
  },
  methods:{
    async getList(){
      this.users = []
      api.get("users/getUsersList").then((res) => {
        let response = {...res.data}
        if(!response.error){
          this.users = response.list
        } else {
          // show Error
          console.log('there is some error')
        }
      })
    }
  }
}
</script>