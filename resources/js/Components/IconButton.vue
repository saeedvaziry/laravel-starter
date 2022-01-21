<template>
    <button :type="type" class="inline-flex items-center p-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 capitalize shadow-sm hover:text-gray-500 focus:outline-none focus:border-primary-300 focus:ring focus:ring-primary-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: 'VIconButton',

        props: {
            type: {
                type: String,
                default: 'button',
            },
        }
    }
</script>
