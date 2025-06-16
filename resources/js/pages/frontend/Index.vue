<script lang="ts" setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

/**
 *
 *  Variables
 *
 */


interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    image: string;
}

const products = ref<Product[]>([]);
const loading = ref(true);
const error = ref('');
const searchQuery = ref('');
const selectedCategory = ref('');
const minPrice = ref('');
const maxPrice = ref('');
const sortBy = ref('name');

const showModal = ref(false);
const selectedProduct = ref<Product | null>(null);


/**
 *
 *  Services
 *
 */


const fetchDatas = async () => {
    try {
        loading.value = true;
        error.value = '';
        const response = await axios.get('/api/products');
        products.value = response.data;
    } catch (err) {
        error.value = 'Failed to load products. Please try again.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};






/**
 *
 *  Functions
 *
 */

const filteredProducts = computed(() => {
    let filtered = products.value;

    if (searchQuery.value) {
        filtered = filtered.filter(product =>
            (product.name ?? '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (product.description ?? '').toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    filtered.sort((a, b) => {
        switch (sortBy.value) {
            case 'name':
                return a.name.localeCompare(b.name);
            case 'price-low':
                return a.price - b.price;
            case 'price-high':
                return b.price - a.price;
            default:
                return 0;
        }
    });

    return filtered;
});

const clearFilters = () => {
    searchQuery.value = '';
    selectedCategory.value = '';
    minPrice.value = '';
    maxPrice.value = '';
    sortBy.value = 'name';
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(price);
};

const formatText = (text: string) => {
    return text.substring(0, 50) + '...';
};

const openModal = (product: Product) => {
    selectedProduct.value = product;
    showModal.value = true;
    document.body.style.overflow = 'hidden';
};

const closeModal = () => {
    showModal.value = false;
    selectedProduct.value = null;
    document.body.style.overflow = '';
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && showModal.value) {
        closeModal();
    }
};




onMounted(() => {
    fetchDatas();
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
});
</script>

<template>

    <Head title="Homepage" />

    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="border-b border-zinc-700 p-6 mb-8">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                        <div class="relative">
                            <input v-model="searchQuery" type="text" placeholder="Search by name or description..."
                                class="w-full px-4 py-2 focus:outline-none border border-zinc-700 rounded-sm pl-10" />

                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-zinc-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="min-w-full sm:min-w-full md:min-w-40 lg:min-w-40 xl:min-w-40">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select v-model="sortBy"
                            class="w-full px-4 focus:outline-none py-2 border border-zinc-700 rounded-sm">
                            <option value="name">Name (A-Z)</option>
                            <option value="price-low">Price (Low to High)</option>
                            <option value="price-high">Price (High to Low)</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-full md:w-full lg:w-32 xl:w-32">
                        <button @click="clearFilters"
                            class="w-full px-4 py-2 text-soft bg-zinc-700 rounded-sm hover:bg-armadillo-900 border border-zinc-700 transition-colors duration-200">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-teal-600"></div>
                <span class="ml-3 text-gray-600 font-medium">Loading products...</span>
            </div>

            <div v-else-if="filteredProducts.length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div v-for="product in filteredProducts" :key="product.id"
                        class="bg-white rounded-sm shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-200">
                        <div class="relative overflow-hidden">
                            <img :src="product.image" :alt="product.name"
                                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                loading="lazy" />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <div class="absolute top-4 right-4">
                                <span v-if="product.stock > 0"
                                    class="inline-flex items-center px-3 py-1 rounded-sm text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                    {{ product.stock }} in stock
                                </span>
                                <span v-else
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                    Sold out
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ product.name }}
                            </h3>
                            <p class="text-gray-500 text-sm mb-3 line-clamp-3">
                                {{ formatText(product.description) }}
                            </p>

                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-teal-600">
                                    {{ formatPrice(product.price) }}
                                </span>
                                <button @click="openModal(product)"
                                    class="px-4 py-2 bg-teal-600 text-white text-sm rounded-sm hover:bg-teal-700 transition-all duration-200 focus:ring-2 focus:ring-teal-500 focus:outline-none">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-36">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-500 mb-4">Try adjusting your filters or search terms</p>
                <button @click="clearFilters"
                    class="px-4 py-2 bg-zinc-700 text-white rounded-sm hover:bg-zinc-900 transition-all duration-200 focus:ring-2 focus:ring-gray-500 focus:outline-none">
                    Clear All Filters
                </button>
            </div>
        </div>
    </div>

    <Transition name="modal" appear>
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity duration-300" @click="closeModal"></div>

            <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                <div
                    class="relative bg-white rounded-sm shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300">
                    <button @click="closeModal"
                        class="absolute top-4 right-4 z-10 p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-all duration-200">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <div v-if="selectedProduct" class="p-0">
                        <div class="relative">
                            <img :src="selectedProduct.image" :alt="selectedProduct.name"
                                class="w-full h-64 sm:h-80 object-cover rounded-t-xl">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        </div>

                        <div class="p-6 sm:p-8">
                            <div class="mb-4">
                                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">{{ selectedProduct.name }}
                                </h2>
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4">
                                    <span class="text-2xl sm:text-3xl font-bold text-teal-600">
                                        {{ formatPrice(selectedProduct.price) }}
                                    </span>
                                    <div class="flex items-center space-x-3">
                                        <span class="font-medium text-gray-900">{{ selectedProduct.stock }}</span>
                                        <div v-if="selectedProduct.stock > 0" class="flex items-center">
                                            <p class="text-sm font-medium text-green-600">In Stock</p>
                                            <div class="bg-green-500 size-2 rounded-full ml-1"></div>
                                        </div>
                                        <div class="flex items-center" v-else>
                                            <p class="text-sm font-medium text-red-600">Out of Stock</p>
                                            <div class="bg-red-500 size-2 rounded-full ml-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 border-t border-zinc-700"></h3>
                                <p class="text-gray-600 leading-relaxed">{{ selectedProduct.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
