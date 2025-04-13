<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Task;
use App\Models\User;

class AdminRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Doctor());
    }
}
