<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import TextInput from '../../../../components/TextInput.vue'
import TextArea from '../../../../components/TextArea.vue'
import Message from '../../../../components/Message.vue'

/**
 *
 *  Variables
 *
 */


interface Product {
    id?: number;
    name: string;
    description: string;
    price: string;
    stock: string;
    image?: string;
}

const props = defineProps<{
    show: boolean;
    product?: Product | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success'): void;
}>();

let form = ref({
    name: '',
    price: '',
    stock: '',
    description: '',
});

const showMessage = ref('')
const showToast = ref(false);
const toastMessage = ref('');

const imageFile = ref<File | null>(null);
const imagePreview = ref<string | null>(null);
const errors = ref<Record<string, string>>({});

const isUpdateMode = computed(() => props.product && props.product.id);
const modalTitle = computed(() => isUpdateMode.value ? 'Update Product' : 'Create Product');
const submitButtonText = computed(() => isUpdateMode.value ? 'Update' : 'Simpan');



/**
 *
 *  Services
 *
 */

watch(() => props.product, (newProduct) => {
    if (newProduct) {
        form.value = {
            name: newProduct.name || '',
            price: newProduct.price || '',
            stock: newProduct.stock || '',
            description: newProduct.description || '',
        };

        if (newProduct.image) {
            imagePreview.value = getImageUrl(newProduct.image);
        } else {
            imagePreview.value = null;
        }
        imageFile.value = null;
    } else {
        resetForm();
    }
}, { immediate: true });

watch(() => props.show, (show) => {
    if (!show) {
        resetForm();
    }
});

function showToastMessage(message: string) {
    toastMessage.value = message;
    showToast.value = true;
}

function getImageUrl(image: string) {
    if (image.startsWith('http://') || image.startsWith('https://')) {
        return image;
    }
    return `/storage${image}`;
}

async function submitForm() {
    errors.value = {};
    const formData = new FormData();
    formData.append('name', form.value.name);
    formData.append('description', form.value.description);
    formData.append('price', form.value.price);
    formData.append('stock', form.value.stock);

    if (imageFile.value) {
        formData.append('image', imageFile.value);
    }

    try {
        let response;

        if (isUpdateMode.value) {
            formData.append('_method', 'PUT');
            response = await axios.post(`/api/products/${props.product!.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
        } else {
            response = await axios.post('/api/products', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
        }

        resetForm();

        const successMessage = isUpdateMode.value ? 'Product updated successfully!' : 'Product created successfully!';
        showToastMessage(successMessage);

        setTimeout(() => {
            window.location.reload()

        }, 2000);

        emit('success');


    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.data || error.response.data.errors || {};
        } else {
            console.error('Submit error:', error);
            showToastMessage('An error occurred. Please try again.');
        }
    }
}




/**
 *
 *  Functions
 *
 */

function onImageChange(event: Event) {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
    } else {
        imageFile.value = null;
        imagePreview.value = null;
    }
}

function resetImage() {
    imageFile.value = null;
    if (imagePreview.value && imagePreview.value.startsWith('blob:')) {
        URL.revokeObjectURL(imagePreview.value);
    }
    imagePreview.value = null;

    const fileInput = document.getElementById('dropzone-file') as HTMLInputElement;
    if (fileInput) {
        fileInput.value = '';
    }
}

function resetForm() {
    form.value = {
        name: '',
        price: '',
        stock: '',
        description: '',
    };
    errors.value = {};
    resetImage();
}

function closeModal() {
    resetForm();
    emit('close');
}
</script>

<template>

    <Message :show="showToast" :message="toastMessage" :duration="3000" />

    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40 ">
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <div class="bg-white p-8 rounded shadow-lg w-full max-w-screen-2xl relative">
                <h2 class="text-xl font-semibold mb-4">{{ modalTitle }}</h2>
                <div class="flex flex-col">
                    <div class="mt-4 grid grid-cols-12 space-x-3">
                        <div class="col-span-6">
                            <TextInput required id="Product Name" v-model="form.name" type="text"
                                :class="['bg-transparent', { 'ring-red-500 ring-1 border-none': errors.name }]" />
                            <p v-if="errors.name" class="text-red-500 text-sm mb-2">{{ errors.name[0] || errors.name }}
                            </p>
                        </div>
                        <div class="col-span-4">
                            <TextInput required id="Product Price" v-model="form.price" type="number"
                                :class="['bg-transparent', { 'ring-red-500 ring-1 border-none': errors.price }]" />
                            <p v-if="errors.price" class="text-red-500 text-sm mb-2">{{ errors.price[0] || errors.price
                                }}</p>
                        </div>
                        <div class="col-span-2">
                            <TextInput required id="Product Stock" v-model="form.stock" type="number"
                                :class="['bg-transparent', { 'ring-red-500 ring-1 border-none': errors.stock }]" />
                            <p v-if="errors.stock" class="text-red-500 text-sm mb-2">{{ errors.stock[0] || errors.stock
                                }}</p>
                        </div>
                    </div>
                    <div class="mt-1">
                        <TextArea required id="Product Description" v-model="form.description"
                            :class="['bg-transparent col-span-12', { 'ring-red-500 ring-1 border-none': errors.description }]"
                            placeholder="Enter description" />
                        <p v-if="errors.description" class="text-red-500 text-sm">{{ errors.description[0] ||
                            errors.description }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col items-center justify-center w-full">
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-72 border border-zinc-700 rounded-sm cursor-pointer bg-transparent">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <p class="mb-2 text-sm text-zinc-500">
                                        <span class="font-semibold">Click to upload Image</span> or drag and drop
                                    </p>
                                    <p v-if="isUpdateMode && !imageFile" class="text-xs text-zinc-400">
                                        (Leave empty to keep current image)
                                    </p>
                                </div>
                                <input id="dropzone-file" type="file" accept="image/*" @change="onImageChange"
                                    class="hidden" />
                            </label>
                            <p v-if="errors.image" class="text-red-500 text-sm">{{ errors.image[0] || errors.image }}
                            </p>
                        </div>
                        <div v-if="imagePreview" class="h-72 w-full border rounded overflow-hidden">
                            <img :src="imagePreview" alt="Preview" class="object-cover w-full h-full" />
                        </div>
                        <div v-else
                            class="h-72 w-full border border-zinc-700 flex items-center justify-center rounded overflow-hidden">
                            <span>Image Preview</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="closeModal" class="px-4 py-2 mr-2">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-zinc-700 text-white">
                        {{ submitButtonText }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
