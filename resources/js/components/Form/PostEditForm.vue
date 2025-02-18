<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  editForm: {
    type: Object,
    required: true,
  },
})

const authorFullName = computed(() => {
  if (props.editForm.author && props.editForm.author.name && props.editForm.author.last_name) {
    return `${props.editForm.author.name} ${props.editForm.author.last_name}`;
  }
  return '';
});

const authors = ref([])
const editAuth = ref(false)
const editImage = ref(false)
const imageUrl = ref('')
const isLoading = ref(false)

const loadAuthor = async () => {
  try {
    const response = await axios.get('/api/authors')
    authors.value = response.data.authors
  } catch (error) {
    console.error('Error:', error);
  } finally {
    editAuth.value = true;
  }

}

const renderImage = (img) => {
  return "/upload/" + img
}

const handleFileChange = async (event) => {
  const file = event.target.files[0]
  
  if (!file) return
  
  isLoading.value = true
  imageUrl.value = ''
  
  try {
    const reader = new FileReader()
    
    reader.onload = () => {
      imageUrl.value = reader.result
      props.editForm.image = reader.result
    }
    
    await reader.readAsDataURL(file)
  } catch (error) {
    toast.fire({ icon: 'error', title: error.message })
  } finally {
    isLoading.value = false
  }
}



const resetImage = () => {
  if (imageUrl.value) {
    URL.revokeObjectURL(imageUrl.value);
    imageUrl.value = '';
  }
};

const handleSubmit = async () => {
  const id = props.editForm.id;

  const values = {
    title: props.editForm.title,
    content: props.editForm.content,
    author_id: props.editForm.author.id,
    image: imageUrl.value,
  }
  
  try {
        const response = await axios.put(`/api/posts/${id}`, values);
        if (response.status != 201) {
            throw new Error("Ocurrio un error creando el post");
        }
        resetImage();
    } catch (error) {
        console.error('Error:', error);
        toast.fire({ icon: 'error', title: error.message })
    }finally {
        toast.fire({ icon: 'info', title: 'Post Actualizado correctamente' })
    }
}

</script>

<template>
  <div>
    <h3 class="text-lg font-bold mb-4">Update Post</h3>

    <form @submit.prevent="handleSubmit">
      <input type="text" v-model="editForm.title" class="w-full p-2 mb-4 border rounded" placeholder="Titulo"
        required />
      <input type="text" v-model="editForm.content" class="w-full p-2 mb-4 border rounded" placeholder="Descripcion"
        required />

      <div v-if="!editAuth" class="flex shadow-sm">
        <input :value="authorFullName" class="w-full py-1 indent-2 rounded-s-lg focus:outline-none" disabled />
        <button type="button" @click="loadAuthor" class="py-1 text-white bg-blue-600 flex justify-center items-center w-10 h-10">
          <i class="fa-solid fa-pen fa-sm pointer-events-none"></i>
        </button>
      </div>

      <select v-if="editAuth" v-model="editForm.author_id"
        class="block appearance-none w-full mb-4 border py-3 px-4 pr-8 rounded">
        <option v-for="author in authors" :key="author" :value="author.id">
          {{ `${author.name} ${author.last_name}` }}
        </option>
      </select>

      <div v-if="!editImage" class="relative group">
        <img class="w-full" :src="renderImage(editForm.image)" :alt="editForm.image">
        <button
          class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-800 bg-opacity-70 text-white p-4 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"
          @click="editImage=true">
          <i class="fa-solid fa-pen fa-sm"></i>
        </button>
      </div>

      <label v-if="editImage"
            class="h-[150px] w-full flex flex-col mb-3 items-center justify-center gap-5 cursor-pointer border-2 border-dashed border-gray-300 bg-white p-6 rounded-xl shadow-xl"
            for="file">
            <div class="flex items-center justify-center">
                <span>Click to upload image</span>
            </div>
            <img v-if="imageUrl" :src="imageUrl" @load="handleImageLoad" style="max-width: 50%; max-height: 150px;" />
            <input @change="handleFileChange" class="hidden" type="file" id="file" accept="image/*" />
        </label>
        
      <button type="submit" class="w-full p-2 bg-blue-400 text-white rounded-lg focus:outline-none">
          Actualizar Post
      </button>
    </form>
  </div>
</template>
