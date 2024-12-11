<template>
<UDashboardPage>
  <UDashboardPanel grow>
    <UDashboardNavbar title="GENDER EQUALITY DASHBOARD">
        <template #right>
          <UButton
            label="Add New Resource"
            trailing-icon="i-heroicons-plus"
            color="gray"
            @click="isNewUserModalOpen = true"
          />
        </template>
      </UDashboardNavbar>

      <UDashboardToolbar>
        <template #left>
          Resource Preview
        </template>
        <template #right>
          <USelect 
            v-model="resourceUrl" 
            :options="docsOpt" 
            option-attribute="name"
          />
          <UButton
            label="Remove Resource"
            trailing-icon="i-heroicons-trash"
            color="red"
            :disabled="docsOpt.length === 1"
            @click="removeResource"
          />
        </template>
      </UDashboardToolbar>

      <UDashboardPanelContent>
        <iframe id="pdf" style="width: 100%; height: 100%; border: none;"></iframe>
      </UDashboardPanelContent>


      <UDashboardModal
        v-model="isNewUserModalOpen"
        title="New Resource"
        description="Upload Resources to be Preview"
        :ui="{ width: 'sm:max-w-md' }"
      >
        <!-- ~/components/users/UsersForm.vue -->
        <!-- <DataForm @close="isNewUserModalOpen = false" /> -->
        <UForm
          :state="form"
          class="space-y-4"
          @submit="onSubmit"
        >
          <UFormGroup
            label="Import File"
            name="import"
            help="Only PDF file can be uploaded"
          >
            <UInput
              @input="(evt) => {return getFile(evt)}"
              type="file" 
              size="sm" 
              icon="i-heroicons-folder"
              accept="application/pdf"
            />
          </UFormGroup>

          <!-- <UFormGroup
            label="Import Resource Evaluation"
            name="import"
            help="This will automatically insert the data and close the form once the upload of data is complete"
          >
            <UInput
              @input="(evt) => {return readCSVFile(evt)}"
              type="file" 
              size="sm" 
              icon="i-heroicons-folder" 
            />
          </UFormGroup>
          <div class="text-right text-sky-500">
            <a href="/docs/analytics_data-format.csv" download="analytics_data-format.csv" target="_blank">Click Here to Download Template</a>
          </div> -->

          <div class="mt-1 flex justify-end gap-3">
            <UButton
              type="submit"
              label="Save"
              color="black"
            />
          </div>
        </UForm>
      </UDashboardModal>
  </UDashboardPanel>
</UDashboardPage>
</template>

<script>
import { jwtDecode } from 'jwt-decode';
import { PDFDocument, StandardFonts, rgb } from 'pdf-lib'
import * as d3 from "d3"
const api = useApi()
const toast = useToast()
definePageMeta({
  layout: 'user'
})

export default {
    data() {
        return {
          isNewUserModalOpen: false,
          resourceUrl: 'docs/resources.pdf',
          docList: [],
          docsOpt: [],
          evaluationList:[],
          form: {
            title: '',
            content: ''
          }
        }
    },
    created(){
      this.previewPDF()
      this.getList()
    },
    watch:{
      resourceUrl(){
        this.previewPDF();
      },
    },
    computed: {
      user: function(){
        let token = localStorage.getItem('userToken')
        token = JSON.parse(token);
        return jwtDecode(token.value);
      }
    },
    methods:{
      async getFile(file){
        
        let filePath = file.target.files[0]
        let fileBase = await this.getBase64(filePath)
        this.form.title = filePath.name.replace('.pdf', '')
        this.form.content = fileBase
      },
      async getBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
      },
      async readCSVFile(file){
        var reader = new FileReader();
        let filePath = file.target.files[0]
        reader.readAsText(new Blob(
          [filePath],
          {"type": file.type}
        ))
        const fileContent = await new Promise(resolve => {
          reader.onloadend = (event) => {
            resolve(event.target.result)
          }
        })
        let csvData = d3.csvParse(fileContent)
        

        csvData = csvData.map((el) => {
          return {
            ...el,
            createdBy: Number(this.user.userId)
          }
        })

        this.evaluationList = csvData
      },
      async previewPDF(){
        const existingPdfBytes = await fetch(this.resourceUrl).then(res => res.arrayBuffer())
        const pdfDoc = await PDFDocument.load(existingPdfBytes)

        const pdfDataUri = await pdfDoc.saveAsBase64({ dataUri: true });
        document.getElementById('pdf').src = pdfDataUri;

      },
      async removeResource(){
        let filterDoc = this.docList.filter((el) => { return el.content === this.resourceUrl })
        
        let payload = {
          cId: filterDoc[0].id
        }

        api.post("document/delete/content", payload).then((res) => {
          let response = {...res.data}
          if(!response.error){
            this.clearForm();
            this.getList();
            toast.add({
              id: 'success_submit',
              title: 'Delete Success',
              description: 'Please check',
              icon: 'i-octicon-check-circle-fill-24',
              color: "green",
              timeout: 1000,
            })
          } else {
            toast.add({
              id: 'error_submit',
              title: 'Delete Failed.',
              description: 'Please contact your administrator.',
              icon: 'i-octicon-alert-24',
              color: "red",
              timeout: 1000,
            })
            // show Error
            console.log('there is some error')
          }
        })
      },
      async onSubmit(){
        if(this.form.content !== '' && this.form.title !== ""){
          let payload = {
            ...this.form,
            createdBy: Number(this.user.userId)
          }

          api.post("document/create/content", payload).then((res) => {
            let response = {...res.data}
            if(!response.error){
              this.clearForm();
              this.getList();
              this.isNewUserModalOpen = false
            } else {
              // show Error
              console.log('there is some error')
            }
          })
        } else {
          toast.add({
            id: 'error_submit',
            title: 'Submit Failed.',
            description: 'Please fill the required fields.',
            icon: 'i-octicon-alert-24',
            color: "red",
            timeout: 1000,
          })
        }
      },
      async getList(){
        this.docsOpt = []
        api.get("document/get/list").then((res) => {
          let response = {...res.data}
          if(!response.error){
            this.docList = response.list
            this.docsOpt = response.list.map(el => {
              return {
                name: el.title,
                value: el.content
              }
            })
            this.resourceUrl = this.docsOpt[0].value
          } else {
            // show Error
            console.log('there is some error')
          }
        })
      },
      clearForm(){
        this.form = {
          title: '',
          content: '',
        }
      },
    }
}
</script>