<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import NavLink from '../components/Navlink.vue'

const page = usePage()
const user = computed(() => page.props.auth?.user)

const showDropdown = ref(false)
const showNestedDropdown = ref(false)

function handleClickOutside(event: MouseEvent) {
    const target = event.target as HTMLElement
    if (!target.closest('#dropdownNavbarLink') && !target.closest('#dropdownNavbar')) {
        showDropdown.value = false
    }
    if (!target.closest('#doubleDropdownButton') && !target.closest('#doubleDropdown')) {
        showNestedDropdown.value = false
    }
}

onMounted(() => {
    window.addEventListener('click', handleClickOutside)
})

</script>

<template>

    <nav class="bg-white border-gray-200  shadow-md border-b">
        <div class="max-w-screen-2xl flex flex-wrap items-center justify-between mx-auto py-6">
            <a href="#" class="flex items-center">
                <span class="self-center text-2xl font-semibold whitespace-nowrap">Dashboard</span>
            </a>
            <div class="hidden w-full md:block md:w-auto" id="navbar-multi-level">
                <ul
                    class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                    <li>
                        <NavLink href="/dashboard" :active="$page.url === '/dashboard'">Home</NavLink>
                    </li>
                    <li>
                        <NavLink href="/dashboard/product" :active="$page.url === '/dashboard/product'">Product
                        </NavLink>
                    </li>
                    <li>
                        <button @click="showDropdown = !showDropdown" id="dropdownNavbarLink"
                            class="flex items-center justify-between w-full py-2 px-3 text-gray-900 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-black md:p-0 md:w-auto">
                            {{ user.name ?? 'Admin' }}
                            <svg class="w-2.5 h-2.5 ms-2.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <div id="dropdownNavbar" v-show="showDropdown"
                            class="absolute mt-2 z-10 font-normal bg-white divide-y divide-zinc-700 rounded-sm shadow-lg border w-44">
                            <div class="py-1">
                                <Link href="/logout" method="post" as="button"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:text-black hover:underline">Logout</Link>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="max-w-screen-2xl mx-auto p-4">
        <slot />
    </main>
</template>
