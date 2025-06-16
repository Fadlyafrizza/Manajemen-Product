<script lang="ts" setup>
import { ref, computed } from 'vue';
import EyeOffIcon from '../components/icons/EyeOffIcon.vue';
import EyeOnIcon from '../components/icons/EyeOnIcon.vue';

const model = defineModel({ required: true });

const props = defineProps({
    name: String,
    type: { type: String, default: 'text' },
    message: String,
    placeholder: { type: String, default: '' },
    id: String,
    showToggle: { type: Boolean, default: true }
});

const showPassword = ref(false);

const inputType = computed(() =>
    (props.type === 'password' || props.type === 'confirmation_password')
        ? (showPassword.value ? 'text' : 'password')
        : props.type
);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <div class="mb-3 relative">
        <label v-if="name">{{ name }}</label>
        <div class="relative">
            <input
                v-bind="$attrs"
                :id="id"
                :type="inputType"
                v-model="model"
                :placeholder="placeholder"
                :class="['input-form peer', { 'ring-red-500 ring-1 border-none': message }]"
            />
            <button
                v-if="showToggle && (type === 'password' || type === 'confirmation_password')"
                type="button"
                class="absolute inset-y-0 end-3 flex items-center text-gray-500"
                @click="togglePassword"
                tabindex="-1"
            >
                <slot name="toggle-icon">
                    <span v-if="showPassword"><EyeOffIcon /></span>
                    <span v-else><EyeOnIcon /></span>
                </slot>
            </button>
            <label
                v-if="id"
                :for="id"
                class="absolute text-sm rounded-full text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-zinc-50 px-2 peer-focus:px-2 peer-focus:text-zinc-800 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-1"
            >
                <slot name="floating-label">{{ id }}</slot>
            </label>
        </div>
        <p class="text-error absolute pt-1" v-if="message">{{ message }}</p>
    </div>
</template>
