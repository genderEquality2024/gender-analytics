<template>
    <div class="grid grid-cols-4 gap-4 justify-items-center">
        <span>Year From:</span>
        <USelect 
            v-model="dataValue.from" 
            :options="yearOpts"  
            option-attribute="name"
            @change="emitValue"
        />
        <span>Year To: </span>
        <USelect 
            v-model="dataValue.to" 
            :options="yearOpts" 
            option-attribute="name"
            @change="emitValue"
        />
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
            }
        }
    },
    data() {
        const { modelValue } = this.$props

        return {
            dataValue: modelValue,
        }
    },
    computed:{
        yearOpts(){
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
        }
    },
    methods:{
        moment,
        emitValue(){
            this.$emit('update-modelValue', this.dataValue)
            this.$emit('updateList', this.dataValue)
        }
    }
}
</script>
