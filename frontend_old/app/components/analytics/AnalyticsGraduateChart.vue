<template>
<UDashboardCard
    title="Graduate Rates"
    description="ASCOT graduating student for the year"
    icon="i-heroicons-academic-cap"
>
    <div id="chart">
        <apexchart
            ref="realtimeChart"
            type="area" 
            height="380"  
            :options.sync="chartOptions" 
            :series.sync="series"
        >
        </apexchart>
    </div>
</UDashboardCard>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    props:{
      chartData: {
        type: Array,
        default: []
      },
      groupData: {
        type: Array,
        default: []
      },
      coursesOpt: {
        type: Array,
        default: []
      },

    },
    components: {
        apexchart: VueApexCharts,
    },
    data(){
        return {
            selectedValue: null,
            series: [],
            chartOptions: {
                chart: {
                    type: 'area',
                    height: 280
                },
                dataLabels: {
                    enabled: true
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'text',
                    categories: []
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy'
                    },
                },
            },
        }
    },
    watch:{
        chartData(newData){
            if(newData){
                this.$refs.realtimeChart.updateSeries(newData, false, true);
            }
        },
        groupData(newData){
            if(newData){
                this.$refs.realtimeChart.updateOptions({
                    xaxis: {
                        categories: newData 
                    },
                });
            }
        },
        coursesOpt(newVal){
            if(newVal && newVal.length !== 0){
                this.selectedValue = newVal[0].value
                this.emitValue()
            } else {
                this.$refs.realtimeChart.updateSeries([
                    {
                        name: 'Male',
                        data: [0, 0, 0, 0]
                    },
                    {
                        name: 'Female',
                        data: [0, 0, 0, 0]
                    },
                ], false, true);
                this.$refs.realtimeChart.updateOptions({
                    xaxis: {
                        categories: ['','','',''] 
                    },
                });
            }
        }
    },
    methods:{
        emitValue(){
            let selected = [];
            let categories = [];
            let filterData = this.coursesOpt.filter(el => {
                return el.name === this.selectedValue
            })
            

            if(typeof filterData[0].data === "object"){
                for (const el in filterData[0].data) {
                    selected.push(filterData[0].data[el])
                }
                categories = filterData[0].categories
            } else {
                selected = filterData[0].data
                categories = filterData[0].categories
            }

            this.$refs.realtimeChart.updateSeries(selected, false, true);
            this.$refs.realtimeChart.updateOptions({
                xaxis: {
                    categories 
                },
            });
        }
    }
}
</script>