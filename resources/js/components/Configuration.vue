<script setup>
import FloatButton from './FloatButton.vue'
import Trash from './icons/trash.vue'
import Edit from './icons/Edit.vue'
import ShowMore from './icons/showMore.vue'
import PostEditForm from './Form/PostEditForm.vue'

import { ref, onMounted, defineExpose, reactive, computed } from 'vue'

const posts = ref([])
const filterText = ref('')
const editModal = ref(false)

const EditForm = reactive({
    id: '',
    title: '',
    content: '',
    image: '',
    author: ''
})


const loadPost = async () => {
    try {
        const response = await axios.get('/api/posts')
        posts.value = response.data.posts
    } catch (error) {
        console.error('Error:', error);
    }
}

const deletePost = (id) => {
    Swal.fire({
        title: "Esta seguro?",
        text: "No podras revertir esta accion!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/posts/${id}`)
            } catch (error) {
                console.error('Error:', error);
            } finally {
                Swal.fire({
                    title: "Eliminado!",
                    text: "El post ha sido eliminado correctamente.",
                    icon: "success"
                });
                loadPost()
            }
        }
    });
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

const openEditModal = (post) => {
    editModal.value = true
    const { id, title, content, image, author} = post;
    EditForm.id = id
    editModal.value = true
    EditForm.title = title
    EditForm.content = content
    EditForm.image = image
    EditForm.author = author
}

const closeEditModal = () => {
    editModal.value = false
    EditForm.id = ''
    EditForm.title = ''
    EditForm.content = ''
    EditForm.image = ''
    EditForm.author = ''
    loadPost()
}

defineExpose({ loadPost });

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
            Administrador
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
                <div v-for="post in filteredPosts" :key="post" class="max-w-sm rounded overflow-hidden shadow-xl mb-10 px-10">
                    <img class="w-full" :src="renderImage(post.image)" :alt="post.image">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">{{ post.title }}</div>
                        <p class="text-gray-700 text-base">
                            {{ post.content }}
                        </p>
                    </div>
                    <div class="pt-4 pb-2">
                        <div class="flex justify-end">
                            <button type="button" @click="deletePost(post.id)" title="Eliminar Post">
                                <Trash />
                            </button>
                            <button type="button" @click="openEditModal(post)" title="Editar Post">
                                <Edit />
                            </button>
                            <button type="button" @click="detailsPost(post)" title="Ver mas">
                                <ShowMore />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <FloatButton @child-clicked="loadPost" />

            <div v-if="editModal"  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div @click.stop class="bg-white p-6 rounded-lg shadow-lg w-96">
                    <PostEditForm  
                        @child-clicked="loadPost"
                        :edit-form="EditForm"
                    />
                    <button @click="closeEditModal"
                        class="w-full p-2 mt-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>