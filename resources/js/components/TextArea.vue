<script lang="ts" setup>
import { ref, computed } from 'vue';

const model = defineModel({ required: true });

const props = defineProps({
    name: String,
    message: String,
    placeholder: { type: String, default: '' },
    id: String,
});
</script>

<template>
    <div class="mb-3 relative">
        <div class="relative">
            <textarea v-bind="$attrs" :id="id" v-model="model" rows="4" cols="50" :placeholder="placeholder"
                :class="['input-form peer', { 'ring-red-500 ring-1 border-none': message }]" />
        </div>
        <p class="text-error absolute pt-1" v-if="message">{{ message }}</p>
    </div>
</template>
