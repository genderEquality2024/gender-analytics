<template>
<UDashboardPage>
  <UDashboardPanel grow>
    <UDashboardNavbar title="GENDER EQUALITY DASHBOARD"></UDashboardNavbar>
      <UDashboardToolbar>
        <template #left>
          Tracking and Promoting Gender Equality at ASCOT
        </template>
        <template #right>
          <CommonDateYearSelection  
            v-model="selectedYears"
            label="School Year"
            @updateList="getListSelection"
          />
        </template>
      </UDashboardToolbar>

      <UDashboardPanelContent>
        
        <!-- ~/components/home/HomeCardItems.vue -->
        <div class="grid grid-cols-4 gap-4 justify-items-center">
          <HomeCardItems 
            :cardItems="dashboardCards"
          />
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
          selectedYears: "2024 - 2025",
          dashboardCards: [{
            label: 'Students',
            value: 0,
            caption: 'Total count of the enrolled students',
            color: 'red',
            icon: 'i-heroicons-user-group'
          }, {
            label: 'Faculty & Staff',
            value: 0,
            caption: 'Total count of the employee',
            color: 'orange',
            icon: 'i-heroicons-briefcase'
          }, {
            label: 'Resources',
            value: 0,
            caption: 'Total count of the document',
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
          schoolYear: dataYear,
        }
        api.post("analytics/get/dashboard", payload).then((res) => {
          let response = {...res.data}
          if(!response.error){
            this.dashboardCards[0].value = response.enrollment
            this.dashboardCards[1].value = response.employee
            this.dashboardCards[2].value = response.resource
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
            console.log(optDataGrad)
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