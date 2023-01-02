<?php

namespace App\Http\Requests\ForumCategoryPermission;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class ForumCategoryPermissionRequest extends Request
{
    public function commonRules(): array
    {
        return [
            'forum_cate_id' => 'required|exists:el_forum_category,id',
            'unit_id' => 'nullable|exists:el_unit,id',
            'user_id' => 'nullable|exists:el_profile,user_id',
        ];
    }
}
