<template>
    <div class="inline-flex">
        <span v-if="status === 'success'" class="text-xs rounded-full bg-green-50 text-green-500 px-2 py-1 uppercase">
            <slot></slot>
        </span>
        <span v-if="status === 'error'" class="text-xs rounded-full bg-red-50 text-red-500 px-2 py-1 uppercase">
            <slot></slot>
        </span>
        <span v-if="status === 'warning'" class="text-xs rounded-full bg-yellow-50 text-yellow-500 px-2 py-1 uppercase">
            <slot></slot>
        </span>
        <span v-if="status === 'disabled'" class="text-xs rounded-full bg-gray-50 text-gray-500 px-2 py-1 uppercase">
            <slot></slot>
        </span>
        <span v-if="status === 'info'" class="text-xs rounded-full bg-primary-50 text-primary-500 px-2 py-1 uppercase">
            <slot></slot>
        </span>
    </div>
</template>

<script>
    export default {
        name: 'VStatus',
        props: {
            status: String,
            label: String
        }
    }
</script>
