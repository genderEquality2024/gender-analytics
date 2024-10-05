<script setup lang="ts">
import { VisXYContainer, VisGroupedBar, VisAxis, VisBulletLegend } from '@unovis/vue'
import { dataBar, capitalize, ElectionDatum, colors, getSelectedDepartment } from './sample'

const props = defineProps({
  selectedDepartment: {
    type: String,
    required: false
  }
})

const items = Object.entries(colors).map(([n, c]) => ({
  name: capitalize(n),
  color: c,
}))
const x = (_: ElectionDatum, i: number) => i
const y = [
  (d: ElectionDatum) => d.male,
  (d: ElectionDatum) => d.female,
]
const color = (d: ElectionDatum, i: number) => items[i].color
const xTicks = (i: number) => {
  if (!dataBar[i]) {
    return ''
  }

  return  `${dataBar[i].department}`
}
// const xTicks = (d: ElectionDatum, i: number): string => `${dataBar[i]}`
</script>

<template>
  <UDashboardCard
    ref="cardRef"
    class="p-3"
    :ui="{ body: { padding: '!pb-3 !px-0' } as any }"
  >
    <template #header>
      <div>
        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">
          Information Technology
        </p>
        <VisBulletLegend :items="items" />
      </div>
    </template>

    <VisXYContainer :data="dataBar">
      <VisGroupedBar :data="dataBar" :x="x" :y="y" :color="color" />
      <VisAxis type="x" :tickFormat="xTicks"  label="Department Name" />
      <VisAxis type="y" :tickFormat="(value) => value" label="Number of Student In Department" />
    </VisXYContainer>
  </UDashboardCard>
</template>

<style scoped>
.unovis-xy-container {
  --vis-crosshair-line-stroke-color: rgb(var(--color-primary-500));
  --vis-crosshair-circle-stroke-color: #fff;

  --vis-axis-grid-color: rgb(var(--color-gray-200));
  --vis-axis-tick-color: rgb(var(--color-gray-200));
  --vis-axis-tick-label-color: rgb(var(--color-gray-400));

  --vis-tooltip-background-color: #fff;
  --vis-tooltip-border-color: rgb(var(--color-gray-200));
  --vis-tooltip-text-color: rgb(var(--color-gray-900));
}

</style>
