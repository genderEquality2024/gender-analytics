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
            v-model="selectedCourseFilter"
            icon="i-heroicons-check-circle"
            placeholder="Course"
            multiple
            :options="courseFilter"
            :ui-menu="{ option: { base: 'capitalize' } }"
            class="w-96"
          />
          <USelectMenu
            v-model="selectedReportTypeFilter"
            icon="i-heroicons-map-pin"
            placeholder="Type of Report"
            :options="reportTypeFilter"
            multiple
          />
          <USelectMenu
            v-model="selectedSchoolYearFilter"
            icon="i-heroicons-map-pin"
            placeholder="School Year"
            :options="schoolYearFilter"
            multiple
          />
        </template>

        <template #right>
          <UButton
            label="Print Data Report"
            trailing-icon="i-heroicons-printer"
            color="blue"
            @click="generatePrint"
          />
          <UButton
            label="Export CSV"
            trailing-icon="i-heroicons-printer"
            color="blue"
            @click="exportCSV"
          />
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
        :rows="filteredUser"
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

    <CommonPrintReport 
      :openPrint="openPrint"
      :dataVal="filteredUser"
      @closePrint="openPrint = false"
    />
  </UDashboardPage>
</template>

<script>
import { getData, setData } from 'nuxt-storage/local-storage';
import { jwtDecode } from 'jwt-decode';
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
  key: 'schoolYear',
  label: 'School Year',
  sortable: true
}, {
  key: 'reportType',
  label: 'Type of Report',
  sortable: true
}, {
  key: 'classYear',
  label: 'Year'
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
      openPrint: false,
      users: [],
      usersOrig: [],
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


      courseFilter: [],
      selectedCourseFilter: [],
      schoolYearFilter: [],
      selectedSchoolYearFilter: [],
      reportTypeFilter: [
        "enrollment",
        "graduate",
        "employee"
      ],
      selectedReportTypeFilter: [
        "enrollment",
        "graduate",
        "employee"
      ],
    }
  },
  computed: {
    columns(){
      return defaultColumns.filter(column => this.selectedColumns.includes(column))
    },
    filteredUser(){
      return this.users.filter(el => 
        this.selectedCourseFilter.includes(el.course) && 
        this.selectedSchoolYearFilter.includes(el.schoolYear) && 
        this.selectedReportTypeFilter.includes(el.reportType)
      )
      
      // return this.users
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
    generatePrint(){
      this.openPrint = true
    },
    async exportCSV(){
      const content = [this.columns.map(col => this.wrapCsvValue(col.key))].concat(
        this.users.map(row => this.columns.map(col => this.wrapCsvValue(
            typeof col.key === 'function'
            ? col.field(row)
            : row[ col.field === void 0 ? col.key : col.key ],
            col.format,
            row
        )).join(','))
      ).join('\n')


      console.log(content)
      const anchor = document.createElement('a');
      anchor.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(content);
      anchor.target = '_blank';
      anchor.download = 'DataReport.csv';
      anchor.click();
    },
    wrapCsvValue (val, formatFn, row) {
        let formatted = formatFn !== void 0
            ? formatFn(val, row)
            : val

        formatted = formatted === void 0 || formatted === null
            ? ''
            : String(formatted)

        formatted = formatted.split('"').join('""')
        /**
         * Excel accepts \n and \r in strings, but some other CSV parsers do not
         * Uncomment the next two lines to escape new lines
         */
        // .split('\n').join('\\n')
        // .split('\r').join('\\r')

        return `"${formatted}"`
    },
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
      this.usersOrig = []
      api.get("analytics/get/list").then((res) => {
        let response = {...res.data}
        if(!response.error){
          this.users = response.list
          this.usersOrig = response.list

          let courses = []
          let sy = []
          response.list.forEach(element => {
            courses.push(element.course)
            sy.push(element.schoolYear)
          });
          this.courseFilter = courses.filter((e, i, self) => i === self.indexOf(e));
          this.selectedCourseFilter = this.courseFilter
          this.schoolYearFilter = sy.filter((e, i, self) => i === self.indexOf(e));
          this.selectedSchoolYearFilter = this.schoolYearFilter
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