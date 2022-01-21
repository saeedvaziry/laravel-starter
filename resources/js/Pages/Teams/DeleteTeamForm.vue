<template>
    <v-action-section>
        <template #title>
            Delete Team
        </template>

        <template #description>
            Permanently delete this team.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regarding this team that you wish to retain.
            </div>

            <div class="mt-5">
                <v-danger-button @click="confirmTeamDeletion">
                    Delete Team
                </v-danger-button>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <v-confirmation-modal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
                <template #title>
                    Delete Team
                </template>

                <template #content>
                    Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted.
                </template>

                <template #footer>
                    <v-secondary-button @click="confirmingTeamDeletion = false">
                        Cancel
                    </v-secondary-button>

                    <v-danger-button class="ml-2" @click="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Delete Team
                    </v-danger-button>
                </template>
            </v-confirmation-modal>
        </template>
    </v-action-section>
</template>

<script>
    import VActionSection from '@/Components/ActionSection'
    import VConfirmationModal from '@/Components/ConfirmationModal'
    import VDangerButton from '@/Components/DangerButton'
    import VSecondaryButton from '@/Components/SecondaryButton'

    export default {
        props: ['team'],

        components: {
            VActionSection,
            VConfirmationModal,
            VDangerButton,
            VSecondaryButton,
        },

        data() {
            return {
                confirmingTeamDeletion: false,
                deleting: false,

                form: this.$inertia.form()
            }
        },

        methods: {
            confirmTeamDeletion() {
                this.confirmingTeamDeletion = true
            },

            deleteTeam() {
                this.form.delete(route('teams.destroy', this.team), {
                    errorBag: 'deleteTeam'
                });
            },
        },
    }
</script>
