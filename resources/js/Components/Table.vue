<template>
    <div class="flex flex-col">
        <div class="align-middle inline-block min-w-full overflow-hidden rounded-md shadow">
            <table class="min-w-full">
                <slot></slot>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VTable',
        props: {
            shadow: {
                type: Boolean,
                default: true
            }
        }
    }
</script>

<style>
    table tr:last-child td {
        border-bottom: none !important;
    }
</style>
