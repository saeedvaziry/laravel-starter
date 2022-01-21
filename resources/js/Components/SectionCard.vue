<template>
    <div class="md:col-span-1 flex justify-between px-4 py-5 bg-white sm:p-6 shadow rounded-md">
        <div>
            <h3 class="text-lg font-medium text-gray-900">
                <slot name="title"></slot>
            </h3>

            <p class="mt-1 text-sm text-gray-600">
                <slot name="description"></slot>
            </p>
        </div>

        <div>
            <slot name="aside"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VSectionCard'
    }
</script>
