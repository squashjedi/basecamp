<?php

namespace Squashjedi\Basecamp\App\Traits;

use Validator;
use Illuminate\Support\Facades\Response;

trait Validation
{
	public function validation($form, $request)
	{
	    $validator = Validator::make($request->all(), $form->rules(), $form->messages());
	    if ($validator->fails()) {
	        return Response::json([
	                'errors' => $validator->getMessageBag()->toArray()
	            ]);
	    }
	}	
}

