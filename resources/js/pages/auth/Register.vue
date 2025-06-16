<script setup>
import { useForm } from '@inertiajs/vue3'
import TextInput from '../../Components/TextInput.vue'
import NavLink from '../../Components/Navlink.vue'
import { ref } from 'vue'

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
})

const uploadText = ref('Upload Image')

const formRegister = () => {
    form.post('/register', {
        onSuccess: () => form.reset(),
        onError: () => console.log('error')
    })
}
</script>

<template>

    <Head title="Register" />

    <div class="min-h-screen flex">
        <div class="w-full bg-wheat-100 m-3 rounded-s-xl relative">
            <header>
                <nav class="max-w-4xl mx-auto my-8 gap-2 flex items-center relative z-10">
                    <NavLink href="/" :active="$page.url === '/'">Home</NavLink>
                </nav>
                <img loading="lazy" :src="'/image/pattern.jpg'"
                    class="absolute top-0 left-0 w-full h-full object-cover mix-blend-multiply pointer-events-none"
                    alt="">
            </header>
        </div>

        <div class="w-full flex flex-col items-center justify-center">
            <div class="max-w-lg w-full mx-auto p-12">
                <div class="flex flex-col gap-12 mb-12">
                    <div>
                        <h1 class="text-4xl font-bold text-zinc-700 leading-tight">Create
                            <br>Your Account
                            <p class="text-xs font-normal mt-4">Hey, let's get you set up!</p>
                        </h1>
                    </div>
                </div>

                <div>
                    <form @submit.prevent="formRegister" class="space-y-6">
                        <TextInput id="name" type="text" v-model="form.name" :message="form.errors.name" />
                        <TextInput id="email" type="text" v-model="form.email" :message="form.errors.email" />
                        <TextInput id="password" type="password" v-model="form.password"
                            :message="form.errors.password" />
                        <TextInput id="confirm password" type="password" v-model="form.password_confirmation" />

                        <button type="submit" :disabled="form.processing"
                            class="w-full mt-6 p-3 rounded-sm text-soft bg-zinc-700 hover:bg-zinc-600 transition-colors text-base">
                            Register
                        </button>

                        <div class="text-center text-sm">
                            <span>Already Have An Account?
                                <Link href="/login" class="underline hover:no-underline">Login</Link>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
