<template>
    <div>
        <div v-if="userPermissions.canAddTeamMembers">
            <!-- Add Team Member -->
            <v-form-section @submitted="addTeamMember">
                <template #title>
                    Add Team Member
                </template>

                <template #description>
                    Add a new team member to your team, allowing them to collaborate with you.
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Please provide the email address of the person you would like to add to this team.
                        </div>
                    </div>

                    <!-- Member Email -->
                    <div class="col-span-6 sm:col-span-6">
                        <v-label for="email" value="Email" />
                        <v-input id="email" type="email" class="mt-1 block w-full" v-model="addTeamMemberForm.email" />
                        <v-input-error :message="addTeamMemberForm.errors.email" class="mt-2" />
                    </div>

                    <!-- Member Permissions -->
                    <div class="col-span-6" v-if="availablePermissions.length > 0">
                        <v-label for="permissions" value="Permissions" />
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="permission in availablePermissions" :key="permission">
                                <label class="flex items-center">
                                    <v-checkbox :value="permission" v-model:checked="addTeamMemberForm.permissions" />
                                    <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                </template>

                <template #actions>
                    <v-action-message :on="addTeamMemberForm.recentlySuccessful" class="mr-3">
                        Added.
                    </v-action-message>

                    <v-button :class="{ 'opacity-25': addTeamMemberForm.processing }" :disabled="addTeamMemberForm.processing">
                        Add
                    </v-button>
                </template>
            </v-form-section>
        </div>

        <div v-if="team.team_invitations.length > 0 && userPermissions.canAddTeamMembers">
            <!-- Team Member Invitations -->
            <v-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Pending Team Invitations
                </template>

                <template #description>
                    These people have been invited to your team and have been sent an invitation email. They may join the team by accepting the email invitation.
                </template>

                <!-- Pending Team Member Invitation List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="invitation in team.team_invitations" :key="invitation.id">
                            <div class="text-gray-600">{{ invitation.email }}</div>

                            <div class="flex items-center">
                                <!-- Cancel Team Invitation -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                        @click="cancelTeamInvitation(invitation)"
                                        v-if="userPermissions.canRemoveTeamMembers">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </v-action-section>
        </div>

        <div v-if="team.users.length > 0">
            <!-- Manage Team Members -->
            <v-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Team Members
                </template>

                <template #description>
                    All of the people that are part of this team.
                </template>

                <!-- Team Member List -->
                <template #content>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between" v-for="user in team.users" :key="user.id">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full" :src="user.profile_photo_url" :alt="user.name">
                                <div class="ml-4">{{ user.name }}</div>
                            </div>

                            <div class="flex items-center">
                                <!-- Manage Team Member Permissions -->
                                <button class="ml-2 text-sm text-gray-400 underline"
                                        @click="managePermissions(user)"
                                        v-if="userPermissions.canAddTeamMembers && availablePermissions.length">
                                    Manage Permissions
                                </button>

                                <!-- Leave Team -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500"
                                        @click="confirmLeavingTeam"
                                        v-if="$page.props.user.id === user.id">
                                    Leave
                                </button>

                                <!-- Remove Team Member -->
                                <button class="cursor-pointer ml-6 text-sm text-red-500"
                                        @click="confirmTeamMemberRemoval(user)"
                                        v-if="userPermissions.canRemoveTeamMembers">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </v-action-section>
        </div>

        <!-- Permissions Management Modal -->
        <v-dialog-modal :show="currentlyManagingPermissions" @close="currentlyManagingPermissions = false">
            <template #title>
                Manage Permissions
            </template>

            <template #content>
                <div v-if="managingPermissionsFor">
                    <div class="col-span-6" v-if="availablePermissions.length > 0">
                        <v-label for="permissions" value="Permissions" />
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="permission in availablePermissions" :key="permission">
                                <label class="flex items-center">
                                    <v-checkbox :value="permission" v-model:checked="updatePermissionsForm.permissions" />
                                    <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template #footer>
                <v-secondary-button @click="currentlyManagingPermissions = false">
                    Cancel
                </v-secondary-button>

                <v-button class="ml-2" @click="updatePermissions" :class="{ 'opacity-25': updatePermissionsForm.processing }" :disabled="updatePermissionsForm.processing">
                    Save
                </v-button>
            </template>
        </v-dialog-modal>

        <!-- Leave Team Confirmation Modal -->
        <v-confirmation-modal :show="confirmingLeavingTeam" @close="confirmingLeavingTeam = false">
            <template #title>
                Leave Team
            </template>

            <template #content>
                Are you sure you would like to leave this team?
            </template>

            <template #footer>
                <v-secondary-button @click="confirmingLeavingTeam = false">
                    Cancel
                </v-secondary-button>

                <v-danger-button class="ml-2" @click="leaveTeam" :class="{ 'opacity-25': leaveTeamForm.processing }" :disabled="leaveTeamForm.processing">
                    Leave
                </v-danger-button>
            </template>
        </v-confirmation-modal>

        <!-- Remove Team Member Confirmation Modal -->
        <v-confirmation-modal :show="teamMemberBeingRemoved" @close="teamMemberBeingRemoved = null">
            <template #title>
                Remove Team Member
            </template>

            <template #content>
                Are you sure you would like to remove this person from the team?
            </template>

            <template #footer>
                <v-secondary-button @click="teamMemberBeingRemoved = null">
                    Cancel
                </v-secondary-button>

                <v-danger-button class="ml-2" @click="removeTeamMember" :class="{ 'opacity-25': removeTeamMemberForm.processing }" :disabled="removeTeamMemberForm.processing">
                    Remove
                </v-danger-button>
            </template>
        </v-confirmation-modal>
    </div>
</template>

<script>
    import VActionMessage from '@/Components/ActionMessage'
    import VActionSection from '@/Components/ActionSection'
    import VButton from '@/Components/Button'
    import VConfirmationModal from '@/Components/ConfirmationModal'
    import VDangerButton from '@/Components/DangerButton'
    import VDialogModal from '@/Components/DialogModal'
    import VFormSection from '@/Components/FormSection'
    import VInput from '@/Components/Input'
    import VInputError from '@/Components/InputError'
    import VLabel from '@/Components/Label'
    import VSecondaryButton from '@/Components/SecondaryButton'
    import VSectionBorder from '@/Components/SectionBorder'
    import VCheckbox from "@/Components/Checkbox";

    export default {
        components: {
            VCheckbox,
            VActionMessage,
            VActionSection,
            VButton,
            VConfirmationModal,
            VDangerButton,
            VDialogModal,
            VFormSection,
            VInput,
            VInputError,
            VLabel,
            VSecondaryButton,
            VSectionBorder,
        },

        props: [
            'team',
            'userPermissions',
            'availablePermissions',
            'defaultPermissions',
        ],

        data() {
            return {
                addTeamMemberForm: this.$inertia.form({
                    email: '',
                    permissions: this.defaultPermissions,
                }),

                updatePermissionsForm: this.$inertia.form({
                    permissions: [],
                }),

                leaveTeamForm: this.$inertia.form(),
                removeTeamMemberForm: this.$inertia.form(),

                currentlyManagingPermissions: false,
                managingPermissionsFor: null,
                confirmingLeavingTeam: false,
                teamMemberBeingRemoved: null,
            }
        },

        methods: {
            addTeamMember() {
                this.addTeamMemberForm.post(route('team-members.store', this.team), {
                    errorBag: 'addTeamMember',
                    preserveScroll: true,
                    onSuccess: () => this.addTeamMemberForm.reset(),
                });
            },

            cancelTeamInvitation(invitation) {
                this.$inertia.delete(route('team-invitations.destroy', invitation), {
                    preserveScroll: true
                });
            },

            managePermissions(teamMember) {
                this.managingPermissionsFor = teamMember
                this.updatePermissionsForm.permissions = teamMember.membership.permissions
                this.currentlyManagingPermissions = true
            },

            updatePermissions() {
                this.updatePermissionsForm.put(route('team-members.update', [this.team, this.managingPermissionsFor]), {
                    preserveScroll: true,
                    onSuccess: () => (this.currentlyManagingPermissions = false),
                })
            },

            confirmLeavingTeam() {
                this.confirmingLeavingTeam = true
            },

            leaveTeam() {
                this.leaveTeamForm.delete(route('team-members.destroy', [this.team, this.$page.props.user]))
            },

            confirmTeamMemberRemoval(teamMember) {
                this.teamMemberBeingRemoved = teamMember
            },

            removeTeamMember() {
                this.removeTeamMemberForm.delete(route('team-members.destroy', [this.team, this.teamMemberBeingRemoved]), {
                    errorBag: 'removeTeamMember',
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.teamMemberBeingRemoved = null),
                })
            },

            displayablePermissions(permissions) {
                return permissions;
            },
        },
    }
</script>
