<script setup lang="ts">
const countries = [{
  label: 'Male',
  value: 31,
  color: 'red'
}, {
  label: 'Female',
  value: 21,
  color: 'orange'
}, {
  label: 'LGBTQ',
  value: 10,
  color: 'green'
}, {
  label: 'Unknown',
  value: 15,
  color: 'yellow'
}]
</script>

<template>
  <UDashboardCard
    title="Graduate Rates"
    description="ASCOT graduating student for the year"
    icon="i-heroicons-academic-cap"
  >
    <div class="space-y-2">
      <UMeter
        v-for="(country, index) in countries"
        :key="index"
        :value="country.value"
        :label="country.label"
        :color="country.color"
        size="lg"
        class="flex-row-reverse items-center"
        :ui="{ label: { base: 'flex-shrink-0 w-24' }, indicator: { container: '!w-auto' }, meter: { base: 'flex-1' } }"
        indicator
      />
    </div>
  </UDashboardCard>
</template>
