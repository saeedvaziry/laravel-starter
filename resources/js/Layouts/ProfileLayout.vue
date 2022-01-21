<template>
    <app-layout>
        <template #header>
            <slot name="header"></slot>
        </template>
        <slot></slot>
    </app-layout>
</template>

<script>
    import AppLayout from "@/Layouts/AppLayout";

    export default {
        name: "ProfileLayout",
        components: {
            AppLayout
        },
        mounted() {
        }
    }
</script>
