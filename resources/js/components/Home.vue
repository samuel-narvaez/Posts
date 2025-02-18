<script setup>
import WaveTop from './WaveTop.vue'
</script>


<template>
    <WaveTop />
    <main>
        <div class="flex items-center justify-center h-screen">
            <div class="grid grid-cols-2 w-[700px] gap-2 max-[500px]:grid-cols-1 px-3">
                <router-link 
                    to="/configuration"
                    class="group w-full rounded-lg bg-[#a684db] p-20 transition relative duration-300 cursor-pointer hover:translate-y-[3px] hover:shadow-[0_-8px_0px_0px_#2196f3]">
                    <p class="text-white text-2xl">Configuration</p>
                    <span
                        class="group-hover:opacity-100 absolute right-[10%] top-[50%] translate-y-[-50%] opacity-20 transition group-hover:scale-110 duration-300">
                        <i class="fa-solid fa-user-gear fa-xl" style="color: #ffffff;"></i>
                    </span>
                </router-link>
                <router-link 
                    to="/post"
                    class="group w-full rounded-lg bg-[rgb(228,103,103)] p-20 transition relative duration-300 cursor-pointer hover:translate-y-[3px] hover:shadow-[0_-8px_0px_0px_rgb(244,67,54)]">
                    <p class="text-white text-2xl">Post</p>
                    <span
                        class="group-hover:opacity-100 absolute right-[10%] top-[50%] translate-y-[-50%] opacity-20 transition group-hover:scale-110 duration-300">
                        <i class="fa-regular fa-clipboard fa-xl" style="color: #ffffff;"></i>
                    </span>
                </router-link>
            </div>
        </div>
    </main>
</template>