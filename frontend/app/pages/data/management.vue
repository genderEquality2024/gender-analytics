<template>
  <UDashboardPage>
    <UDashboardPanel grow>
      <UDashboardNavbar
        title="Analytical Data Representation"
        :badge="users.length"
      >
        <template #right>
          <UButton
            label="New Data Report"
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
        title="New Data Analytics"
        description="Add a new data to your analytical result"
        :ui="{ width: 'sm:max-w-md' }"
      >
        <!-- ~/components/users/UsersForm.vue -->
        <!-- <DataForm @close="isNewUserModalOpen = false" /> -->
        <UForm
          :state="form"
          class="space-y-4"
          @submit="onSubmit"
        >
          <UFormGroup
            label="Import CSV Data"
            name="import"
            help="This will automatically insert the data and close the form once the upload of data is complete"
          >
            <UInput
              @input="(evt) => {return getFile(evt)}"
              type="file" 
              size="sm" 
              icon="i-heroicons-folder" 
            />
          </UFormGroup>
          <div class="text-right text-sky-500">
            <a href="/docs/analytics_data-format.csv" download="analytics_data-format.csv" target="_blank">Click Here to Download Template</a>
          </div>
          <UDivider label="OR" />
          
          <UFormGroup
            label="Course"
            name="course"
          >
            <UInput
              v-model="form.course"
              autofocus
            />
          </UFormGroup>
          <UFormGroup
            name="schoolYEar"
          >
            <CommonDateYearSelection
              label="School Year"
              v-model="form.schoolYear"
            />
          </UFormGroup>
          <UFormGroup
            label="Type of Report"
            name="repotType"
          >
            <UInput
              v-model="form.reportType"
              placeholder="graduate/enrollment/employee"
            />
          </UFormGroup>
          <UFormGroup
            label="Term"
            name="term"
          >
            <UInput
              v-model="form.term"
            />
          </UFormGroup>
          <UFormGroup
            label="Male Count"
            name="male"
          >
            <UInput
              v-model="form.male"
            />
          </UFormGroup>
          <UFormGroup
            label="Female Count"
            name="female"
          >
            <UInput
              v-model="form.female"
            />
          </UFormGroup>
          <UFormGroup
            label="Vacant Count"
            name="vacant"
          >
            <UInput
              v-model="form.vacant"
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
        <template #years-data="{ row }">
          <div class="flex items-center gap-3">
            <span class="text-gray-900 dark:text-white font-medium">
              {{ `${row.schoolYear}` }}
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
import { getData, setData } from 'nuxt-storage/local-storage';
import { jwtDecode } from 'jwt-decode';
import csv from "csvtojson";
import * as d3 from "d3"
const toast = useToast()
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
  key: 'course',
  label: 'Course',
  sortable: true
}, {
  key: 'years',
  label: 'School Year',
  sortable: true
}, {
  key: 'reportType',
  label: 'Type of Report',
  sortable: true
}, {
  key: 'term',
  label: 'School Term'
}, {
  key: 'male',
  label: 'Male'
}, {
  key: 'female',
  label: 'Female'
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
      form:{
        course: "",
        schoolYear: "2024 - 2025",
        reportType: "",
        term: "",
        male: 0,
        female: 0,
        vacant: 0,
        createdBy: 0,
      },
      csvData: [],
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
    enableSubmit(){
      
      let checkItemVal = 0;
      for(const obj in this.form){
        if(
          this.form[obj] === ""
        ){
          checkItemVal += 1
        }
      }

      return checkItemVal > 1
    }
  },
  created(){
    this.getList();
  },
  methods:{
    async getFile(file){
      var reader = new FileReader();
      let filePath = file.target.files[0]
      reader.readAsText(new Blob(
        [filePath],
        {"type": file.type}
      ))
      const fileContent = await new Promise(resolve => {
        reader.onloadend = (event) => {
          resolve(event.target.result)
        }
      })
      let csvData = d3.csvParse(fileContent)
      

      csvData = csvData.map((el) => {
        return {
          ...el,
          createdBy: Number(this.user.userId)
        }
      })
      this.uploadCSVData(csvData)
      // this.csvData = csvData
    },
    async uploadCSVData(data){
      const dataUploaded = await new Promise((resolve, reject) => {
        let uploaded = 0
        data.forEach(el => {
          api.post("analytics/add/new", el).then((res) => {
            let response = {...res.data}
            if(!response.error){
              console.log('data uploaded')
            } else {
              // show Error
              console.log('there is some error')
            }
          })
          uploaded += 1
        });

        if(uploaded === data.length){
          resolve({
            message: 'Upload complete'
          })
        } else {
          reject()
        }
      })
      
      this.getList();
      this.isNewUserModalOpen = false
      
    },
    async getList(){
      this.users = []
      api.get("analytics/get/list").then((res) => {
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
      if(!this.enableSubmit){
        let payload = {
          ...this.form,
          yearFrom: this.form.years.from,
          yearTo: this.form.years.to,
        }

        api.post("analytics/add/new", payload).then((res) => {
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
      } else {
        toast.add({
          id: 'error_submit',
          title: 'Submit Failed.',
          description: 'Please fill the required fields.',
          icon: 'i-octicon-alert-24',
          color: "red",
          timeout: 0,
        })
      }
      
    },
    clearForm(){
      this.form = {
        course: "",
        years: {
          from: "2024",
          to: "2025",
        },
        reportType: "",
        term: "",
        male: "",
        female: "",
        undergrad: "",
        vacant: "",
        createdBy: 0,
      }
    }
  }
}
</script>