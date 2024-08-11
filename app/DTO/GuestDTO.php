<?php

namespace App\DTO;

use Illuminate\Http\Request;

class GuestDTO
{
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $phone;
    public ?string $country;

    public function __construct(string $first_name, string $last_name, string $email, string $phone, ?string $country = null)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->country = $country;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('email'),
            $request->input('phone'),
            $request->input('country')
        );
    }
}
