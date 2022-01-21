<template>
    <div class="max-w-2xl mx-auto mb-10">
        <v-section-title class="mb-5" v-if="$slots.title || $slots.description">
            <template #title>
                <slot name="title"></slot>
            </template>
            <template #description>
                <slot name="description"></slot>
            </template>
        </v-section-title>

        <div class="mt-5 md:mt-0" :class="{'md:col-span-2': $slots.title || $slots.description, 'md:col-span-3': !$slots.title && !$slots.description}">
            <form @submit.prevent="$emit('submitted')">
                <div class="px-4 py-5 bg-white sm:p-6 shadow"
                     :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'">
                    <div class="grid grid-cols-6 gap-6">
                        <slot name="form"></slot>
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md" v-if="hasActions">
                    <slot name="actions"></slot>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import VSectionTitle from './SectionTitle'

    export default {
        name: 'VFormSection',

        emits: ['submitted'],

        components: {
            VSectionTitle,
        },

        computed: {
            hasActions() {
                return !!this.$slots.actions
            }
        }
    }
</script>
