<template>
    <div class="bg-white border-b border-gray-200 p-5 w-full">
        <slot name="header"></slot>
    </div>
    <slot></slot>
</template>

<script>
    export default {
        name: "AppLayout"
    }
</script>
