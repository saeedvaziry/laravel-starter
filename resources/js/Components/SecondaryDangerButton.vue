<template>
    <button :type="type" class="w-max inline-flex items-center justify-center px-4 py-1 bg-red-50 border border-transparent rounded-md font-semibold text-red-500 capitalize hover:bg-red-100 focus:outline-none focus:ring focus:ring-red-200 active:bg-red-100 disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: 'VSecondaryDangerButton',

        props: {
            type: {
                type: String,
                default: 'button',
            },
        }
    }
</script>
