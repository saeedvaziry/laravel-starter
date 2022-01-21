<?php

namespace App\Actions\Team;

use Illuminate\Support\Facades\DB;

class DeleteTeam
{
    /**
     * Delete the given team.
     *
     * @param mixed $team
     * @return void
     */
    public function delete($team)
    {
        DB::transaction(function () use ($team) {
            $team->cleanDelete();
        });
    }
}
