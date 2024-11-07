<script setup lang="ts">
import { sub } from 'date-fns'
import type { Period, Range } from '~/types'

const { isNotificationsSlideoverOpen } = useDashboard()
definePageMeta({
  layout: 'user'
})

const items = [[{
  label: 'New mail',
  icon: 'i-heroicons-paper-airplane',
  to: '/inbox'
}, {
  label: 'New user',
  icon: 'i-heroicons-user-plus',
  to: '/users'
}]]


const departments = [
    'AGRICULTURE', 
    'ARTS AND SCIENCES', 
    'EDUCATION', 
    'ENGINEERING', 
    'FISHERIES', 
    'INDUSTRIAL TECHNOLOGY', 
    'INFORMATION TECHNOLOGY'
]

const selected = ref(departments[0])

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
        <UDashboardCard
            class="mb-8"
        >
            <label>Department</label>
            <USelectMenu v-model="selected" :options="departments" />
        </UDashboardCard>
        <!-- ~/components/home/HomeCardItems.vue -->
        <div class="grid grid-rows-3 grid-flow-col gap-4">
            <div class="row-span-3">
                <AnalyticsBarChart
                    :selectedDepartment="selected"
                />
            </div>
            <div class="col-span-2">
                <AnalyticsDonut />
            </div>
            <div class="row-span-2 col-span-2">
                <AnalyticsDonutFull />
            </div>
        </div>
      </UDashboardPanelContent>
    </UDashboardPanel>
  </UDashboardPage>
</template>
