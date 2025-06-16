<script setup>

import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    href: {
        type: String,
        required: true
    },
    active: {
        type: Boolean,
        default: false
    }
})

const animationClass = ref('init-state')
const hoverCompleted = ref(false)
let hoverTimer = null

const startHover = () => {
    clearTimeout(hoverTimer)
    animationClass.value = 'hover-right'

    hoverTimer = setTimeout(() => {
        hoverCompleted.value = true
    }, 300)
}

const endHover = () => {
    clearTimeout(hoverTimer)

    if (hoverCompleted.value) {
        animationClass.value = 'unhover-right'
    } else {
        animationClass.value = 'unhover-left'
    }

    hoverCompleted.value = false
}

</script>

<template>
    <Link :href="href"
        class="group font-semibold tracking-tight rounded-md text-zinc-800 transition-all duration-300 ease-in-out"
        @mouseenter="startHover" @mouseleave="endHover">
    <span class="relative inline-block transition-all duration-300 ease-in-out" :class="[
        active ? 'active-link' : '',
        animationClass
    ]">
        <slot></slot>
    </span>
    </Link>
</template>

<style scoped>
.relative {
    position: relative;
}

.relative::after {
    content: '';
    position: absolute;
    bottom: 2px;
    height: 2px;
    background: linear-gradient(to right, #3f3f46, #3f3f46);
    transition: width 0.3s ease-in-out;
}

.init-state::after {
    left: 0;
    right: auto;
    width: 0;
}

.hover-right::after {
    left: 0;
    right: auto;
    width: 100%;
}

.unhover-right::after {
    left: auto;
    right: 0;
    width: 0;
}

.unhover-left::after {
    left: 0;
    right: auto;
    width: 0;
}

.active-link::after {
    left: 0;
    width: 100% !important;
}
</style>
