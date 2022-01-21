<template>
    <profile-layout>
        <template #header>
            <div class="w-full flex items-center justify-between relative">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Create Team
                </h2>
                <v-secondary-button class="absolute right-0" @click="$inertia.visit(route('teams.show', $page.props.user.current_team))">
                    back to team settings
                </v-secondary-button>
            </div>
        </template>

        <div>
            <div class="max-w-6xl mx-auto py-10">
                <create-team-form />
            </div>
        </div>
    </profile-layout>
</template>

<script>
    import CreateTeamForm from './CreateTeamForm'
    import ProfileLayout from "@/Layouts/ProfileLayout";
    import VSecondaryButton from "@/Components/SecondaryButton";

    export default {
        components: {
            VSecondaryButton,
            ProfileLayout,
            CreateTeamForm,
        },

        mounted() {
            document.title = 'Create New Team'
        }
    }
</script>
