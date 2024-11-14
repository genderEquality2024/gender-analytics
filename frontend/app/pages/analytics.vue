<template>
    <UDashboardPage>
      <UDashboardPanel grow>
          <UDashboardNavbar title="GENDER EQUALITY DASHBOARD"></UDashboardNavbar>
    
          <UDashboardToolbar>
            <template #left>
              Analytical Representation of the Gender Rates
            </template>
            <template #right>
              <CommonDateYearSelection
                v-model="selectedYears"
                label="School Year"
                @updateList="changeYear"
              />
            </template>
          </UDashboardToolbar>
    
          <UDashboardPanelContent>
            <UDashboardCard
              class="mb-8"
            >
              Filter
              <div class="grid grid-rows-3 grid-flow-col gap-4">
                <UFormGroup
                  class="row-span-3"
                  label="Type of Report"
                  name="reportType"
                >
                  <USelect 
                    v-model="filters.reportType" 
                    :options="typeOfReportOpt" 
                    option-attribute="name"
                    @change="getCourceList"
                  />
                </UFormGroup>
                <UFormGroup
                  class="row-span-3"
                  label="Courses/School"
                  name="course"
                >
                  <USelect 
                    v-model="filters.course" 
                    :options="courseOpt" 
                    option-attribute="name"
                    @change="getListSelection"
                  />
                </UFormGroup>
              </div>
    
              
              
            </UDashboardCard>
    
            <div class="grid lg:grid-cols-1 lg:items-start gap-8">
              <!-- ~/components/home/HomeChart.vue -->
              <AnalyticsEnrollChart
                v-show="(filters.reportType === 'enrollment' || filters.reportType === 'employee') && seriesData.length > 0"
                :chartData.sync="seriesData"
                :groupData.sync="groupData"
              />
              <!-- ~/components/home/HomeCountries.vue -->
              <AnalyticsGraduateChart
                v-show="filters.reportType === 'graduate' && seriesData.length > 0"
                :chartData.sync="seriesData"
                :groupData.sync="groupData"
              />
              
              <AnalyticsEmployeeChart
                v-show="filters.reportType === 'employee' && seriesData.length > 0"
                :chartData.sync="seriesData"
                :groupData.sync="groupData"
              />
              
            </div>
          </UDashboardPanelContent>
        </UDashboardPanel>
    </UDashboardPage>
</template>
    
<script>
import { group } from 'd3';

    const api = useApi()
    definePageMeta({
      layout: 'user'
    })
    
    export default {
        data() {
            return {
            filters: {
                reportType: 'enrollment',
                course: ''
            },
            selectedYears: "2024 - 2025",
            showCharts: false,
            courseOpt: [],
            chartOptions: [],
            chartGradOptions: [],
            seriesData: [],
            groupData: [],
            typeOfReportOpt: [
                {
                    name: "Enrollment",
                    value: "enrollment",
                },
                {
                    name: "Graduates",
                    value: "graduate",
                },
                {
                    name: "Employment",
                    value: "employee",
                },
            ],
        }
    },
    created(){
      this.getCourceList()
    },
    methods:{
        changeYear(val){
            this.selectedYears = val
            this.getCourceList()
        },
        async getCourceList(){
            let payload = {
              schoolYear: this.selectedYears,
              reportType: this.filters.reportType
            }
            api.post("analytics/get/graph/options", payload).then((res) => {
              let response = {...res.data}
              if(!response.error){
                this.courseOpt = res.data
                this.filters.course = this.courseOpt[0].value
                this.getListSelection()
              } else {
                // show Error
                console.log('there is some error')
              }
            })
        },
    
        async getListSelection(){
            let payload = {
            schoolYear: this.selectedYears,
              ...this.filters
            }
            api.post("analytics/get/graph", payload).then((res) => {
              let response = {...res.data}
              if(!response.error){
                let selected = [];
                let series = []
                let groups = []

                if(typeof res.data === "object"){
                    for (const el in res.data) {
                        selected.push(res.data[el])
                    }
                } else {
                    selected = res.data
                }

                if(this.filters.reportType === 'enrollment'){
                    selected.forEach(el => {
                        groups.push(el.group)
                        series.push(...el.series)
                    });
                } else if(this.filters.reportType === 'graduate'){
                    let maleSeries = []
                    let femaleSeries = []
                    for(const i in res.data){
                        maleSeries.push(res.data[i].male)
                        femaleSeries.push(res.data[i].female)
                        groups.push(res.data[i].categories)
                    }

                    series.push({
                        name: "male",
                        data: maleSeries,
                    },
                    {
                        name: "female",
                        data: femaleSeries,
                    })

                } else if(this.filters.reportType === 'employee'){
                    
                }
                
                console.log(series)
                console.log(groups)
                this.seriesData = series
                this.groupData = groups
              } else {
                // show Error
                console.log('there is some error')
              }
            })
        },
    }
}
</script>