<template>
    <div class="max-w-2xl mx-auto mb-10">
        <v-section-title class="mb-5">
            <template #title>
                <slot name="title"></slot>
            </template>
            <template #description>
                <slot name="description"></slot>
            </template>
        </v-section-title>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
                <slot name="content"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import VSectionTitle from './SectionTitle'

    export default {
        name: 'VActionSection',
        components: {
            VSectionTitle,
        }
    }
</script>
