<script setup lang="ts">
import { sub } from 'date-fns'
import type { Period, Range } from '~/types'
definePageMeta({
  layout: 'user'
})

const { isNotificationsSlideoverOpen } = useDashboard()

const items = [[{
  label: 'New mail',
  icon: 'i-heroicons-paper-airplane',
  to: '/inbox'
}, {
  label: 'New user',
  icon: 'i-heroicons-user-plus',
  to: '/users'
}]]

const range = ref<Range>({ start: sub(new Date(), { days: 14 }), end: new Date() })
const period = ref<Period>('daily')
</script>

<template>
  <UDashboardPage>
    <UDashboardPanel grow>
      <UDashboardNavbar title="GENDER EQUALITY DASHBOARD">
        <template #right>
          <UTooltip
            text="Notifications"
            :shortcuts="['N']"
          >
            <UButton
              color="gray"
              variant="ghost"
              square
              @click="isNotificationsSlideoverOpen = true"
            >
              <UChip
                color="red"
                inset
              >
                <UIcon
                  name="i-heroicons-bell"
                  class="w-5 h-5"
                />
              </UChip>
            </UButton>
          </UTooltip>
        </template>
      </UDashboardNavbar>

      <UDashboardToolbar>
        <template #left>
          Tracking and Promoting Gender Equality at ASCOT
        </template>
      </UDashboardToolbar>

      <UDashboardPanelContent>
        
        <!-- ~/components/home/HomeCardItems.vue -->
        <div class="grid grid-cols-4 gap-4 justify-items-center">
          <HomeCardItems />
        </div>

        <div class="grid lg:grid-cols-2 lg:items-start gap-8 mt-8">
          <!-- ~/components/home/HomeChart.vue -->
          <HomeChart
            :period="period"
            :range="range"
          />
          <!-- ~/components/home/HomeCountries.vue -->
          <HomeCountries />
          
        </div>
      </UDashboardPanelContent>
    </UDashboardPanel>
  </UDashboardPage>
</template>
