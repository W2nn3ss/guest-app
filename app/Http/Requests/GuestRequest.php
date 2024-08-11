<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Validation\Rule;

/**
 * @bodyParam first_name string required Имя гостя. Example: Петр
 * @bodyParam last_name string required Фамилия гостя. Example: Петров
 * @bodyParam email string required Email гостя. Example: petr@test.ru
 * @bodyParam phone string required Телефон гостя. Example: +79991112233
 * @bodyParam country string Страна проживания гостя. Example: Россия
 */
class GuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guestId = $this->route('id');

        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('guests')->ignore($guestId),
            ],
            'phone' => [
                'required',
                'string',
                'min:11',
                'max:12',
                Rule::unique('guests')->ignore($guestId),
            ],
            'country' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Поле "Имя" обязательно для заполнения.',
            'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Поле "Email" должно быть действительным адресом электронной почты.',
            'email.unique' => 'Такой email уже зарегистрирован.',
            'phone.required' => 'Поле "Телефон" обязательно для заполнения.',
            'phone.string' => 'Поле "Телефон" должно быть строкой.',
            'phone.unique' => 'Такой телефон уже зарегистрирован.',
            'phone.min' => 'Поле "Телефон" должно содержать не менее 11 символов.',
            'phone.max' => 'Поле "Телефон" должно содержать не более 12 символов.',
            'phone.invalid' => 'Невалидный номер телефона.',
        ];
    }

    protected function passedValidation(): void
    {
        if (!$this->input('country')) {
            $country = $this->determineCountryFromPhone($this->input('phone'));
            if ($country) {
                $this->merge(['country' => $country]);
            }
        }
    }

    protected function validatePhone($validator): void
    {
        try
        {
            $country = $this->determineCountryFromPhone($this->input('phone'));

            if (!$country)
            {
                $validator->errors()->add('phone', 'Невалидный номер телефона.');
            }
        }
        catch (\libphonenumber\NumberParseException $e)
        {
            $validator->errors()->add('phone', 'Невалидный номер телефона.');
        }
    }

    protected function determineCountryFromPhone(string $phone): ?string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $geocoder = PhoneNumberOfflineGeocoder::getInstance();

        $phoneNumber = $phoneUtil->parse($phone, 'RU');

        if ($phoneUtil->isValidNumber($phoneNumber))
        {
            return $geocoder->getDescriptionForNumber($phoneNumber, 'ru');
        }

        return null;
    }
}
