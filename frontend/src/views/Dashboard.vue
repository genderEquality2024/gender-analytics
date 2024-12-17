<!-- 
	This is the dashboard page, it uses the dashboard layout in: 
	"./layouts/Dashboard.vue" .
 -->

<template>
	<div>
		<!-- Counter Widgets -->
		<a-row :gutter="24">
			<a-col :span="24" :lg="6" >
				<a-form :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }">
					<a-form-item label="Year From">
						<a-select
							v-model="selectedYears.from"
							v-decorator="[
								'schoolYeaer',
								{ rules: [{ required: true, message: 'Please select year' }] },
							]"
							placeholder="Select a Year"
							:options="yearMultipleOpts"
							@change="getListSelection(selectedYears)"
						/>
					</a-form-item>
				</a-form>
			</a-col>
			<a-col :span="24" :lg="6" >
				<a-form :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }">
					<a-form-item label="Year To">
						<a-select
							v-model="selectedYears.to"
							v-decorator="[
								'schoolYeaer',
								{ rules: [{ required: true, message: 'Please select year' }] },
							]"
							placeholder="Select a Year"
							:options="yearMultipleOpts"
							@change="getListSelection(selectedYears)"
						/>
					</a-form-item>
				</a-form>
			</a-col>
			<a-col :span="24" :lg="24"></a-col>
			<a-col :span="24" :lg="12" :xl="6" class="mb-24" v-for="(stat, index) in dashboardCards" :key="index">
				<a-card>
					<a-card-meta :title="stat.value" :description="stat.label">
						<a-icon 
							slot="avatar"
							key="man"
							:style="{color: stat.color, fontSize: '32px'}"
							:type="stat.icon" 
						/>
					</a-card-meta>
					<!-- <span class="font-bold">{{ stat.label }}</span><br/>
					<h5>{{  }}</h5>  -->
					<template v-if="stat.genders !== undefined" slot="actions" >
						<a-icon key="man" type="man" /> {{ stat.genders.male }}
						<a-icon key="woman" type="woman" /> {{ stat.genders.female }}
					</template>
					<template v-else slot="actions" >
						<a @click="goTo('/resources')">View Details</a>
					</template>
				</a-card>
				
			</a-col>
		</a-row>
		<!-- / Counter Widgets -->

		<!-- Charts -->
		<a-row :gutter="24" type="flex" align="stretch">
			<a-col :span="24" :lg="10" class="mb-24">

				<!-- Active Users Card -->
				<CardLineChart
					title="Enrolled Student Analytics"
					description="5 years graph representation of the GAD"
					:chartData.sync="seriesDataEnroll"
          			:groupData.sync="groupDataEnroll"
				></CardLineChart>
				<!-- Active Users Card -->

			</a-col>
			<a-col :span="24" :lg="14" class="mb-24">
				
				<!-- Sales Overview Card -->
				<CardLineChart
					title="Graduate Student Analytics"
					description="5 years graph representation of the GAD"
					:chartData.sync="seriesDataGrad"
          			:groupData.sync="groupDataGrad"
				></CardLineChart>
				<!-- / Sales Overview Card -->

			</a-col>
		</a-row>
		<!-- / Charts -->

	</div>
</template>

<script>
	import moment from "moment";
	// Bar chart for "Active Users" card.
	import CardBarChart from '../components/Cards/CardBarChart' ;
	// Line chart for "Sales Overview" card.
	import CardLineChart from '../components/Cards/CardLineChart' ;
	// Counter Widgets
	import WidgetCounter from '../components/Widgets/WidgetCounter' ;

	const currentYear = moment().format('YYYY')

	export default ({
		components: {
			CardBarChart,
			CardLineChart,
			WidgetCounter
		},
		computed:{
			yearMultipleOpts(){
				let res = []
				let startDate = 1997

				for (let index = 27; index < 90; index++) {
					let val = moment(startDate).add(index, 'y').format('YYYY')
					res.push({
						label: val,
						value: val
					})
				}

				return res
			},
		},
		data() {
			return {
				// Counter Widgets Stats
				chartEnrollmentData: [],
				chartGraduateData: [],
				chartOptions: [],
				chartGradOptions: [],
				selectedYears: {
					from: "2019",
					to: "2024"
				},
				dashboardCards: [{
					label: 'Students',
					value: 0,
					genders: {
						male: 0,
						female: 0,
					},
					caption: 'Total count of the enrolled students',
					color: 'blue',
					icon: 'usergroup-add'
				}, {
					label: 'Faculty & Staff',
					value: 0,
					genders: {
						male: 0,
						female: 0,
					},
					caption: 'Total count of the employee',
					color: 'orange',
					icon: 'idcard'
				}, {
					label: 'Graduating Student',
					value: 0,
					genders: {
						male: 0,
						female: 0,
					},
					caption: 'Total count of Graduates',
					color: 'green',
					icon: 'check-circle'
				}, {
					label: 'Resources',
					value: 0,
					
					caption: 'Total count of the document',
					color: 'red',
					icon: 'file-pdf'
				},],
				seriesDataEnroll: [],
				groupDataEnroll: [],
				seriesDataGrad: [],
				groupDataGrad: [],
			}
		},
		created(){
			this.getListSelection(this.selectedYears)
			this.getEnrollmentData()
			this.getGraduateData()
		},
		methods:{
			moment,
			goTo(route){
				this.$router.push(route)
			},
			async getListSelection(dataYear){
				let payload = {
					...dataYear
				}
				this.$api.post("analytics/get/dashboard", payload).then((res) => {
					let response = {...res.data}
					if(!response.error){
						this.dashboardCards[0].value = response.enrollment
						this.dashboardCards[0].genders = response.genders.enrollment
						this.dashboardCards[1].value = response.employee
						this.dashboardCards[1].genders = response.genders.employee
						this.dashboardCards[2].value = response.graduates
						this.dashboardCards[2].genders = response.genders.graduate
						this.dashboardCards[3].value = response.resource
						
					} else {
						// show Error
						console.log('there is some error')
					}
				})
			},
			async getEnrollmentData(){
				let from = moment(currentYear).subtract(5, 'y').format('YYYY')
				let to = currentYear

				let payload = {
					from,
					to,
					reportType: 'enrollment'
				}
				this.$api.post("analytics/get/graph/dashboard", payload).then((res) => {
					let response = {...res.data}
					if(!response.error){
						let selected = [];
						let series = {
							male: [],
							female: [],
						}
						let groups = []

						if(typeof res.data === "object"){
							for (const el in res.data) {
								groups.push(el)
								selected.push(res.data[el])
							}
						} else {
							selected = res.data
						}

						// selected.sort((a, b) => +(a.group.title > b.group.title) || -(a.group.title < b.group.title))
						selected.forEach((el) => {
							let totalMale = 0
							let totalFemale = 0
							
							el.forEach(sel => {
								totalMale += sel[0].y
								totalFemale += sel[1].y
							})

							series.male.push(totalMale)
							series.female.push(totalFemale)
						});

						this.seriesDataEnroll = series
                		this.groupDataEnroll = groups
						
					} else {
						// show Error
						console.log('there is some error')
					}
				})
			},
			async getGraduateData(){
				let from = moment(currentYear).subtract(5, 'y').format('YYYY')
				let to = currentYear

				let payload = {
					from,
					to,
					reportType: 'graduate'
				}
				this.$api.post("analytics/get/graph/dashboard", payload).then((res) => {
					let response = {...res.data}
					if(!response.error){
						let selected = [];
						let series = {
							male: [],
							female: [],
						}
						let groups = []

						if(typeof res.data === "object"){
							for (const el in res.data) {
								groups.push(el)
								selected.push(res.data[el])
							}
						} else {
							selected = res.data
						}
						
						// selected.sort((a, b) => +(a.group.title > b.group.title) || -(a.group.title < b.group.title))
						selected.forEach((el) => {
							let totalMale = 0
							let totalFemale = 0
							if(typeof el === "object"){
								for (const i in el) {
									totalMale += el[i].male
									totalFemale += el[i].female
								}
							}

							series.male.push(totalMale)
							series.female.push(totalFemale)
						});

						this.seriesDataGrad = series
                		this.groupDataGrad = groups

						
					} else {
						// show Error
						console.log('there is some error')
					}
				})
			}
		}
		// End
	})

</script>

<style lang="scss">
</style>