<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prompt' => 'required|string|max:4000',
            'model' => 'sometimes|string',
            
            // Image specific
            'aspectRatio' => 'sometimes|in:1:1,3:4,4:3,9:16,16:9',
            'numberOfImages' => 'sometimes|integer|min:1|max:4',
            'imageSize' => 'sometimes|in:1K,2K',
            
            // Audio specific
            'voice' => 'sometimes|string',
            
            // Generic (mantener compatibilidad)
            'format' => 'sometimes|string',
            'size' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'aspectRatio.in' => 'Aspect ratio must be one of: 1:1, 3:4, 4:3, 9:16, 16:9',
            'numberOfImages.max' => 'Maximum 4 images per request',
            'imageSize.in' => 'Image size must be either 1K or 2K',
        ];
    }
}