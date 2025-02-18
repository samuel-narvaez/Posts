<script setup>
import { ref, defineEmits  } from 'vue'

import AuthorsForm from './Form/AuthorsForm.vue'
import PostForm from './Form/PostForm.vue'

const isMenuOpen = ref(false)
const isAuthorModalOpen = ref(false)
const isPostModalOpen = ref(false)
const emit = defineEmits(['child-clicked']);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const openModal = (type) => {
  if (type === 'author') {
    isAuthorModalOpen.value = true
  } else if (type === 'post') {
    isPostModalOpen.value = true
  }
  isMenuOpen.value = false
}

const closeModals = async() => {
  isAuthorModalOpen.value = false
  isPostModalOpen.value = false
  emit('child-clicked');
}

</script>
<template>
  <div>
    <!-- Botón flotante -->
    <button @click="toggleMenu"
      class="fixed bottom-6 right-6 p-4 group cursor-pointer outline-none hover:rotate-90 duration-300">
      <i class="fa-solid fa-circle-plus fa-2xl" style="color: #74C0FC;"></i>
    </button>

    <!-- Menú flotante -->
    <div v-if="isMenuOpen" class="fixed bottom-20 right-14 bg-white shadow-lg rounded-lg p-2 space-y-2">
      <button @click="openModal('author')" title="Crear Autor" class="w-full p-2 rounded-lg">
        <i class="fa-solid fa-user-plus fa-lg"></i>
      </button>
      <button @click="openModal('post')" title="Crear Post" class="w-full p-2 rounded-lg">
        <i class="fa-solid fa-clipboard-list fa-lg"></i>
      </button>
    </div>

    <!-- Modal de Crear Autor -->
    <div v-if="isAuthorModalOpen" @click="closeModals"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div @click.stop class="bg-white p-6 rounded-lg shadow-lg w-96">
        <AuthorsForm />
        <button @click="closeModals"
          class="w-full p-2 mt-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none">
          Cancelar
        </button>
      </div>
    </div>

    <!-- Modal de Crear Post -->
    <div v-if="isPostModalOpen" @click="closeModals"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div @click.stop class="bg-white p-6 rounded-lg shadow-lg w-96">
        <PostForm />
        <button @click="closeModals"
          class="w-full p-2 mt-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none">
          Cancelar
        </button>
      </div>
    </div>
  </div>
</template>