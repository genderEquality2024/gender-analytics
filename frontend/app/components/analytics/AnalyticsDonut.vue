<script setup lang="ts">
import { defineComponent } from 'vue'
import { VisSingleContainer, VisDonut, VisBulletLegend } from '@unovis/vue'
import { dataHalf, DataRecord } from './sample'

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
          Population Rate
        </p>
        <p class="text-3xl text-gray-900 dark:text-white font-semibold">
          {{ total }}
        </p>
      </div>
    </template>

    <VisSingleContainer :data="dataHalf" >
        <VisDonut
            :value="d => d.value"
            :showEmptySegments="true"
            :arcWidth="50"
            :angleRange="[-1.5707963267948966, 1.5707963267948966]"
            :backgroundAngleRange="[1.5707963267948966,0]"
            centralLabel="60.62 %" 
            centralSubLabel="Population rate for the SY 2025"
        />
    </VisSingleContainer>
  </UDashboardCard>
</template>