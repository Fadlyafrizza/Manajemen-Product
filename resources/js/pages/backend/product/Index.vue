<script lang="ts" setup>

import { onMounted, ref } from 'vue'
import { nextTick } from 'vue';
import PlusIcon from '../../../components/icons/PlusIcon.vue'
import MoreIcon from '../../../components/icons/MoreIcon.vue'
import CreateUpdateModal from './modal/ProductModal.vue'

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    image: string;
    stock: number;
    created_at: string;
    updated_at: string;
}

const showModal = ref(false);
const selectedProduct = ref<Product | null>(null);

const products = ref<Product[]>([])

const createButtonRef = ref<HTMLElement | null>(null)

/**
 *
 * Services
 *
 */

const fetchProducts = async () => {
    if ($.fn.DataTable.isDataTable('#product-table')) {
        $('#product-table').DataTable().destroy();
    }

    const response = await fetch('/api/products');
    const data = await response.json();
    products.value = data;
    await nextTick();

    $('#product-table').DataTable({
        columnDefs: [
            {
                targets: [1, 2, 3],
                orderable: false
            }
        ]
    });
};


const deleteProduct = async (productId: number) => {
    if (!confirm("You sure you wan't to delete this product?")) return

    try {
        const response = await axios.delete(`/api/products/${productId}`)
        alert('Success to delete product!')
        await fetchProducts();
    } catch (error) {
        console.error('Delete error:', error)
        alert('Error')
    }
}




/**
 *
 * Functions
 *
 */



function openCreateModal() {
    selectedProduct.value = null;
    showModal.value = true;
}

function openUpdateModal(product: Product) {
    selectedProduct.value = { ...product, price: product.price.toString(), stock: product.stock.toString() };
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    selectedProduct.value = null;
}

function handleModalSuccess() {
    fetchProducts();
}

function refreshCreateButton() {
    if (createButtonRef.value) {
        createButtonRef.value.classList.add('ring-2', 'ring-blue-500')
        setTimeout(() => {
            createButtonRef.value?.classList.remove('ring-2', 'ring-blue-500')
        }, 500)
    }
}

function getImageUrl(image: string) {
    if (image.startsWith('http://') || image.startsWith('https://')) {
        return image;
    }
    return `/storage${image}`;
}






onMounted(() => {
    fetchProducts()
})

</script>

<template>
    <CreateUpdateModal :show="showModal" :product="selectedProduct" @close="closeModal" @success="handleModalSuccess" />

    <div class="my-8 bg-white p-12 rounded-md shadow-sm border">
        <div class="flex justify-end mb-4">
            <button @click="openCreateModal"
                class="flex items-center gap-2 px-4 py-2 bg-zinc-700 text-white rounded shadow hover:bg-zinc-800">
                <PlusIcon class="size-5 fill-white" />
                Create Product
            </button>
        </div>

        <table id="product-table" class="display">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Aksi</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(product, index) in products" :key="product.id">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <div class="relative group">
                            <MoreIcon :product-id="product.id" class="cursor-pointer bg-zinc-700 fill-white size-8" />
                            <span
                                class="absolute hidden group-hover:block bg-white text-black text-xs rounded py-1 px-2 shadow-lg top-full left-1/2 transform -translate-x-1/2 z-30">
                                <span @click="openUpdateModal(product)"
                                    class="block pr-6 pl-2 py-2 hover:underline cursor-pointer">Edit</span>
                                <span @click="deleteProduct(product.id)"
                                    class="block pr-6 pl-2 py-2 hover:underline cursor-pointer">Hapus</span>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center space-x-3">
                            <img v-if="product.image" :src="getImageUrl(product.image)" :alt="product.name"
                                class="w-12 h-12 object-cover rounded" />
                            <span>{{ product.name }}</span>
                        </div>
                    </td>
                    <td>{{ product.description }}</td>
                    <td>{{ product.price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }) }}</td>
                    <td>{{ product.stock }} pcs.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>


<style scoped>
.middle {
    text-align: center;
    vertical-align: middle;
}

.icon-center {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}
</style>
