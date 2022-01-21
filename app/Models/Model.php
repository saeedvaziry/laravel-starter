<?php

namespace App\Models;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @return void
     */
    public function cleanDelete()
    {
        $this->delete();
    }
}
