<template>
    <div class="space-y-10">
        <div class="grid grid-cols-5 gap-5">
            <UCard class="col-span-3">
                <UStepper :items="items" class="w-full" />

            </UCard>
            <UCard class="col-span-2">
                <UStepper :items="items" class="w-full" />
                <div class="space-x-1 mt-10">
                   <QadTransactionValidateStudent school_id="1"/>
                    <UButton color="neutral" variant="solid" label="Activity Log" />
                    <UButton color="primary" variant="solid" label="SO PREVIEW" />
                </div>
            </UCard>
        </div>
        <QadTransactionStudentTable />


    </div>
</template>
<script setup lang="ts">
definePageMeta({
    middleware: ['qad-middleware'],
    layout: 'adminlayout',
});
import type { StepperItem } from '@nuxt/ui'

const items = ref<StepperItem[]>([
    {
        title: 'Form 9',


    },
    {
        title: 'Evaluator',


    },
    {
        title: 'Checker',

    }
])
</script>