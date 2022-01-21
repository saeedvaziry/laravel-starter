<template>
    <profile-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Tokens
            </h2>
        </template>

        <div>
            <div class="max-w-6xl mx-auto py-10">
                <api-token-manager :tokens="tokens"
                                   :available-permissions="availablePermissions"
                                   :default-permissions="defaultPermissions" />
            </div>
        </div>
    </profile-layout>
</template>

<script>
    import ApiTokenManager from './ApiTokenManager'
    import ProfileLayout from "@/Layouts/ProfileLayout";

    export default {
        props: [
            'tokens',
            'availablePermissions',
            'defaultPermissions',
        ],

        components: {
            ProfileLayout,
            ApiTokenManager,
        },

        mounted() {
            document.title = 'API Tokens';
        }
    }
</script>
