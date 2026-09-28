<?php
namespace Lareon\Modules\Fence\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lareon\Modules\Fence\App\Models\Fence;

class NewIpRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.setting.edit','admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return Fence::rules();
    }



    public function after(): array
    {
        return [
            fn(Validator $validator) =>'dsfsf'
        ];
    }

    private function uniqueIpAddressRule(bool $isFileStorage,)
    {
        if ($isFileStorage) {
            $ips = $this->loadFile();
            return Rule::notIn($ips[$this->type]);
        }

        return Rule::unique(Fence::class, 'ip_address');
    }

    /**
     * Load IP addresses from file storage.
     *
     * @return array
     * @throws \RuntimeException
     */
    private function loadFile(): array
    {
        $filePath = storage_path('app/private/ip_address.php');

        if (!file_exists($filePath)) {
            return [];
        }
        return require $filePath;
    }
}
