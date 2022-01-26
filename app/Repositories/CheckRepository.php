<?php


namespace App\Repositories;


use App\Models\Check;

class CheckRepository
{
    public function store(array $data): Check
    {
        $check = new Check($data);

        $check->save();

        return $check;
    }
}
