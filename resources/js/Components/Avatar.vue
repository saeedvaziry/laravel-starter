<template>
    <div class="inline-flex items-center justify-start">
        <img class="w-10 h-10 rounded-full object-cover border-2 border-primary-300" :src="user.profile_photo_url" :alt="user.name">

        <div class="ml-2 leading-tight hidden lg:block" v-if="withName || withEmail">
            <div v-if="withName">{{ user.name }}</div>
            <div v-if="withEmail" class="text-gray-700 text-sm">{{ user.email }}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "VAvatar",
        props: {
            user: Object,
            withName: {
                type: Boolean,
                default: true
            },
            withEmail: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
