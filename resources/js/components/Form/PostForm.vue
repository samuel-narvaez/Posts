<script setup>
import { reactive, ref, onMounted } from 'vue'

const initialState = {
    title: '',
    content: '',
    image: '',
    author_id: ''
}

const form = reactive(initialState)

const authors = ref([])
const imageUrl = ref('')
const isLoading = ref(false)

const loadAuthor = async () => {
    try {
        const response = await axios.get('/api/authors', form)
        authors.value = response.data.authors
        console.log(response);
    } catch (error) {
        console.error('Error:', error);

    }

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
      form.image = reader.result
    }
    
    await reader.readAsDataURL(file)
  } catch (error) {
    toast.fire({ icon: 'error', title: error.message })
  } finally {
    isLoading.value = false
  }
}

const resetForm = () => {
  Object.keys(form).forEach(key => {
    form[key] = '';
  });
};

const resetImage = () => {
  if (imageUrl.value) {
    URL.revokeObjectURL(imageUrl.value);
    imageUrl.value = '';
    form.image = '';
  }
};

const createPost = async () => {
    try {
        const response = await axios.post('/api/posts', form)
        if (response.status != 201) {
            throw new Error("Ocurrio un error creando el post");
        }
        resetForm();
        resetImage();
    } catch (error) {
        console.error('Error:', error);
        toast.fire({ icon: 'error', title: error.message })
    }finally {
        toast.fire({ icon: 'success', title: 'Post creado correctamente' })
    }
}

onMounted(() => {
    loadAuthor()
})

</script>

<template>
    <div>
        <h3 class="text-lg font-bold mb-4">Crear Post</h3>
        <input type="text" v-model="form.title" class="w-full p-2 mb-4 border rounded" placeholder="Titulo" required />

        <input type="text" v-model="form.content" class="w-full p-2 mb-4 border rounded" placeholder="Descripcion"
            required />

        <select v-model="form.author_id" class="block appearance-none w-full mb-4 border py-3 px-4 pr-8 rounded">
            <option v-for="author in authors" :key="author" :value="author.id">
                {{ `${author.name} ${author.last_name}` }}
            </option>
        </select>

        <label
            class="h-[150px] w-full flex flex-col mb-3 items-center justify-center gap-5 cursor-pointer border-2 border-dashed border-gray-300 bg-white p-6 rounded-xl shadow-xl"
            for="file">
            <div class="flex items-center justify-center">
                <span>Click to upload image</span>
            </div>
            <img v-if="imageUrl" :src="imageUrl" @load="handleImageLoad" style="max-width: 50%; max-height: 150px;" />
            <input @change="handleFileChange" class="hidden" type="file" id="file" accept="image/*" />
        </label>

        <button @click="createPost" class="w-full p-2 bg-blue-400 text-white rounded-lg focus:outline-none">
            Crear Post
        </button>
    </div>
</template>
