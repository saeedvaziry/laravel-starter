<template>
    <profile-layout>
        <template #header>
            <div class="w-full flex items-center justify-between relative">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Team Settings
                </h2>
                <v-button class="absolute right-0" @click="$inertia.visit(route('teams.create'))">
                    create new team
                </v-button>
            </div>
        </template>

        <div>
            <div class="max-w-6xl mx-auto py-10">
                <update-team-name-form :team="team" :permissions="permissions" />

                <team-member-manager class="mt-10 sm:mt-0"
                                     :team="team"
                                     :available-permissions="availablePermissions"
                                     :default-permissions="defaultPermissions"
                                     :user-permissions="permissions" />

                <template v-if="permissions.canDeleteTeam && ! team.personal_team">
                    <delete-team-form class="mt-10 sm:mt-0" :team="team" />
                </template>
            </div>
        </div>
    </profile-layout>
</template>

<script>
    import TeamMemberManager from './TeamMemberManager'
    import DeleteTeamForm from './DeleteTeamForm'
    import VSectionBorder from '@/Components/SectionBorder'
    import UpdateTeamNameForm from './UpdateTeamNameForm'
    import ProfileLayout from "@/Layouts/ProfileLayout";
    import VButton from "@/Components/Button";

    export default {
        props: [
            'team',
            'permissions',
            'availablePermissions',
            'defaultPermissions',
        ],

        components: {
            VButton,
            ProfileLayout,
            DeleteTeamForm,
            VSectionBorder,
            TeamMemberManager,
            UpdateTeamNameForm,
        },

        mounted() {
            document.title = 'Team Settings';
        }
    }
</script>
