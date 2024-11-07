<template>
<UDashboardCard
    title="Graduate Rates"
    description="ASCOT graduating student for the year"
    icon="i-heroicons-academic-cap"
>
    <div id="chart">
        <apexchart type="bar" height="380"  :options="chartOptions" :series="series"></apexchart>
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

    },
    components: {
        apexchart: VueApexCharts,
    },
    data(){
        return {
            series: [{
                name: "sales",
                data: [
                    {
                        x: 'Male',
                        y: 2
                    }, {
                        x: 'Female',
                        y: 1
                    }, {
                        x: 'Male',
                        y: 41
                    }, {
                        x: 'Female',
                        y: 115
                    }
                ]
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
                        groups: [
                            { title: 'First - 2022 - 2023', cols: 2 },
                            { title: 'Second - 2022 - 2023', cols: 2 }
                        ]
                    }
                },
            },
        }
    }
}
</script>