<template>
    <select class="border-gray-300 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="select">
        <slot></slot>
    </select>
</template>

<script>
    export default {
        name: 'VSelect',

        props: ['modelValue'],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

