<script setup>
import { reactive } from 'vue'

const initialState = {
    name: '',
    last_name: ''
}

const form = reactive(initialState)

const resetForm = () => {
  Object.keys(form).forEach(key => {
    form[key] = '';
  });
};

const createAuthor = async () => {
    try {
        const response = await axios.post('/api/authors', form)
        if (response.status != 201) {
            throw new Error("Ocurrio un Error al Tratar de crear el Autor");
        }
    } catch (error) {
        console.error('Error:', error);
        toast.fire({ icon: 'error', title: error.message })
    } finally {
        resetForm();
        toast.fire({ icon: 'success', title: 'Autor creado correctamente' })
    }
}
</script>

<template>
    <div>
        <h3 class="text-lg font-bold mb-4">Crear Autor</h3>
        <input type="text" v-model="form.name" class="w-full p-2 mb-4 border rounded" placeholder="Nombres" required />
        <input type="text" v-model="form.last_name" class="w-full p-2 mb-4 border rounded" placeholder="Apellidos"
            required />
        <button @click="createAuthor" class="w-full p-2 bg-blue-400 text-white rounded-lg focus:outline-none">
            Crear Autor
        </button>
    </div>
</template>