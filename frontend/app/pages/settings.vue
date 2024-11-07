<script setup lang="ts">
definePageMeta({
  layout: 'user'
})

const links = [[{
  label: 'General',
  icon: 'i-heroicons-user-circle',
  to: '/settings',
  exact: true
}, {
  label: 'Account',
  icon: 'i-heroicons-user-group',
  to: '/settings/members'
}]]
</script>

<template>
  <UDashboardPage>
    <UDashboardPanel grow>
      <UDashboardNavbar title="Settings" />

      <UDashboardToolbar class="py-0 px-1.5 overflow-x-auto">
        <UHorizontalNavigation :links="links" />
      </UDashboardToolbar>

      <NuxtPage />
    </UDashboardPanel>
  </UDashboardPage>
</template>
