<script setup lang="ts">
import { defineComponent } from 'vue'
import { VisSingleContainer, VisDonut, VisBulletLegend } from '@unovis/vue'
import { data, DataRecord } from './sample'

const legendItems = Object.entries(data).map(([_, data]) => ({
  name: data.key.charAt(0).toUpperCase() + data.key.slice(1)
}))
const value = (d: number) => d
</script>

<template>
  <UDashboardCard
    ref="cardRef"
    :ui="{ body: { padding: '!pb-3 !px-0' } as any }"
  >
    <template #header>
      <div>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">
          Gender Rates
        </p>
      </div>
    </template>

    <VisSingleContainer :data="data" >
        <VisDonut
            :value="d => d.value"
            :showEmptySegments="true"
            :arcWidth="50"
        />
    </VisSingleContainer>
  </UDashboardCard>
</template>