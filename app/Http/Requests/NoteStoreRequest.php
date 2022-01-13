<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function prepareForValidation()
    {

        $this->merge([
            'private' => $this->has('private'),
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name'        => ['required','max:191'],
            'description' => ['required','max:2000','string'],
            'filepond.*'  => ['nullable','exists:temporary_files,folder'],
            'private'     => ['boolean']
        ];
    }
    public function validated()
    {
        $data = parent::validated();
        $data['user_id'] = auth()->user()->id;
        return $data;
    }
}
