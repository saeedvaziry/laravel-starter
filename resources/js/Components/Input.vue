<template>
    <input class="border-gray-300 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>

<script>
    export default {
        name: 'VInput',

        props: ['modelValue', 'value'],

        mounted() {
            if (this.value) {
                this.$emit('update:modelValue', this.value)
            }
        },

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

