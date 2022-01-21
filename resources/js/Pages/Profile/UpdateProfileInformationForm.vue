<template>
    <v-form-section @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div class="col-span-6 sm:col-span-6">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden" ref="photo" @change="updatePhotoPreview">

                <v-label for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div class="mt-2" v-show="! photoPreview">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" v-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <v-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </v-secondary-button>

                <v-secondary-button type="button" class="mt-2" @click.prevent="deletePhoto" v-if="user.profile_photo_path">
                    Remove Photo
                </v-secondary-button>

                <v-input-error :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-6">
                <v-label for="name" value="Name" />
                <v-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <v-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-6">
                <v-label for="email" value="Email" />
                <v-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                <v-input-error :message="form.errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
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
    import VButton from '@/Components/Button'
    import VFormSection from '@/Components/FormSection'
    import VInput from '@/Components/Input'
    import VInputError from '@/Components/InputError'
    import VLabel from '@/Components/Label'
    import VActionMessage from '@/Components/ActionMessage'
    import VSecondaryButton from '@/Components/SecondaryButton'

    export default {
        components: {
            VActionMessage,
            VButton,
            VFormSection,
            VInput,
            VInputError,
            VLabel,
            VSecondaryButton,
        },

        props: ['user'],

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    name: this.user.name,
                    email: this.user.email,
                    photo: null,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.post(route('user-profile-information.update'), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    onSuccess: () => (this.clearPhotoFileInput()),
                });
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const photo = this.$refs.photo.files[0];

                if (!photo) return;

                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(photo);
            },

            deletePhoto() {
                this.$inertia.delete(route('user.current-user-photo.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.photoPreview = null;
                        this.clearPhotoFileInput();
                    },
                });
            },

            clearPhotoFileInput() {
                if (this.$refs.photo?.value) {
                    this.$refs.photo.value = null;
                }
            },
        },
    }
</script>
