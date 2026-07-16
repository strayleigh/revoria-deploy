<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberId = $this->route('member')?->id_anggota;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'tanggal_bergabung' => ['nullable', 'date'],
            'status_anggota' => ['required', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:50'],
        ];
    }
}
