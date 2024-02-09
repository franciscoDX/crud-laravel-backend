<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateProjectRequest extends FormRequest
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
    public function rules()
    {
        return [
            'projectName' => 'required',
            'clientName' => 'required',
            'status' => 'required',
            'progress' => 'required|numeric|min:0|max:100',
        ];
    }

     /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'projectName.required' => 'The project name is required.',
            'clientName.required' => 'The client name is required.',
            'status.required' => 'The status is required.',
            'progress.required' => 'The progress is required.',
            'progress.numeric' => 'The progress must be a number.',
            'progress.min' => 'The progress must be at least 0.',
            'progress.max' => 'The progress must be at most 100.',
            'progress.integer' => 'The progress must be an integer.'
        ];
    }

        /**
    * Get the error messages for the defined validation rules.*
    * @return array
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
        'errors' => $validator->errors(),
        'error' => true
        ], 200));
    }
            
}