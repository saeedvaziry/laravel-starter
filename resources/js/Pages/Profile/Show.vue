<template>
    <profile-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Profile
            </h2>
        </template>

        <div>
            <div class="max-w-6xl mx-auto py-10">
                <div>
                    <update-profile-information-form :user="$page.props.user" />
                </div>

                <div>
                    <update-password-form class="mt-10 sm:mt-0" />
                </div>

                <div>
                    <two-factor-authentication-form class="mt-10 sm:mt-0" />
                </div>

                <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />

                <delete-user-form class="mt-10 sm:mt-0" />
            </div>
        </div>
    </profile-layout>
</template>

<script>
    import DeleteUserForm from './DeleteUserForm'
    import VSectionBorder from '@/Components/SectionBorder'
    import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
    import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
    import UpdatePasswordForm from './UpdatePasswordForm'
    import UpdateProfileInformationForm from './UpdateProfileInformationForm'
    import ProfileLayout from "@/Layouts/ProfileLayout";

    export default {
        props: ['sessions'],

        components: {
            ProfileLayout,
            DeleteUserForm,
            VSectionBorder,
            LogoutOtherBrowserSessionsForm,
            TwoFactorAuthenticationForm,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
        },

        mounted() {
            document.title = 'Profile';
        }
    }
</script>
