<template>
  <div  
    v-for="(cardItem, index) in cardItems"
    :key="index"
    class="w-full"
  >
      <UCard class="rounded-b-xl border-blue-400 border-b-4">
          <div class="flex items-center">
            <div :class="`p-1 bg-${cardItem.color}-400 rounded-md`">
              <UIcon :name="cardItem.icon" class="w-8 h-7 text-white"  />
            </div>
            <span class="font-bold text-center ml-9">{{cardItem.value}}</span>
          </div>
          <br/>
          <div class="text-ellipsis overflow-hidden text-truncate">
              {{cardItem.label}}<br/>
              <span class="text-xs">{{cardItem.caption}}</span>
          </div>
      </UCard>
  </div>
</template>

<script>
export default {
    props:{
      cardItems: {
        type: Array,
        default: []
      }
    }
}
</script>