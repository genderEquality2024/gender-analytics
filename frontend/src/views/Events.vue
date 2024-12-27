<!-- 
	This is the user profile page, it uses the dashboard layout in: 
	"./layouts/Dashboard.vue" .
 -->

 <template>
	<div>

		<!-- Header Background Image -->
		<div class="profile-nav-bg" style="background-image: url('images/bg-profile.jpg')"></div>
		<!-- / Header Background Image -->

		<!-- User Profile Card -->
		<a-card :bordered="false" class="card-profile-head" :bodyStyle="{padding: 0,}">
			<template #title>
				<a-row type="flex" align="middle">
					<a-col :span="24" :md="12" class="col-info">
						<a-avatar 
							shape="square" 
							:size="74" 
							icon="calendar" 
							:style="{ backgroundColor: 'orange' }"
						/>
						<div class="avatar-info">
							<h4 class="font-semibold m-0">EVENTS</h4>
							<p>List of Event and Evaluation</p>
						</div>
					</a-col>
					<a-col :span="24" :md="12" style="display: flex; align-items: center; justify-content: flex-end">
						<a-button @click="addUSerModal = true" type="primary">Add Event</a-button>
					</a-col>
				</a-row>
			</template>
		</a-card>
		<!-- User Profile Card -->

		<a-row type="flex" :gutter="24">

			<!-- Calendar Settings Column -->
			<a-col :span="24" :md="24" class="mb-24">
				<a-card :bordered="false" class="header-solid h-full" :bodyStyle="{paddingTop: 0, paddingBottom: 0 }">
					<!-- <template #title>
						<h6 class="font-semibold m-0">Event Calendar</h6>
					</template> -->
					<a-calendar @select="getEventDetails" @panelChange="onPanelChange">
						<ul slot="dateCellRender" slot-scope="value" class="events">
							<li v-for="item in getListData(value)" :key="item.id">
								{{ item.title }}
							</li>
						</ul>
					</a-calendar>
			
				</a-card>
				
			</a-col>
			

		</a-row>

		<a-modal
			v-model="addUSerModal"
			title="Event Details"
			centered
		>
			<template slot="footer">
				<a-button key="submit" type="primary" :loading="loading" @click="addToEvent">
					Submit
				</a-button>
			</template>

			<a-form :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }">
				<a-row :gutter="24">
					<a-col :span="24" :sm="12">
						<a-form-item label="Title">
							<a-input
								v-model="form.title"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="12">
						<a-form-item label="Code">
							<a-input
								v-model="form.eventCode"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="24">
						<a-form-item label="Decription">
							<a-input
								type="textarea"
								v-model="form.description"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="24">
						<a-form-item label="Event Date">
							<a-range-picker @change="onChange" />
						</a-form-item>
					</a-col>
				</a-row>
			</a-form>
		</a-modal>

		<a-modal
			v-if="eventDetails !== null"
			v-model="eventDetailsModal"
			:title="eventDetails.title"
			centered
		>	
			<template slot="footer">
				<a-button
					type="primary"
					:disabled="csvData.length === 0"
					:loading="uploading"
					style="margin-top: 16px"
					@click="uploadCSVData(csvData)"
				>
						{{ uploading ? 'Uploading' : 'Start Upload' }}
				</a-button>
			</template>
			<a-row :gutter="24">
				<a-col :span="24" :sm="24">
					<a-form-item label="Description">
						<span>{{ eventDetails.description }}</span>
					</a-form-item>
				</a-col>
				<a-col v-if="eventEvaluated" :span="24" :sm="24">
					<a-alert
                        message="Informational Notice"
                        description="You can now view the responses of the instructors on the event you selected"
                        type="info"
                        show-icon
                    />
					<a href="/docs/evaluation-format.csv" download="evaluation-format.csv" target="_blank">Click Here to View the Responses</a>
				</a-col>
				<!-- eventEvaluated: false,
				eventUploaded: false, -->
				<a-col v-if="!eventUploaded" :span="24" :sm="24">
					<a-form-item label="Upload Questionaires">
						<a-upload :file-list="fileList" :remove="handleRemove" :before-upload="beforeUpload">
							<a-button> <a-icon type="upload" /> Select File </a-button>
						</a-upload>
					</a-form-item>
				</a-col>
				<a-col v-if="!eventUploaded" :span="24" :sm="24">
					<a href="/docs/evaluation-format.csv" download="evaluation-format.csv" target="_blank">Click Here to Download Template</a>
				</a-col>
			</a-row>
		</a-modal>
	</div>
</template>

<script>
	import { jwtDecode } from 'jwt-decode';
	import * as d3 from "d3"
	import moment from 'moment'
	export default ({
		data() {
			return {
				addUSerModal: false,
				eventDetailsModal: false,
				eventDetails: null,
				eventEvaluated: false,
				eventUploaded: false,
				form:{
					title: "",
					description: "",
					month: "",
					eventDate: "",
					eventCode: ""
				},
				rangeDate: [],
				csvData: [],
				uploading: false,
				eventList: [],
				currMonth: moment().format("M"),
				currYear: moment().format("YYYY"),
				eventTypes: [
					{
						label: "Enrollment",
						value: "enrollment",
					},
					{
						label: "Graduates",
						value: "graduate",
					},
					{
						label: "Employment",
						value: "employee",
					},
				],
			}
		},
		computed:{
			user: function(){
				let token = localStorage.getItem('userToken')
				return jwtDecode(token);
			},
		},
		created(){
			this.getList();
		},
		methods:{
			moment,
			onChange(date, dateString) {
				this.rangeDate = dateString
			},
			async addToEvent(){
				var start = moment(this.rangeDate[0], "YYYY-MM-DD");
				var end = moment(this.rangeDate[1], "YYYY-MM-DD");
				let daysDiff = moment.duration(end.diff(start)).asDays();


				for (let index = 0; index <= daysDiff; index++) {
					let evntMonth = moment(this.rangeDate[0]).add(index, 'd').format('M')
					let evntDay = moment(this.rangeDate[0]).add(index, 'd').format('DD')
					let evntYear = moment(this.rangeDate[0]).add(index, 'd').format('YYYY')
					let evntDate = moment(this.rangeDate[0]).add(index, 'd').format('YYYY-MM-DD')
					
					let payload = {
						...this.form,
						month: evntMonth,
						days: evntDay,
						year: evntYear,
						eventDate: evntDate
					}
					this.$api.post("events/add", payload).then((res) => {
						let response = {...res.data}
						if(!response.error){
							console.log('data uploaded')
						} else {
							// show Error
							console.log('there is some error')
						}
					})
				}

				this.getList()
				this.addUSerModal = false
				this.form = {
					title: "",
					description: "",
					month: "",
					eventDate: "",
					eventCode: ""
				}

			},
			async getList(month = moment().format("M"), year = moment().format("YYYY")){
				let payload = {
					month,
					year
				}
				this.$api.post("events/list", payload).then((res) => {
					let response = {...res.data}
					if(!response.error){
						this.eventList = res.data
					} else {
						// show Error
						console.log('there is some error')
					}
				})
			},
			getListData(value) {
				let listData;
				let day = value.date();
				if(this.eventList.length > 0){
					listData = this.eventList.filter(el =>
						Number(el.days) === day && 
						Number(el.month) === Number(this.currMonth) &&
						Number(el.year) === Number(this.currYear)
					)
				} else {
					listData = []
				}
				
				return listData || [];
			},
			onPanelChange(value, mode){
				this.currMonth = value.format('M')
				this.currYear = value.format('YYYY')
				this.getList(value.format('M'), value.format('YYYY'))
			},
			getEventDetails(value){
				let day = value.date();
				let data = this.eventList.filter(el =>
				 	Number(el.days) === day && 
					Number(el.month) === Number(this.currMonth) &&
					Number(el.year) === Number(this.currYear)
				)

				this.eventDetails = data.length > 0 ? data[0] : null
				this.eventDetailsModal = true
				this.checkEvelauatedResponse();
				this.checkEventQuestionaires();
			},
			async checkEvelauatedResponse(){
				let payload = {
					eventId: this.eventDetails.eventCode
				}
				this.$api.post("evaluation/response/get", payload).then((res) => {
					let response = {...res.data}
					if(!response.error){
                        
						if(res.data.length > 0){
							// hide upload
							this.eventEvaluated = true;
						} else {
							// enable upoload
							this.eventEvaluated = false;
						}
					} else {
						// show Error
						console.log('there is some error')
					}
				})
			},
			async checkEventQuestionaires(){
				let payload = {
					eventId: this.eventDetails.eventCode
				}
				this.$api.post("evaluation/get/questions", payload).then((res) => {
					let response = {...res.data}
					if(!response.error){
                        if(res.data.length > 0){
							// hide upload
							this.eventUploaded = true;
						} else {
							// enable upoload
							this.eventUploaded = false;
						}
					} else {
						// show Error
						console.log('there is some error')
					}
				})
			},
			handleRemove(file) {
				this.csvData = [];
			},
			async beforeUpload(file) {
				var reader = new FileReader();
				reader.readAsText(new Blob(
					[file],
					{"type": file.type}
				))
				const fileContent = await new Promise(resolve => {
					reader.onloadend = (event) => {
						resolve(event.target.result)
					}
				})
				let csvData = d3.csvParse(fileContent)
				

				this.csvData = csvData
				return false;
			},
			handleChange(info) {
				console.log(info.file.status)
				if (info.file.status !== 'uploading') {
					console.log(info.file, info.fileList);
				}
				if (info.file.status === 'done') {
					this.$message.success(`${info.file.name} file uploaded successfully`);
				} else if (info.file.status === 'error') {
					this.$message.error(`${info.file.name} file upload failed.`);
				}
			},
			async uploadCSVData(data){
				this.uploading = true
				data = data.map((el) => {
					return {
						...el,
						eventId: this.eventDetails.eventCode,
						createdBy: Number(this.user.userId)
					}
				})

				const dataUploaded = await new Promise((resolve, reject) => {
					let uploaded = 0
					data.forEach(el => {
						this.$api.post("evaluation/create/content", el).then((res) => {
							let response = {...res.data}
							if(!response.error){
							console.log('data uploaded')
							} else {
							// show Error
							console.log('there is some error')
							}
						})
						uploaded += 1
					});

					if(uploaded === data.length){
						this.$message.success(`File uploaded successfully`);
						resolve({
							message: 'Upload complete'
						})
					} else {
					reject()
					}
				})
			
				this.getList();
				this.csvData = []
				this.eventDetails = null
				this.eventDetailsModal = false
				this.uploading = false
			
			},
			
		}
	})

</script>

<style lang="scss">
</style>