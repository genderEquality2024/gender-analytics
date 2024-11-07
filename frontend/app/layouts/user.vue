<script setup lang="ts">
import { getData, setData } from 'nuxt-storage/local-storage';
const route = useRoute()
const router = useRouter()
// const cToken = checkToken()
const appConfig = useAppConfig()
const { isHelpSlideoverOpen } = useDashboard()

// const userToken = getData('userToken');
// console.log(userToken)
// if (userToken) {
//     router.push("/home")
// } else {
//     router.push("/")
// }

const links = [{
  id: 'home',
  label: 'Home',
  icon: 'i-heroicons-home',
  to: '/home',
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
  label: 'User Management',
  icon: 'i-heroicons-user-group',
  to: '/users',
  tooltip: {
    text: 'Users',
    shortcuts: ['G', 'U']
  }
}, {
  id: 'events',
  label: 'Data Management',
  icon: 'i-heroicons-chart-bar',
  to: '/data/management',
  tooltip: {
    text: 'Users',
    shortcuts: ['G', 'U']
  }
}, {
  id: 'events',
  label: 'Resources',
  icon: 'i-heroicons-chart-pie',
  to: '/events',
  tooltip: {
    text: 'Users',
    shortcuts: ['G', 'U']
  }
}, {
  id: 'events',
  label: 'Events and Evaluation',
  icon: 'i-heroicons-calendar-days',
  to: '/events',
  tooltip: {
    text: 'Users',
    shortcuts: ['G', 'U']
  }
},
// {
//   id: 'settings',
//   label: 'Resources',
//   to: '/settings',
//   icon: 'i-heroicons-document-text',
//   children: [{
//     label: 'General',
//     to: '/settings',
//     exact: true
//   }, {
//     label: 'Members',
//     to: '/settings/members'
//   }, {
//     label: 'Notifications',
//     to: '/settings/notifications'
//   }],
//   tooltip: {
//     text: 'Settings',
//     shortcuts: ['G', 'S']
//   }
// }
]

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
</script>

<template>
  <UDashboardLayout>
    <UDashboardPanel
      :width="250"
      :resizable="{ min: 200, max: 300 }"
      collapsible
    >
      <UDashboardNavbar
        class="!border-transparent"
        :ui="{ left: 'flex-1' }"
      >
        <template #left>
          <TeamsDropdown />
        </template>
      </UDashboardNavbar>

      <UDashboardSidebar>
        <template #header>
          <UDashboardSearchButton />
        </template>

        <UDashboardSidebarLinks :links="links" />

        <!-- <UDivider />

        <UDashboardSidebarLinks
          :links="[{ label: 'Colors', draggable: true, children: colors }]"
          @update:links="colors => defaultColors = colors"
        /> -->

        <div class="flex-1" />

        <UDashboardSidebarLinks :links="footerLinks" />

        <UDivider class="sticky bottom-0" />

        <template #footer>
          <!-- ~/components/UserDropdown.vue -->
          <UserDropdown />
        </template>
      </UDashboardSidebar>
    </UDashboardPanel>

    <slot />

    <!-- ~/components/HelpSlideover.vue -->
    <HelpSlideover />
    <!-- ~/components/NotificationsSlideover.vue -->
    <NotificationsSlideover />

    <ClientOnly>
      <LazyUDashboardSearch :groups="groups" />
    </ClientOnly>
  </UDashboardLayout>
</template>
