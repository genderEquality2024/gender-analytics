<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const appConfig = useAppConfig()
const api = useApi()
const { isHelpSlideoverOpen } = useDashboard()
import type { FormError } from '#ui/types'
import { getData, setData } from 'nuxt-storage/local-storage';

const userToken = getData('userToken');
// if (userToken) {
//     router.push("/home")
// } else {
//     router.push("/")
// }

const fields = [{
  name: 'username',
  type: 'text',
  label: 'Username',
  placeholder: 'Enter your username'
}, {
  name: 'password',
  label: 'Password',
  type: 'password',
  placeholder: 'Enter your password'
}, {
  name: 'remember',
  label: 'Remember me',
  type: 'checkbox'
}]

const links = [{
  id: 'home',
  label: 'Home',
  icon: 'i-heroicons-home',
  to: '/',
  tooltip: {
    text: 'Home',
    shortcuts: ['G', 'H']
  }
}, {
  id: 'analytics',
  label: 'Analytics',
  icon: 'i-heroicons-presentation-chart-line',
  to: '/analytics',
  tooltip: {
    text: 'Analytics',
    shortcuts: ['G', 'I']
  }
}, {
  id: 'users',
  label: 'Reports',
  icon: 'i-heroicons-chart-pie',
  to: '/users',
  tooltip: {
    text: 'Users',
    shortcuts: ['G', 'U']
  }
}, {
  id: 'settings',
  label: 'Resources',
  to: '/settings',
  icon: 'i-heroicons-document-text',
  children: [{
    label: 'General',
    to: '/settings',
    exact: true
  }, {
    label: 'Members',
    to: '/settings/members'
  }, {
    label: 'Notifications',
    to: '/settings/notifications'
  }],
  tooltip: {
    text: 'Settings',
    shortcuts: ['G', 'S']
  }
}]

const footerLinks = [{
  label: 'Help & Support',
  icon: 'i-heroicons-question-mark-circle',
  click: () => isHelpSlideoverOpen.value = true
}]

const groups = [{
  key: 'links',
  label: 'Go to',
  commands: links.map(link => ({ ...link, shortcuts: link.tooltip?.shortcuts }))
}, {
  key: 'code',
  label: 'Code',
  commands: [{
    id: 'source',
    label: 'View page source',
    icon: 'i-simple-icons-github',
    click: () => {
      window.open(`https://github.com/nuxt-ui-pro/dashboard/blob/main/pages${route.path === '/' ? '/index' : route.path}.vue`, '_blank')
    }
  }]
}]

const defaultColors = ref(['green', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet'].map(color => ({ label: color, chip: color, click: () => appConfig.ui.primary = color })))
const colors = computed(() => defaultColors.value.map(color => ({ ...color, active: appConfig.ui.primary === color.label })))

const btnLoading = ref<Boolean>(false)
const validate = (state: any) => {
  const errors: FormError[] = []
  if (!state.username) errors.push({ path: 'username', message: 'Username is required' })
  if (!state.password) errors.push({ path: 'password', message: 'Password is required' })
  return errors
}

async function onSubmit (data: any) {
  btnLoading.value = true
  api.post("auth/login", data).then((res) => {
    let response = {...res.data}
    if(!response.error){
      setData("userToken", response.jwt)
      router.push("/home")
    } else {
      // show Error
    }
    btnLoading.value = false
  })
}
</script>

<template>
  <UDashboardLayout>
    <UCard class="max-w-sm w-full">
      <UAuthForm
        :fields="fields"
        :validate="validate"
        align="top"
        :ui="{ base: 'text-center', footer: 'text-center' }"
        @submit="onSubmit"
        :submitButton="{
          loading: btnLoading
        }"
      >
        <template #icon>
          <img src="/imgs/ASCT_logo2.png"  />
        </template>
        <template #title>
          GENDER EQUALITY ANALYTICS
        </template>

        <template #password-hint>
          <NuxtLink to="/" class="text-primary font-medium">Forgot password?</NuxtLink>
        </template>
        <!-- <template #validation>
          <UAlert color="red" icon="i-heroicons-information-circle-20-solid" title="Error signing in" />
        </template> -->
        <template #footer>
          By signing in, you agree to our <NuxtLink to="/" class="text-primary font-medium">Terms of Service</NuxtLink>.
        </template>
      </UAuthForm>
    </UCard>

    <slot />
  </UDashboardLayout>
</template>
