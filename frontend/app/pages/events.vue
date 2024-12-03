<template>
<UDashboardPage>
  <UDashboardPanel grow>
    <UDashboardNavbar title="GENDER EQUALITY DASHBOARD">
        <template #right>
          <UButton
            label="Add New Event"
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
        <Qalendar 
          :events="events"
          :config="config"
        />
      </UDashboardPanelContent>


      <UDashboardModal
        v-model="isNewUserModalOpen"
        title="New Event"
        description="Add a new event schedule to calendar"
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
            label="Event Title"
            name="eventTitle"
          >
            <UInput
              v-model="form.title"
              autofocus
            />
          </UFormGroup>
          <UFormGroup
            label="Event Date"
            name="eventTitle"
          >
            <UInput
              type="date"
              v-model="form.eventType"
            />
          </UFormGroup>
          <UFormGroup
            label="Import Evaluation Questionaire"
            name="import"
            help="Please download the template below link"
          >
            <UInput
              @input="(evt) => {return getFile(evt)}"
              type="file" 
              size="sm" 
              icon="i-heroicons-folder"
              accept="application/pdf"
            />
          </UFormGroup>
          <div class="text-right text-sky-500">
            <a href="/docs/evaluation-format.csv" download="evaluation-format.csv" target="_blank">Click Here to Download Template</a>
          </div>

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
import { Qalendar } from "qalendar";
import { jwtDecode } from 'jwt-decode';
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
          content: '',
          fileUploaded: '',
          eventConfig: {
            time: { start: "", end: "" },
            with: "",
            colo: "",
            description: ""
          }
        },
        events: [
            // ...
            {
              title: "Advanced algebra",
              with: "Chandler Bing",
              time: { start: "2024-11-16 12:05", end: "2024-11-16 13:35" },
              color: "yellow",
              isEditable: true,
              id: "753944708f0f",
              description: "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores assumenda corporis doloremque et expedita molestias necessitatibus quam quas temporibus veritatis. Deserunt excepturi illum nobis perferendis praesentium repudiandae saepe sapiente voluptatem!"
            },
            {
              title: "Ralph on holiday",
              with: "Rachel Greene",
              time: { start: "2022-05-10", end: "2022-05-22" },
              color: "green",
              isEditable: true,
              id: "5602b6f589fc"
            }
            // ...
        ],
        config: {
          // see configuration section
          defaultMode: 'month',
        }
      }
  },
  components: {
    Qalendar,
  },
  created(){},
  computed: {
    user: function(){
      let token = localStorage.getItem('userToken')
      token = JSON.parse(token);
      return jwtDecode(token.value);
    }
  },
  methods:{}
}
</script>

<style>
    @import "qalendar/dist/style.css";
</style>