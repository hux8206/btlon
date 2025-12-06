<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    //doc file lay so luong tu trong file
    protected $maxword = 0;

    protected function prepareForValidation()
    {
        if ($this->hasFile('vocabFile') && $this->file('vocabFile')->isValid()) {
            $content = file($this->file('vocabFile')->getRealPath());
            $this->maxword = count($content);
        }
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'vocabFile' => 'required|file|mimes:txt|max:2048',
            'timeEachQuestion' => 'required|integer|min:5',
            'quantity' => 'exclude_if:all,1|required|integer|min:1|max:' . $this->maxword,
            'all' =>'boolean',
            'mode' => 'required|in:0,1'

        ];
    }
}
