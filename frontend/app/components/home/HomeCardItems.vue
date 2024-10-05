<script setup lang="ts">
const cardItems = [{
  label: 'Total Enrollment',
  value: 700,
  color: 'red',
  icon: 'i-heroicons-user-group'
}, {
  label: 'Employement',
  value: 30,
  color: 'orange',
  icon: 'i-heroicons-briefcase'
}, {
  label: 'Resources',
  value: 5,
  color: 'yellow',
  icon: 'i-heroicons-clipboard-document-list'
}, {
  label: 'Incoming Graduates',
  value: 100,
  color: 'green',
  icon: 'i-heroicons-academic-cap'
}]
</script>

<template>
    <div  
        v-for="(cardItem, index) in cardItems"
        :key="index"
        class="w-full "
    >
        <UCard>
            <template #header>
                <div class="flex items-center">
                    <UIcon :name="cardItem.icon" class="w-7 h-6" /> 
                    {{cardItem.label}}
                </div>
            </template>
            <div class="text-center">
                {{cardItem.value}}
            </div>
            

            <!-- <template #footer>
                <Placeholder class="h-8" />
            </template> -->
        </UCard>
    </div>
    
</template>
