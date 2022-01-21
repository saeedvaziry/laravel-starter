<template>
    <div>
        <!-- Generate API Token -->
        <v-form-section @submitted="createApiToken">
            <template #title>
                Create API Token
            </template>

            <template #description>
                API tokens allow third-party services to authenticate with our application on your behalf.
            </template>

            <template #form>
                <!-- Token Name -->
                <div class="col-span-6 sm:col-span-6">
                    <v-label for="name" value="Name" />
                    <v-input id="name" type="text" class="mt-1 block w-full" v-model="createApiTokenForm.name" autofocus />
                    <v-input-error :message="createApiTokenForm.errors.name" class="mt-2" />
                </div>

                <!-- Token Permissions -->
                <div class="col-span-6" v-if="availablePermissions.length > 0">
                    <v-label for="permissions" value="Permissions" />

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="permission in availablePermissions" :key="permission">
                            <label class="flex items-center">
                                <v-checkbox :value="permission" v-model:checked="createApiTokenForm.permissions"/>
                                <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <template #actions>
                <v-action-message :on="createApiTokenForm.recentlySuccessful" class="mr-3">
                    Created.
                </v-action-message>

                <v-button :class="{ 'opacity-25': createApiTokenForm.processing }" :disabled="createApiTokenForm.processing">
                    Create
                </v-button>
            </template>
        </v-form-section>

        <div v-if="tokens.length > 0">
            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <v-action-section>
                    <template #title>
                        Manage API Tokens
                    </template>

                    <template #description>
                        You may delete any of your existing tokens if they are no longer needed.
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between" v-for="token in tokens" :key="token.id">
                                <div>
                                    {{ token.name }}
                                </div>

                                <div class="flex items-center">
                                    <div class="text-sm text-gray-400" v-if="token.last_used_ago">
                                        Last used {{ token.last_used_ago }}
                                    </div>

                                    <button class="cursor-pointer ml-6 text-sm text-gray-400 underline"
                                        @click="manageApiTokenPermissions(token)"
                                        v-if="availablePermissions.length > 0"
                                    >
                                        Permissions
                                    </button>

                                    <button class="cursor-pointer ml-6 text-sm text-red-500" @click="confirmApiTokenDeletion(token)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </v-action-section>
            </div>
        </div>

        <!-- Token Value Modal -->
        <v-dialog-modal :show="displayingToken" @close="displayingToken = false">
            <template #title>
                API Token
            </template>

            <template #content>
                <div>
                    Please copy your new API token. For your security, it won't be shown again.
                </div>

                <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500" v-if="$page.props.data.token">
                    {{ $page.props.data.token }}
                </div>
            </template>

            <template #footer>
                <v-secondary-button @click="displayingToken = false">
                    Close
                </v-secondary-button>
            </template>
        </v-dialog-modal>

        <!-- API Token Permissions Modal -->
        <v-dialog-modal :show="managingPermissionsFor" @close="managingPermissionsFor = null">
            <template #title>
                API Token Permissions
            </template>

            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="permission in availablePermissions" :key="permission">
                        <label class="flex items-center">
                            <v-checkbox :value="permission" v-model:checked="updateApiTokenForm.permissions"/>
                            <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                        </label>
                    </div>
                </div>
            </template>

            <template #footer>
                <v-secondary-button @click="managingPermissionsFor = null">
                    Cancel
                </v-secondary-button>

                <v-button class="ml-2" @click="updateApiToken" :class="{ 'opacity-25': updateApiTokenForm.processing }" :disabled="updateApiTokenForm.processing">
                    Save
                </v-button>
            </template>
        </v-dialog-modal>

        <!-- Delete Token Confirmation Modal -->
        <v-confirmation-modal :show="apiTokenBeingDeleted" @close="apiTokenBeingDeleted = null">
            <template #title>
                Delete API Token
            </template>

            <template #content>
                Are you sure you would like to delete this API token?
            </template>

            <template #footer>
                <v-secondary-button @click="apiTokenBeingDeleted = null">
                    Cancel
                </v-secondary-button>

                <v-danger-button class="ml-2" @click="deleteApiToken" :class="{ 'opacity-25': deleteApiTokenForm.processing }" :disabled="deleteApiTokenForm.processing">
                    Delete
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
    import VCheckbox from '@/Components/Checkbox'
    import VInputError from '@/Components/InputError'
    import VLabel from '@/Components/Label'
    import VSecondaryButton from '@/Components/SecondaryButton'
    import VSectionBorder from '@/Components/SectionBorder'

    export default {
        components: {
            VActionMessage,
            VActionSection,
            VButton,
            VConfirmationModal,
            VDangerButton,
            VDialogModal,
            VFormSection,
            VInput,
            VCheckbox,
            VInputError,
            VLabel,
            VSecondaryButton,
            VSectionBorder,
        },

        props: [
            'tokens',
            'availablePermissions',
            'defaultPermissions',
        ],

        data() {
            return {
                createApiTokenForm: this.$inertia.form({
                    name: '',
                    permissions: this.defaultPermissions,
                }),

                updateApiTokenForm: this.$inertia.form({
                    permissions: []
                }),

                deleteApiTokenForm: this.$inertia.form(),

                displayingToken: false,
                managingPermissionsFor: null,
                apiTokenBeingDeleted: null,
            }
        },

        methods: {
            createApiToken() {
                this.createApiTokenForm.post(route('api-tokens.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.displayingToken = true
                        this.createApiTokenForm.reset()
                    }
                })
            },

            manageApiTokenPermissions(token) {
                this.updateApiTokenForm.permissions = token.abilities

                this.managingPermissionsFor = token
            },

            updateApiToken() {
                this.updateApiTokenForm.put(route('api-tokens.update', this.managingPermissionsFor), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.managingPermissionsFor = null),
                })
            },

            confirmApiTokenDeletion(token) {
                this.apiTokenBeingDeleted = token
            },

            deleteApiToken() {
                this.deleteApiTokenForm.delete(route('api-tokens.destroy', this.apiTokenBeingDeleted), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.apiTokenBeingDeleted = null),
                })
            },
        },
    }
</script>
