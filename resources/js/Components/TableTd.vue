<template>
    <td :class="classes">
        <slot></slot>
    </td>
</template>

<script>
    export default {
        name: 'VTableTd',
        props: {
            classes: {
                type: String,
                default: 'px-6 py-4 border-t border-gray-200'
            }
        }
    }
</script>
