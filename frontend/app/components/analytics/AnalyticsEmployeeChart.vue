<template>
    <UDashboardCard
        title="Faculty and Staffs"
        description="ASCOT's faculty and staffs data chart per school/department"
        icon="i-heroicons-user-group"
    >
        <div id="chart">
            <apexchart
                ref="realtimeChart"
                type="bar" 
                height="380"  
                :options.sync="chartOptions"
                :series="series"
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
        watch:{
            chartData(newData){
                console.log(newData)
                if(newData){
                    this.$refs.realtimeChart.updateSeries(newData, false, true);
                }
            },
            groupData(newData){
                if(newData){
                    this.$refs.realtimeChart.updateOptions({
                        xaxis: {
                            type: 'text',
                            categories: newData,
                        },
                    });
                }
            },
            coursesOpt(newVal){
                if(newVal && newVal.length !== 0){
                    this.selectedValue = newVal[0].value
                    this.emitValue()
                } else {
                    this.$refs.realtimeChart.updateSeries([{
                        data: [],
                    }], false, true);
                    this.$refs.realtimeChart.updateOptions({
                        xaxis: {
                            type: 'text',
                            categories: [],
                        },
                    });
                }
            }
        },
        data(){
            return {
                selectedValue: null,
                series: [],
                chartOptions: {
                    chart: {
                        type: 'bar',
                        height: 350,
                        stacked: true,
                        toolbar: {
                            show: true
                        },
                        zoom: {
                            enabled: true
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                position: 'bottom',
                                offsetX: -10,
                                offsetY: 0
                            }
                        }
                    }],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 10,
                            borderRadiusApplication: 'end', // 'around', 'end'
                            borderRadiusWhenStacked: 'last', // 'all', 'last'
                            dataLabels: {
                            total: {
                                enabled: true,
                                style: {
                                fontSize: '13px',
                                fontWeight: 900
                                }
                            }
                            }
                        },
                    },
                    xaxis: {
                        type: 'text',
                        categories: [],
                    },
                    legend: {
                        position: 'right',
                        offsetY: 40
                    },
                    fill: {
                        opacity: 1
                    }
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
                        type: 'text',
                        categories: groups,
                    }
                });
            }
        }
    }
    </script>