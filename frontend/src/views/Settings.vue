<!-- 
	This is the billing page, it uses the dashboard layout in: 
	"./layouts/Dashboard.vue" .
 -->

 <template>
	<div>

		<a-row type="flex" :gutter="24">

			<!-- Billing Info Column -->
			<a-col :span="24" :md="16">
				<a-row type="flex" :gutter="24">
					<a-col :span="24" :xl="12" class="mb-24">

						<!-- Master Card -->
						<CardCredit></CardCredit>
						<!-- / Master Card -->

					</a-col>
					<a-col :span="12" :xl="6" class="mb-24" v-for="(salary, index) in salaries" :key="index">
                        <a-card :bordered="false" >
                            <a-card-meta :title="salary.title" :description="salary.content">
                                <a-icon 
                                    slot="avatar"
                                    key="man"
                                    :style="{color: salary.color, fontSize: '32px'}"
                                    :type="salary.icon" 
                                />
                            </a-card-meta>
                            <template slot="actions" >
                                <a-button @click="databaseAction(salary.action)" slot="actions" type="link">
                                    {{ salary.action }}
                                </a-button>
                            </template>
                        </a-card>

					</a-col>
					<a-col :span="24" class="mb-24">

						<!-- Payment Methods Card -->
						<CardPaymentMethods></CardPaymentMethods>
						<!-- Payment Methods Card -->

					</a-col>
				</a-row>
			</a-col>
			<!-- / Billing Info Column -->
			
			<!-- Invoices Column -->
			<a-col :span="24" :md="8" class="mb-24">

				<!-- Invoices Card -->
				<CardInvoices
					:data="invoiceData"
				></CardInvoices>
				<!-- / Invoices Card -->

			</a-col>
			<!-- / Invoices Column -->

		</a-row>

		

	</div>
</template>

<script>
	import { jwtDecode } from 'jwt-decode';
	import CardCredit from "../components/Cards/CardCredit"
	import WidgetSalary from "../components/Widgets/WidgetSalary"
	import CardPaymentMethods from "../components/Cards/CardPaymentMethods"
	import CardInvoices from "../components/Cards/CardInvoices"
	import CardBillingInfo from "../components/Cards/CardBillingInfo"
	import CardTransactions from "../components/Cards/CardTransactions"


	// Salary cards data
	const salaries = [
		{
            color: 'red',
			icon: `download`,
			title: "Export",
			content: "Database",
			action: "Download",
		},
		{
            color: 'blue',
			icon: `cloud-upload`,
			title: "Import",
			content: "Database",
            action: "Upload",
		},
	] ;

	// "Invoices" list data.
	const invoiceData = [
		{
			title: "Analytics Format",
			code: "Fields required for Analytical Data",
			file: "/docs/analytics_data-format.csv",
			name: "AnaLyticsFormat.csv",
			amount: "Data Management",
		},
		{
			title: "Event Format",
			code: "Fields required for Event Evaluation",
			file: "/docs/evaluation-format.csv",
			name: "EvaluationFormat.csv",
			amount: "Events",
		},
	] ;

	export default ({
		components: {
			CardCredit,
			WidgetSalary,
			CardPaymentMethods,
			CardInvoices,
			CardBillingInfo,
			CardTransactions,
		},
		data() {
			return {
				// Salary cards data
				salaries,

				// Associating "Invoices" list data with its corresponding property.
				invoiceData,
				
			}
		},
		computed: {
			user: function(){
				let token = localStorage.getItem('userToken')
				return jwtDecode(token);
			},
		},
		methods:{
			async databaseAction(){
				let vm = this;
				this.$confirm({
					title: 'Do you want to delete these items?',
					content: ``,
					onOk() {
						let payload = {}
						vm.$api.post("misc/database/backup", payload).then((res) => {
							let response = {...res.data}
							if(!response.error){
								
							} else {
								// show Error
								console.log('there is some error')
							}
						})
					},
					onCancel() {},
				});
			},
		}
	})

</script>

<style lang="scss">
</style>