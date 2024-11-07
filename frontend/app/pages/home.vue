<template>
<UDashboardPage>
  <UDashboardPanel grow>
    <UDashboardNavbar title="GENDER EQUALITY DASHBOARD">
        <template #right>
          <UTooltip
            text="Notifications"
            :shortcuts="['N']"
          >
            <UButton
              color="gray"
              variant="ghost"
              square
              @click="isNotificationsSlideoverOpen = true"
            >
              <UChip
                color="red"
                inset
              >
                <UIcon
                  name="i-heroicons-bell"
                  class="w-5 h-5"
                />
              </UChip>
            </UButton>
          </UTooltip>
        </template>
      </UDashboardNavbar>

      <UDashboardToolbar>
        <template #left>
          Tracking and Promoting Gender Equality at ASCOT
        </template>
        <template #right>
          <CommonDateYearSelection  
            v-model="selectedYears"
          />
          <UButton @click="getList">Get Analytics & Dashboard</UButton>
        </template>
      </UDashboardToolbar>

      <UDashboardPanelContent>
        
        <!-- ~/components/home/HomeCardItems.vue -->
        <div class="grid grid-cols-4 gap-4 justify-items-center">
          <HomeCardItems 
            :cardItems="dashboardCards"
          />
        </div>

        <div class="grid lg:grid-cols-2 lg:items-start gap-8 mt-8">
          <!-- ~/components/home/HomeChart.vue -->
          <HomeGraduateChart />
          <!-- ~/components/home/HomeCountries.vue -->
          <!-- <HomeCountries /> -->
          
        </div>
      </UDashboardPanelContent>
  </UDashboardPanel>
</UDashboardPage>
</template>

<script>
const api = useApi()
definePageMeta({
  layout: 'user'
})

export default {
    data() {
        return {
          chartEnrollmentData: [],
          chartGraduateData: [],
          selectedYears: {
            from: "2023",
            to: "2024",
          },
          dashboardCards: [{
            label: 'Total Enrollment',
            value: 0,
            caption: 'Total count of the enrolled students',
            color: 'red',
            icon: 'i-heroicons-user-group'
          }, {
            label: 'Employement',
            value: 0,
            caption: 'Total count of the employee',
            color: 'orange',
            icon: 'i-heroicons-briefcase'
          }, {
            label: 'Resources',
            value: 0,
            caption: 'Total count of the Events',
            color: 'yellow',
            icon: 'i-heroicons-clipboard-document-list'
          }, {
            label: 'Graduating Student',
            value: 0,
            caption: 'Total count of Graduates',
            color: 'green',
            icon: 'i-heroicons-academic-cap'
          }]
        }
    },
    created(){
      this.getList()
    },
    methods:{
      async getList(){
        let payload = {
          yearFrom: this.selectedYears.from,
          yearTo: this.selectedYears.to,
        }
        api.post("analytics/get/dashboard", payload).then((res) => {
          let response = {...res.data}
          if(!response.error){
            console.log(response)
            this.dashboardCards[0].value = response.enrollment
            this.dashboardCards[1].value = response.employee
            this.dashboardCards[3].value = response.graduates


            // series: [{
            //     name: "sales",
            //     data: [
            //         {
            //             x: 'Male',
            //             y: 2
            //         }, {
            //             x: 'Female',
            //             y: 1
            //         }, {
            //             x: 'Male',
            //             y: 41
            //         }, {
            //             x: 'Female',
            //             y: 115
            //         }
            //     ]
            // }],
          } else {
            // show Error
            console.log('there is some error')
          }
        })
      },
    }
}
</script>