<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Presenters\JwtPresenter;
use RA\Auth\Validators\ResetPasswordValidator as Validator;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class ResetPasswordAction extends Action
{
    public function run(Request $request) {
        $data = $request->all();

        //validate
        $validation = Validator::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $item = ClassName::Model()::find($data['id']);
        $item->update([
            'password' => app('hash')->make($data['password']),
            'reset_code' => null,
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);

        $item = ClassName::Presenter()::run($item);

        //create jwt token
        $jwt_token = config('ra-auth.login_strategy') == 'jwt' ? Jwt::generate(JwtPresenter::run(clone $item)) : '';

        return Response::success(compact('item', 'jwt_token'));
    }
}
