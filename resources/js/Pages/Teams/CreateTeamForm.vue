<template>
    <v-form-section @submitted="createTeam">
        <template #title>
            Team Details
        </template>

        <template #description>
            Create a new team to collaborate with others on projects.
        </template>

        <template #form>
            <div class="col-span-6">
                <v-label value="Team Owner" />

                <div class="flex items-center mt-2">
                    <img class="w-12 h-12 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">

                    <div class="ml-4 leading-tight">
                        <div>{{ $page.props.user.name }}</div>
                        <div class="text-gray-700 text-sm">{{ $page.props.user.email }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-6 sm:col-span-6">
                <v-label for="name" value="Team Name" />
                <v-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                <v-input-error :message="form.errors.name" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <v-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Create
            </v-button>
        </template>
    </v-form-section>
</template>

<script>
    import VButton from '@/Components/Button'
    import VFormSection from '@/Components/FormSection'
    import VInput from '@/Components/Input'
    import VInputError from '@/Components/InputError'
    import VLabel from '@/Components/Label'

    export default {
        components: {
            VButton,
            VFormSection,
            VInput,
            VInputError,
            VLabel,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                })
            }
        },

        methods: {
            createTeam() {
                this.form.post(route('teams.store'), {
                    errorBag: 'createTeam',
                    preserveScroll: true
                });
            },
        },
    }
</script>
