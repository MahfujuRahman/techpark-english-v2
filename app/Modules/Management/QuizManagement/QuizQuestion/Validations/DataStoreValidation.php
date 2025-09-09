<?php

namespace App\Modules\Management\QuizManagement\QuizQuestion\Validations;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class DataStoreValidation extends FormRequest
{
    /**
     * Determine if the  is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * validateError to make this request.
     */
    public function validateError($data)
    {
        $errorPayload =  $data->getMessages();
        return response(['status' => 'validation_error', 'errors' => $errorPayload], 422);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validateError($validator->errors()));
        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException($this->validateError($validator->errors()));
        }
        parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'quiz_question_topic_id' => 'required | sometimes',
            'title' => 'required | sometimes',
            'question_level' => 'required | sometimes',
            'mark' => 'required | sometimes',
            'is_multiple' => 'required | sometimes',
            'session_year' => 'nullable | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'options' => 'array | sometimes',
            'options.*.value' => 'required_with:options | sometimes',
            'options.*.type' => ['required_with:options', Rule::in(['text', 'image'])],
            'options.*.is_correct' => 'boolean | sometimes',
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB max size
        ];
    }
}