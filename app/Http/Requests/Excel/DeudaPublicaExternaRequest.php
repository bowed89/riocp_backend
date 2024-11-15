<?php

namespace App\Http\Requests\Excel;

use Illuminate\Foundation\Http\FormRequest;

class DeudaPublicaExternaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,csv,xls,xlsb',
        ];
    }
}
