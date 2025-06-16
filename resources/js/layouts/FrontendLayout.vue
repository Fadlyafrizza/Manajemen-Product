<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage()

const user = computed(() => page.props.auth?.user)
const hideNavbar = computed(() => {
    return page.url === '/login' || page.url === '/register'
})

defineProps({
    hideNavbar: {
        type: Boolean,
        default: false
    },
    user: {
        type: Object,
        default: null
    }
})

const isMenuOpen = ref(false)
</script>

<template>
    <header v-if="!hideNavbar" class="w-full px-4 sm:px-8 md:px-12 py-2 border-b shadow-md fixed z-50 bg-white">
        <nav class="max-w-7xl mx-auto flex justify-between items-center h-16">
            <h1 class="text-xl font-bold leading-tight">HomePage</h1>

            <template v-if="user">
                <div class="hidden md:flex items-center gap-2">
                    <span class="text-sm">{{ user.name }}</span>
                    <Link href="/logout" method="post" as="button"
                        class="px-2 py-1 bg-zinc-700 hover:bg-zinc-950 font-semibold tracking-tight text-white text-sm rounded-md transition-all">
                    Logout
                    </Link>
                </div>
            </template>
            <template v-else>
                <div class="hidden md:flex space-x-2">
                    <Link href="/login"
                        class="font-semibold text-sm tracking-tight text-zinc-800 rounded border border-zinc-800 focus:bg-zinc-200 focus:text-zinc-600 py-2 px-3 text-center transition-all duration-300 ease-in-out">
                    Login
                    </Link>
                    <Link href="/register"
                        class="font-semibold text-sm tracking-tight rounded bg-zinc-700 text-white py-2 px-3 hover:bg-zinc-950 transition-all duration-300 ease-in-out">
                    Register
                    </Link>
                </div>
            </template>
            <div class=" md:hidden flex items-center space-x-2">
                <span v-if="user" class="text-sm text-zinc-600 px-2">{{ user.name }}</span>

                <button @click="isMenuOpen = !isMenuOpen"
                    class="md:hidden flex flex-col justify-center items-center w-8 h-8 space-y-1 focus:outline-none"
                    :class="{ 'open': isMenuOpen }">

                    <span class="w-6 h-0.5 bg-zinc-800 transition-all duration-300 ease-in-out"
                        :class="{ 'rotate-[45deg] translate-y-1': isMenuOpen }"></span>
                    <span class="w-6 h-0.5 bg-zinc-800 transition-all duration-300 ease-in-out"
                        :class="{ 'opacity-0': isMenuOpen }"></span>
                    <span class="w-6 h-0.5 bg-zinc-800 transition-all duration-300 ease-in-out"
                        :class="{ '-rotate-[45deg] -translate-y-2': isMenuOpen }"></span>
                </button>
            </div>
        </nav>

        <div class="md:hidden overflow-hidden transition-all duration-300 ease-in-out"
            :class="isMenuOpen ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0'">
            <div class="py-4 space-y-3 border-t border-zinc-200">
                <template v-if="user">
                    <div class="flex flex-col space-y-3">
                        <Link href="/logout" method="post" as="button"
                            class="mx-2 px-4 py-2 bg-zinc-700 hover:bg-zinc-950 font-semibold tracking-tight text-white text-sm rounded-md transition-all text-center">
                        Logout
                        </Link>
                    </div>
                </template>
                <template v-else>
                    <div class="flex flex-col space-y-3 px-2">
                        <Link href="/login"
                            class="font-semibold text-sm tracking-tight text-zinc-800 rounded border border-zinc-800 focus:bg-zinc-200 focus:text-zinc-600 py-2 px-3 text-center transition-all duration-300 ease-in-out">
                        Login
                        </Link>
                        <Link href="/register"
                            class="font-semibold text-sm tracking-tight rounded bg-zinc-700 text-white py-2 px-3 hover:bg-zinc-950 transition-all duration-300 ease-in-out text-center">
                        Register
                        </Link>
                    </div>
                </template>
            </div>
        </div>
    </header>
    
    <main class="pt-16">
        <slot />
    </main>
</template>
