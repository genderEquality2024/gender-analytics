<template>
    <div>
        <div v-if="selectionType === 'single'" class="grid grid-cols-2 gap-2 justify-items-center">
            <span>{{ label }}:</span>
            <USelect 
                v-model="dataValue" 
                :options="yearOpts" 
                option-attribute="name"
                @change="emitValue"
              />
        </div>
        <div v-if="selectionType === 'separate'" class="grid grid-cols-4 gap-4 justify-items-center">
            <span>Year From:</span>
            <USelect 
                v-model="dataValue.from" 
                :options="yearMultipleOpts"  
                option-attribute="name"
                @change="emitValue"
            />
            <span>Year To: </span>
            <USelect 
                v-model="dataValue.to" 
                :options="yearMultipleOpts" 
                option-attribute="name"
                @change="emitValue"
            />
        </div>
    </div>
    
</template>

<script>
import moment from 'moment'

export default {
    props:{
        modelValue: {
            type: Object,
            default: {
                from: "",
                to: ""
            } || "2024 - 2025"
        },
        selectionType: {
            type: String,
            default: "single"
        },
        label: {
            type: String,
            default: "Select Option"
        }
    },
    data() {
        const { modelValue } = this.$props

        return {
            dataValue: modelValue,
        }
    },
    computed:{
        yearMultipleOpts(){
            let res = []
            let startDate = 2000

            for (let index = 49; index < 90; index++) {
                let val = moment(startDate).add(index, 'y').format('YYYY')
                res.push({
                    name: val,
                    value: val
                })
            }

            return res
        },
        yearOpts(){
            let res = []
            let startDate = 2000

            for (let index = 49; index < 90; index++) {
                let val = moment(startDate).add(index, 'y').format('YYYY')
                let val2 = moment(startDate).add(index + 1, 'y').format('YYYY')
                res.push({
                    name: `${val} - ${val2}`,
                    value: `${val} - ${val2}`
                })
            }
            
            return res
        }
    },
    methods:{
        moment,
        emitValue(val){
            this.$emit('update-modelValue', val)
            this.$emit('updateList', val)
        }
    }
}
</script>
