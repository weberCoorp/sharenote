<?php

namespace App\Http\Requests;


use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoteShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    /*
     function($attribute, $value, $fail) use($note){
                $user = User::with('availableNotes:id')->where('email', $value)->first();
                //dd($user);
                if($user){
                    if($user->email === $value){
                        $fail($attribute.' себе нельзя.');
                    }
                    if( in_array($note->id, $user->availableNotes->pluck('id')->toArray()) ){
                        $fail($attribute.' уже добавлен.');
                    }

                } else {
                    $fail($attribute.' no user.');
                }
    public function rules()
    {

        return [
            'email' => ['required','email','exists:users']
        ];
    }*/

    public function rules()
    {
        $note = $this->note;

        return [
            'email' => ['required', 'email', function ($attribute, $value, $fail) use ($note) {
                $user = User::with('availableNotes:id')->where('email', $value)->first();

                if ($user) {

                    if ($user->id == $note->user_id) {
                        $fail(__('You can\'t share with yourself'));
                    }
                    if (in_array($note->id, $user->availableNotes->pluck('id')->toArray())) {
                        $fail(__('This email address has already been added'));
                    }
                } else {
                    $fail(__('Couldn\'t find an account associated with this email. Please try again.'));
                }
            }]
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => __('Couldn’t find an account associated with this email. Please try again.'),
        ];
    }// You can't share a locked note
}
