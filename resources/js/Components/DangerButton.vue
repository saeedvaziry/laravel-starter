<template>
    <button :type="type" class="w-max inline-flex items-center justify-center px-4 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-white capitalize hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: 'VDangerButton',

        props: {
            type: {
                type: String,
                default: 'button',
            },
        }
    }
</script>
