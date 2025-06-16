<script lang="ts" setup>
import { ref, onMounted, onUnmounted } from 'vue'

const currentTime = ref(new Date().toLocaleTimeString())

let intervalId: number

onMounted(() => {
    intervalId = window.setInterval(() => {
        currentTime.value = new Date().toLocaleTimeString()
    }, 1000)
})

onUnmounted(() => {
    clearInterval(intervalId)
})
</script>

<template>
    <div class="my-24">
        <header class="my-8">
            <div class="flex items-center justify-between">
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold">Welcome To Dashboard </h1>
                    <span>Current Time: {{ currentTime }}</span>
                </div>
                <div class="flex gap-3 border-b py-2 border-zinc-900">
                    <div class="size-6 bg-teal-600">
                    </div>
                    <div class="size-6 bg-amber-600">
                    </div>
                    <div class="size-6 bg-rose-600">
                    </div>
                </div>
            </div>
        </header>

        <div class="space-y-4">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-8 h-64 bg-white shadow-md border rounded-sm">

                </div>
                <div class="col-span-4 h-64 bg-white shadow-md border rounded-sm">

                </div>
            </div>
            <div class="col-span-8 h-64 bg-white shadow-md border rounded-sm">

            </div>

        </div>
    </div>
</template>
