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
            label="File Title"
            name="fileTitle"
          >
            <UInput
              v-model="form.title"
              autofocus
            />
          </UFormGroup>
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
          docsOpt: [],
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
      async previewPDF(){
        const existingPdfBytes = await fetch(this.resourceUrl).then(res => res.arrayBuffer())
        const pdfDoc = await PDFDocument.load(existingPdfBytes)

        const pdfDataUri = await pdfDoc.saveAsBase64({ dataUri: true });
        document.getElementById('pdf').src = pdfDataUri;

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
            timeout: 0,
          })
        }
      },
      async getList(){
        this.docsOpt = []
        api.get("document/get/list").then((res) => {
          let response = {...res.data}
          if(!response.error){
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