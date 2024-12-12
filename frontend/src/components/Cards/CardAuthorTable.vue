<template>

	<!-- Authors Table Card -->
	<a-card :bordered="false" class="header-solid h-full" :bodyStyle="{padding: 0,}">
		<template #title>
			<a-row type="flex" align="middle">
				<a-col :span="24" :md="12">
					<h5 class="font-semibold m-0">User's List</h5>
				</a-col>
				<a-col :span="24" :md="12" style="display: flex; align-items: center; justify-content: flex-end">
					<a-button type="primary"  @click="addUSerModal = true"> 
						<a-icon type="user-add" />Add New User 
					</a-button>
				</a-col>
			</a-row>
		</template>
		<a-table :columns="columns" :data-source="data" :pagination="false">

			<template slot="name" slot-scope="name">
				<p>{{ `${name.firstName} ${name.lastName}` }}</p>
			</template>

			<template slot="status" slot-scope="status">
				<a-tag class="tag-status" :class="Number(status) === 1 ? 'ant-tag-green' : 'ant-tag-red'">
					{{ Number(status) === 1 ? "Active" : "Deactive" }}
				</a-tag>
			</template>

			<template slot="editBtn" slot-scope="row">
				<a-button type="link" :data-id="row.key" class="btn-edit">
					Edit
				</a-button>
			</template>

		</a-table>

		<a-modal
			v-model="addUSerModal"
			title="Add User Form"
			centered
		>
		<template slot="footer">
			<a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
				Submit
			</a-button>
		</template>
			<a-form :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }">
				<a-row :gutter="24">
					<a-col :span="24" :sm="12">
						<a-form-item label="Username">
							<a-input
								v-model="form.username"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="12">
						<a-form-item label="Password">
							<a-input
								type="password"
								v-model="form.password"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="24">
						<a-form-item label="User Type">
							<a-select
								v-model="form.userType"
								placeholder="Select Type of User"
								:options="userTypeOpt"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="8">
						<a-form-item label="First Name and Suffix">
							<a-input
								v-model="form.firstName"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="8" >
						<a-form-item label="Middle Name">
							<a-input
								v-model="form.middleName"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="8" >
						<a-form-item label="Last Name">
							<a-input
								v-model="form.lastName"
							/>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="12" >
						<a-form-item label="Gender">
							<a-select
								v-model="form.sex"
							>
								<a-select-option value="Male">
									Male
								</a-select-option>
								<a-select-option value="Female">
									Female
								</a-select-option>
							</a-select>
						</a-form-item>
					</a-col>
					<a-col :span="24" :sm="12" >
						<a-form-item label="Contact">
							<a-input
								v-model="form.contact"
							/>
						</a-form-item>
					</a-col>
				</a-row>
				
				

				
				
			</a-form>
		</a-modal>
	</a-card>
	<!-- / Authors Table Card -->

</template>

<script>

	export default ({
		props: {
			data: {
				type: Array,
				default: () => [],
			},
			columns: {
				type: Array,
				default: () => [],
			},
		},
		data() {
			return {
				// Active button for the "Authors" table's card header radio button group.
				authorsHeaderBtns: 'all',
				addUSerModal: false,
				form: {
					username: '',
					password: '',
					firstName: '',
					lastName: '',
					middleName: '',
					suffix: '',
					sex: '',
					email: '',
					contact: '',
					userType: null
				},
				userTypeOpt: [
					{
						label: "Admin",
						value: 1,
					},
					{
						label: "Employee",
						value: 2,
					},
				],
			}
		},
		methods:{
			async onSubmit(){
				let payload = {
					...this.form,
					status: 1
				}

				this.$api.post("users/create", payload).then((res) => {
					let response = {...res.data}
					if(!response.error){
						this.clearForm();
						this.$emit('updateTable')
						this.addUSerModal = false
					} else {
						// show Error
						console.log('there is some error')
					}
				})
			
			},
			clearForm(){
				this.form = {
					username: '',
					password: '',
					firstName: '',
					lastName: '',
					middleName: '',
					suffix: '',
					sex: '',
					email: '',
					contact: '',
					userType: null
				}
			}
		}
	})

</script>