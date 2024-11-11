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
      </UDashboardToolbar>

      <UDashboardPanelContent>
        <UDashboardCard
          class="mb-8"
        >
          <CommonDateYearSelection  
            v-model="selectedYears"
          />
        </UDashboardCard>
        <!-- ~/components/home/HomeCardItems.vue -->
        <div class="grid grid-rows-3 grid-flow-col gap-4">
            <!-- <div class="row-span-3">
                <AnalyticsBarChart
                    :selectedDepartment="selected"
                />
            </div>
            <div class="col-span-2">
                <AnalyticsDonut />
            </div>
            <div class="row-span-2 col-span-2">
                <AnalyticsDonutFull />
            </div> -->
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
          chartOptions: [],
          chartGradOptions: [],
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
            label: 'Employment',
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
      this.getListSelection(this.selectedYears)
    },
    methods:{
      async getListSelection(dataYear){
        let payload = {
          yearFrom: dataYear.from,
          yearTo: dataYear.to,
        }
        api.post("analytics/get/dashboard", payload).then((res) => {
          let response = {...res.data}
          if(!response.error){
            this.dashboardCards[0].value = response.enrollment
            this.dashboardCards[1].value = response.employee
            this.dashboardCards[3].value = response.graduates
            console.log(response)
            let optData = []
            for (const i in response.enrollAnalytics) {
              optData.push({
                name: i,
                value: i,
                data: response.enrollAnalytics[i]
              })
            }
            this.chartOptions = optData

            let optDataGrad = []
            for (const i in response.dataAnalytics) {
              let maleSeries = []
              let femaleSeries = []
              let category = []

              for(const l in response.dataAnalytics[i]){
                maleSeries.push(response.dataAnalytics[i][l].male)
                femaleSeries.push(response.dataAnalytics[i][l].female)
                category.push(response.dataAnalytics[i][l].categories)
              }

              optDataGrad.push({
                name: i,
                value: i,
                data: [
                  {
                    name: "male",
                    data: maleSeries,
                  },
                  {
                    name: "female",
                    data: femaleSeries,
                  }
                ],
                categories: category
              })
            }

            this.chartGradOptions = optDataGrad
          } else {
            // show Error
            console.log('there is some error')
          }
        })
      },
    }
}
</script>