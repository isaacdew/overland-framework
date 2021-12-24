<?php

namespace Overland\Core;

abstract class Controller {
    
    protected function validate($input, $rules) {
        return Validator::make($input, $rules)->validate();
    }

    protected function authorize($capability) {
        if ( ! $this->can($capability) ) {
            $this->response(403);
        }
        return true;
    }

    protected function can($capability) {
        return current_user_can($capability);
    }

    protected function response($code) {
        return Response::create()->status($code);       
    }
}
