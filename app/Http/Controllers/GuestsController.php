<?php

namespace App\Http\Controllers;

use App\DTO\GuestDTO;
use App\Exceptions\GuestException;
use App\Http\Requests\GuestRequest;
use App\Http\Resources\GuestResource;
use App\Services\GuestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GuestsController extends Controller
{
    public function __construct(protected guestService $guestService){}

    /**
     * Создает нового гостя.
     *
     * @param GuestRequest $request
     * @return GuestResource
     * @throws \Exception
     * @apiResource App\Http\Resources\GuestResource
     * @apiResourceModel App\Models\Guest
     */
    public function createGuest(GuestRequest $request): GuestResource
    {
        $guestDTO = GuestDTO::fromRequest($request);
        $guest = $this->guestService->createGuest($guestDTO);

        return new GuestResource($guest);
    }

    /**
     * Получает информацию о госте или список всех гостей.
     *
     * @param int|null $id
     * @return AnonymousResourceCollection|GuestResource
     * @throws GuestException
     * @apiResource App\Http\Resources\GuestResource
     * @apiResourceModel App\Models\Guest
     */
    public function getGuest(int $id = null): AnonymousResourceCollection|GuestResource
    {
        if ($id !== null)
        {
            $guest = $this->guestService->getGuest($id);
            return new GuestResource($guest);
        }

        $guests = $this->guestService->getAllGuests();
        return GuestResource::collection($guests);
    }

    /**
     * Обновляет данные гостя.
     *
     * @param int $id
     * @param GuestRequest $request
     * @return GuestResource
     * @throws \Exception
     * @apiResource App\Http\Resources\GuestResource
     * @apiResourceModel App\Models\Guest
     */
    public function updateGuest(int $id, GuestRequest $request): GuestResource
    {
        $guestDTO = GuestDTO::fromRequest($request);
        $guest = $this->guestService->updateGuest($id, $guestDTO);

        return new GuestResource($guest);
    }

    /**
     * Удаляет гостя.
     *
     * @param int $id
     * @return JsonResponse
     * @throws GuestException
     */
    public function deleteGuest(int $id): JsonResponse
    {
        $this->guestService->deleteGuest($id);
        return response()->json('Deleted');
    }
}
