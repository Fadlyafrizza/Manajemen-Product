<script setup lang="ts">
import { watch } from 'vue';

interface Props {
    show: boolean;
    message: string;
    duration?: number;
}

interface Emits {
    (e: 'close'): void;
}

const props = withDefaults(defineProps<Props>(), {
    duration: 3000
});

const emit = defineEmits<Emits>();

watch(() => props.show, (show) => {
    if (show && props.duration > 0) {
        setTimeout(() => {
            emit('close');
        }, props.duration);
    }
});

function handleClose() {
    emit('close');
}
</script>


<template>
    <Transition enter-active-class="transition-all duration-300 ease-out" enter-from-class="-translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100" leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="translate-y-0 opacity-100" leave-to-class="-translate-y-full opacity-0">
        <div v-if="show" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[999]">
            <div
                class="shadow-lg bg-white rounded-lg border border-zinc-600/50 p-4 max-w-sm min-w-80 flex items-center justify-center">
                <div class="text-sm font-medium text-zinc-700 text-center w-full">
                    <span>{{ message }}</span>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.bg-black-elegant {
    background-color: #1f1f1f;
}

.color-white-elegant {
    color: #f5f5f5;
}
</style>
