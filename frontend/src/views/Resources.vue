<!-- 
	This is the dashboard page, it uses the dashboard layout in: 
	"./layouts/Dashboard.vue" .
 -->

 <template>
	<div>
		<!-- Counter Widgets -->
		<a-row :gutter="24">
			<a-col :span="24" :lg="12" :xl="6" class="mb-24" v-for="(stat, index) in stats" :key="index">
				<!-- Widget 1 Card -->
				<WidgetCounter
					v-bind="stat"
				></WidgetCounter>
				<!-- / Widget 1 Card -->
			</a-col>
		</a-row>
		<!-- / Counter Widgets -->

		<!-- Charts -->
		<a-row :gutter="24" type="flex" align="stretch">
			<a-col :span="24" :lg="10" class="mb-24">

				<!-- Active Users Card -->
				<CardBarChart></CardBarChart>
				<!-- Active Users Card -->

			</a-col>
			<a-col :span="24" :lg="14" class="mb-24">
				
				<!-- Sales Overview Card -->
				<CardLineChart></CardLineChart>
				<!-- / Sales Overview Card -->

			</a-col>
		</a-row>
		<!-- / Charts -->

	</div>
</template>

<script>

	// Bar chart for "Active Users" card.
	import CardBarChart from '../components/Cards/CardBarChart' ;
	// Line chart for "Sales Overview" card.
	import CardLineChart from '../components/Cards/CardLineChart' ;
	// Counter Widgets
	import WidgetCounter from '../components/Widgets/WidgetCounter' ;

	// Counter Widgets stats
	const stats = [
		{
			title: "Students",
			value: 0,
			icon: `<svg width="22" height="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" >
					<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
				</svg>
			`,
		},
		{
			title: "Faculty & Staffs",
			value: 0,
			icon: `<svg width="22" height="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
			</svg>
			`,
		},
		{
			title: "Resources",
			value: 0,
			icon: `
						<svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M3.17157 5.17157C4.73367 3.60948 7.26633 3.60948 8.82843 5.17157L10 6.34315L11.1716 5.17157C12.7337 3.60948 15.2663 3.60948 16.8284 5.17157C18.3905 6.73367 18.3905 9.26633 16.8284 10.8284L10 17.6569L3.17157 10.8284C1.60948 9.26633 1.60948 6.73367 3.17157 5.17157Z" fill="#111827"/>
						</svg>`,
		},
		{
			title: "Graduating Student",
			value: 0,
			icon: `
						<svg width="22" height="22" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M10 2C7.79086 2 6 3.79086 6 6V7H5C4.49046 7 4.06239 7.38314 4.00612 7.88957L3.00612 16.8896C2.97471 17.1723 3.06518 17.455 3.25488 17.6669C3.44458 17.8789 3.71556 18 4 18H16C16.2844 18 16.5554 17.8789 16.7451 17.6669C16.9348 17.455 17.0253 17.1723 16.9939 16.8896L15.9939 7.88957C15.9376 7.38314 15.5096 7 15 7H14V6C14 3.79086 12.2091 2 10 2ZM12 7V6C12 4.89543 11.1046 4 10 4C8.89543 4 8 4.89543 8 6V7H12ZM6 10C6 9.44772 6.44772 9 7 9C7.55228 9 8 9.44772 8 10C8 10.5523 7.55228 11 7 11C6.44772 11 6 10.5523 6 10ZM13 9C12.4477 9 12 9.44772 12 10C12 10.5523 12.4477 11 13 11C13.5523 11 14 10.5523 14 10C14 9.44772 13.5523 9 13 9Z" fill="#111827"/>
						</svg>`,
		},
	] ;

	export default ({
		components: {
			CardBarChart,
			CardLineChart,
			WidgetCounter
		},
		data() {
			return {
				// Counter Widgets Stats
				stats,
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
	})

</script>

<style lang="scss">
</style>