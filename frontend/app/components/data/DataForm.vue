<template>
    <UForm
        class="space-y-4"
        @submit="onSubmit"
    >
        <UFormGroup
        label="Name"
        name="name"
        >
        <UInput
            v-model="form.course"
            autofocus
        />
        </UFormGroup>

        <UFormGroup
        label="Email"
        name="email"
        >
        <UInput
            v-model="form.course"
        />
        </UFormGroup>

        <div class="flex justify-end gap-3">
        <UButton
            label="Cancel"
            color="gray"
            variant="ghost"
            @click="closeModal"
        />
        <UButton
            type="submit"
            label="Save"
            color="black"
        />
        </div>
    </UForm>
</template>

<script>
import { FormError, FormSubmitEvent } from '#ui/types'
import { jwtDecode } from 'jwt-decode';
const api = useApi()

export default {
  data() {
    return {
        form:{
            course: "",
            years: {
                from: "2024",
                to: "2025",
            },
            reportType: "",
            term: "",
            male: "",
            female: "",
            undergrad: "",
            vacant: "",
            createdBy: 0,
        }
        
    }
  },
  computed:{
    user: function(){
        let profile = LocalStorage.getItem('userData');
        return jwtDecode(profile);
    }
  },
  created(){
    
  },
  methods:{
    closeModal(){
        this.$emit('close')
    },
    validate(val){
        const errors = [];
        if (!val.email) errors.push({ path: 'email', message: 'Required' });
        if (!val.password) errors.push({ path: 'password', message: 'Required' });
        return errors;
    }
  }
}
</script>