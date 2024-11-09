<template>
<UDashboardCard
    title="Graduate Rates"
    description="ASCOT graduating student for the year"
    icon="i-heroicons-academic-cap"
>
    <USelect 
        v-model="selectedValue" 
        :options="coursesOpt" 
        option-attribute="name"
        @change="emitValue"
    />
    <div id="chart">
        <apexchart
            ref="realtimeChart"
            type="bar" 
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
            series: [{
                name: "graduates",
                data: []
            }],
            chartOptions: {
                chart: {
                    type: 'bar',
                    height: 280
                },
                xaxis: {
                    type: 'category',
                    labels: {
                        formatter: function(val) {
                            return val
                        }
                    },
                    group: {
                        style: {
                            fontSize: '10px',
                            fontWeight: 700
                        },
                        groups: []
                    }
                },
            },
        }
    },
    methods:{
        emitValue(){
            let selected = [];
            let filterData = this.coursesOpt.filter(el => {
                return el.name === this.selectedValue
            })
            
            let series = []
            let groups = []

            if(typeof filterData[0].data === "object"){
                for (const el in filterData[0].data) {
                    selected.push(filterData[0].data[el])
                }
            } else {
                selected = filterData[0].data
            }

            selected.forEach(el => {
                groups.push(el.group)
                series.push(...el.series)
            });

            // this.series.data = series
            // this.chartOptions.xaxis.group.groups = groups
            
            this.$refs.realtimeChart.updateSeries([{
                data: series,
            }], false, true);
            this.$refs.realtimeChart.updateOptions({
                xaxis: {
                    group: {
                        style: {
                            fontSize: '10px',
                            fontWeight: 700
                        },
                        groups: groups
                    }
                },
            });
        }
    }
}
</script>