<template>
    <button :type="type" :class="{'w-full': full, 'w-max': !full}" class="inline-flex items-center justify-center px-4 py-1 bg-primary-600 border border-transparent rounded-md font-semibold capitalize text-white hover:bg-primary-700 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-200 disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        name: 'VButton',
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            full: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
