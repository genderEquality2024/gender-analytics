<template>
  <UDashboardPage>
    <UDashboardPanel grow>
      <UDashboardNavbar
        title="Users"
        :badge="users.length"
      >
        <template #right>
          <!-- <UInput
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
          </UInput> -->

          <UButton
            label="New user"
            trailing-icon="i-heroicons-plus"
            color="gray"
            @click="isNewUserModalOpen = true"
          />
        </template>
      </UDashboardNavbar>

      <UDashboardToolbar>
        <!-- <template #left>
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
        </template> -->
      </UDashboardToolbar>

      

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


    <UDashboardModal
        v-model="isNewUserModalOpen"
        title="New user"
        description="Add a new user to your database"
        :ui="{ width: 'sm:max-w-md' }"
    >
      <UForm
          :state="form"
          class="space-y-4"
          @submit="onSubmit"
        >
          <UFormGroup
            label="Username"
            name="username"
          >
            <UInput
              v-model="form.username"
              autofocus
            />
          </UFormGroup>
          <UFormGroup
            label="Password"
            name="password"
          >
            <UInput
              v-model="form.password"
              type="password"
            />
          </UFormGroup>
          <UFormGroup
                  label="User Type"
                  name="userType"
                >
                  <USelect 
                    v-model="form.userType" 
                    :options="userTypeOpt" 
                    option-attribute="name"
                  />
                </UFormGroup>
          <UDivider label="User Details" />
          <UFormGroup
            label="First Name"
            name="firstName"
          >
            <UInput
              v-model="form.firstName"
            />
          </UFormGroup>
          <UFormGroup
            label="Middle Name"
            name="middleName"
          >
            <UInput
              v-model="form.middleName"
            />
          </UFormGroup>
          <UFormGroup
            label="Last Name"
            name="lastName"
          >
            <UInput
              v-model="form.lastName"
            />
          </UFormGroup>
          <UFormGroup
            label="Contact"
            name="contact"
          >
            <UInput
              v-model="form.contact"
            />
          </UFormGroup>
          <UFormGroup
            label="Email"
            name="email"
          >
            <UInput
              v-model="form.email"
            />
          </UFormGroup>

          <div class="mt-1 flex justify-end gap-3">
            <UButton
              type="submit"
              label="Save"
              color="black"
            />
          </div>
        </UForm>
    </UDashboardModal>
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
      users: [],
      form: {
        username: '',
        password: '',
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        sex: '',
        email: '',
        contact: '',
        userType: null
      },
      userTypeOpt: [
                {
                    name: "Admin",
                    value: 1,
                },
                {
                    name: "Employee",
                    value: 2,
                },
            ],
    }
  },
  computed: {
    columns(){
      return defaultColumns.filter(column => this.selectedColumns.includes(column))
    },
    user: function(){
      let token = localStorage.getItem('userToken')
      token = JSON.parse(token);
      return jwtDecode(token.value);
    },
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
    },
    async onSubmit(){
      let payload = {
          ...this.form,
          status: 1
        }

        api.post("users/create", payload).then((res) => {
          let response = {...res.data}
          if(!response.error){
            this.clearForm();
            this.getList();
            this.isNewUserModalOpen = false
          } else {
            // show Error
            console.log('there is some error')
          }
        })
      
    },
    clearForm(){
      this.form = {
        username: '',
        password: '',
        firstName: '',
        lastName: '',
        middleName: '',
        suffix: '',
        sex: '',
        email: '',
        contact: '',
        userType: null
      }
    }
  }
}
</script>