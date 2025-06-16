<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3'
import TextInput from '../../Components/TextInput.vue'
import NavLink from '../../Components/Navlink.vue'

const form = useForm({
    email: null,
    password: null,
    remember: null
})

const formLogin = () => {
    form.post('/login', {
        onError: () => form.reset('password')
    })
}

</script>
<template>

    <Head title="Login" />

    <div class="min-h-screen flex flex-col md:flex-row">
        <div class="lg:block w-full md:w-1/2 bg-zinc-100 m-3 rounded-xl md:rounded-s-xl relative min-h-[200px]">
            <header>
                <nav class="max-w-4xl mx-auto my-8 gap-2 flex items-center relative z-10">
                    <NavLink href="/" :active="$page.url === '/'">Home</NavLink>
                </nav>
                <img loading="lazy" :src="'/image/pattern.jpg'"
                    class="absolute top-0 left-0 w-full h-full object-cover mix-blend-multiply pointer-events-none"
                    alt="">
            </header>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center">
            <div class="max-w-lg w-full mx-auto p-6 md:p-12">
                <div class="flex flex-col gap-8 md:gap-12 mb-8 md:mb-12">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-zinc-700 leading-tight">Hello,
                            <br>Login To Your Account
                            <p class="text-xs font-normal mt-4">Hey, Wellcome back to our website!</p>
                        </h1>
                    </div>
                </div>

                <div>
                    <form @submit.prevent="formLogin" class="space-y-6">
                        <TextInput id="email" type="text" v-model="form.email" :message="form.errors.email"
                            class="bg-transparent" />
                        <TextInput id="password" type="password" v-model="form.password" :message="form.errors.password"
                            class="bg-transparent" />
                        <div class="flex flex-col md:flex-row items-center justify-between text-sm pt-2 gap-2">
                            <div class="flex items-center gap-2">
                                <label for="remember">Remember Me</label>
                                <input type="checkbox" name="remember" id="remember" v-model="form.remember"
                                    class="accent-zinc-800">
                            </div>
                            <div>
                                <Link href="/register" class="underline hover:no-underline">Forgot Password?</Link>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-2 mb-4 p-2 rounded-sm text-soft bg-zinc-700 ">login</button>
                        <div class="text-center text-sm mb-4">
                            <span>Don't Have An Account?
                                <Link href="/register" class="underline hover:no-underline">Register</Link>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
