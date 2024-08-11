<?php

namespace App\Services;

use App\DTO\GuestDTO;
use App\Exceptions\GuestException;
use App\Repositories\GuestRepository;
use App\Models\Guest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class GuestService
{
    protected const CACHE_TTL = 300;
    public function __construct(protected GuestRepository $guestRepository){}

    public function createGuest(GuestDTO $guestDTO): Guest
    {
        $guest = $this->guestRepository->create([
            'first_name' => $guestDTO->first_name,
            'last_name' => $guestDTO->last_name,
            'email' => $guestDTO->email,
            'phone' => $guestDTO->phone,
            'country' => $guestDTO->country,
        ]);

        Cache::forget('guests');

        return $guest;
    }

    public function updateGuest(int $id, GuestDTO $guestDTO): Guest
    {
        try
        {
            $guest = $this->guestRepository->update($id, [
                'first_name' => $guestDTO->first_name,
                'last_name' => $guestDTO->last_name,
                'email' => $guestDTO->email,
                'phone' => $guestDTO->phone,
                'country' => $guestDTO->country,
            ]);
            Cache::forget("guest_{$id}");
            Cache::forget('guests');
        }
        catch (\Exception $e)
        {
            throw new GuestException("Гость с id {$id} не найден", 404);
        }

        return $guest;
    }

    public function getGuest(int $id): Guest
    {
        try
        {
            $guest = Cache::remember("guest_{$id}", self::CACHE_TTL, function () use ($id)
            {
                return $this->guestRepository->find($id);
            });
        }
        catch (\Exception $e)
        {
            throw new GuestException("Гость с id {$id} не найден", 404);
        }

        return $guest;
    }

    public function getAllGuests(): Collection
    {
        return Cache::remember('guests', self::CACHE_TTL, function ()
        {
            return $this->guestRepository->findAll();
        });
    }

    public function deleteGuest(int $id): void
    {
        try
        {
            $this->guestRepository->delete($id);
            Cache::forget("guest_{$id}");
            Cache::forget('guests');
        }
        catch (\Exception $e)
        {
            throw new GuestException("Гость с id {$id} не найден", 404);
        }

    }
}
