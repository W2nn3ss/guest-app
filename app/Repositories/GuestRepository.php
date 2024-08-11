<?php

namespace App\Repositories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Collection;

class GuestRepository
{
    public function create(array $data): Guest
    {
        return Guest::create($data);
    }

    public function update(int $id, array $data): Guest
    {
        $guest = Guest::findOrFail($id);
        $guest->update($data);
        return $guest;
    }

    public function find(int $id): Guest
    {
        return Guest::findOrFail($id);
    }

    public function findAll(): Collection
    {
        return Guest::all();
    }

    public function delete(int $id): void
    {
        Guest::findOrFail($id)->delete();
    }
}
