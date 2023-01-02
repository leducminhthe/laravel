<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;
use Orion\Http\Requests\Request;

class NoteRequest extends Request
{
   public function commonRules(): array
   {
       return [
           'content' => 'required',
           'user_id' => 'required|exists:el_profile,user_id',
           'type' => 'required',
       ];
   }
}
