<template>
    <div>
        <div v-if="type === 'warning'" class="py-3 px-4 border-l-4 rounded-sm border-yellow-500 bg-yellow-100 text-yellow-700">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "VAlert",
        props: {
            type: String
        }
    }
</script>
