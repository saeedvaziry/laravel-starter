<template>
    <v-form-section @submitted="updateTeamName">
        <template #title>
            Team Name
        </template>

        <template #description>
            The team's name and owner information.
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6">
                <v-label value="Team Owner" />

                <div class="flex items-center mt-2">
                    <img class="w-12 h-12 rounded-full object-cover" :src="team.owner.profile_photo_url" :alt="team.owner.name">

                    <div class="ml-4 leading-tight">
                        <div>{{ team.owner.name }}</div>
                        <div class="text-gray-700 text-sm">{{ team.owner.email }}</div>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-6">
                <v-label for="name" value="Team Name" />

                <v-input id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            :disabled="! permissions.canUpdateTeam" />

                <v-input-error :message="form.errors.name" class="mt-2" />
            </div>
        </template>

        <template #actions v-if="permissions.canUpdateTeam">
            <v-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </v-action-message>

            <v-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </v-button>
        </template>
    </v-form-section>
</template>

<script>
    import VActionMessage from '@/Components/ActionMessage'
    import VButton from '@/Components/Button'
    import VFormSection from '@/Components/FormSection'
    import VInput from '@/Components/Input'
    import VInputError from '@/Components/InputError'
    import VLabel from '@/Components/Label'

    export default {
        components: {
            VActionMessage,
            VButton,
            VFormSection,
            VInput,
            VInputError,
            VLabel,
        },

        props: ['team', 'permissions'],

        data() {
            return {
                form: this.$inertia.form({
                    name: this.team.name,
                })
            }
        },

        methods: {
            updateTeamName() {
                this.form.put(route('teams.update', this.team), {
                    errorBag: 'updateTeamName',
                    preserveScroll: true
                });
            },
        },
    }
</script>
