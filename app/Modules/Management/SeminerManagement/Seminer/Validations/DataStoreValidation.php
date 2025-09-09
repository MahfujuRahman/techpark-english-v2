<?php

namespace App\Modules\Management\SeminerManagement\Seminer\Validations;

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
        // Determine if this is an update request or a create
        $isUpdate = $this->route('slug') !== null;

        // Image should be required on create, optional on update
        $imageRule = $isUpdate
            ? 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120'
            : 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120';

        return [
            'title' => 'required | sometimes',
            'description' => 'required | sometimes',
            'poster' => $imageRule,
            'whatsapp_group' => 'required | sometimes',
            'facebook_group' => 'required | sometimes',
            'telegram_group' => 'required | sometimes',
            'date_time' => [
                'required',
                'sometimes',
                'date',
                'after:' . \Carbon\Carbon::now(env('TIMEZONE', config('app.timezone', 'UTC')))->format('Y-m-d H:i:s'),
            ],
            'promo_video' => 'required | sometimes',
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
        ];
    }
}
