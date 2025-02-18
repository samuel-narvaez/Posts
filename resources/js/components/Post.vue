<script setup>
import ShowMore from './icons/showMore.vue'
import PostEditForm from './Form/PostEditForm.vue'

import { ref, onMounted, computed } from 'vue'

const posts = ref([])
const filterText = ref('')

const loadPost = async () => {
    try {
        const response = await axios.get('/api/posts')
        posts.value = response.data.posts
    } catch (error) {
        console.error('Error:', error);
    }
}

const renderImage = (img) => {
    return "/upload/" + img
}

const detailsPost = (post) => {
    const { title, content, author } = post;
    console.log(author);
    Swal.fire({
        title: title,
        footer: `Autor <p>${author.name} ${author.last_name}</p>`,
        html: `
            <img src="${renderImage(post.image)}" class="w-full h-auto object-cover mb-4">
            <p class="text-justify">${content}</p>
        `,
        showCloseButton: true,
        showConfirmButton: false,
    });
}

onMounted(() => {
    loadPost()
})

const filteredPosts = computed(() => {
    if (!filterText.value) return posts.value

    return posts.value.filter(post =>
        post.title.toLowerCase().includes(filterText.value.toLowerCase())
    )
})

</script>

<template>
    <div>
        <h1
            class="text-center mt-10 mb-6 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent text-3xl font-black tracking-tight">
            Post
        </h1>

        <div class="flex justify-end px-10">
            <div
                class="flex items-center border w-80 transition duration-300 pr-3 gap-2 bg-white border-gray-500/30 h-[46px] rounded-[5px] overflow-hidden">
                <input type="text" v-model="filterText" placeholder="Search for posts"
                    class="w-full h-full pl-4 outline-none placeholder-gray-500 text-sm" />
                <i class="fa-solid fa-magnifying-glass fa-lg"></i>
            </div>
        </div>

        <div class="flex items-center justify-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-7xl mx-auto px-10 py-8">
                <div v-for="post in filteredPosts" :key="post"
                    class="max-w-sm rounded overflow-hidden shadow-xl mb-10 px-10">

                    <div class="px-2 py-4">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                {{ post.title }}
                            </div>
                            <button type="button" @click="detailsPost(post)" title="Ver mas" class="ml-2">
                                <ShowMore />
                            </button>
                        </div>
                    </div>
                    <img class="w-full py-1" :src="renderImage(post.image)" :alt="post.image">
                </div>
            </div>

            <div v-if="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div @click.stop class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <PostEditForm @child-clicked="loadPost" :edit-form="EditForm" />
                    <button @click="closeEditModal"
                        class="w-full p-2 mt-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>