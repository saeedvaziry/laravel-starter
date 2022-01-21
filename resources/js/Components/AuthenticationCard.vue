<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 p-6 overflow-hidden rounded-md bg-white shadow">
            <slot />
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VAuthenticationCard'
    }
</script>
