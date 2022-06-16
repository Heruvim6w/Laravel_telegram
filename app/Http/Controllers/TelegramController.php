<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use danog\MadelineProto\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TelegramController extends Controller
{
    public function start(CodeRequest $request = null)
    {
        $code = $request ? $request->validated() : null;

        if(file_exists( env('TELEGRAM_SESSION_FILE') ) ) {
            $madeline = new API( env('TELEGRAM_SESSION_FILE') );
            if ($code){
                //Завершаем авторизацию проверкой кода
                $madeline->complete_phone_login($code);
            }
        } else {
            // Иначе создать новую сессию
            $madeline = new API(env('TELEGRAM_SESSION_FILE'), [
                'app_info' => [
                    'api_id' => env('TELEGRAM_API_ID'),
                    'api_hash' => env('TELEGRAM_API_HASH'),
                ]
            ]);
            // Принудительно сохранить сессию
            $madeline->serialize();

            // Начать авторизацию по номеру мобильного телефона
            $madeline->phone_login( env('TELEGRAM_PHONE') );
        }

        return ['ststus' => 'Ok', 'message' => 'Вы авторизованы', 'errors' => 0];
    }

    public function logout()
    {
        $madeline = new API( env('TELEGRAM_SESSION_FILE') );

        $madeline->logout();
        $madeline->stop();

        array_map('unlink', glob(env('TELEGRAM_SESSION_FILE')."*"));

        return 'Вы вышли';
    }

    public function me()
    {
        $madeline = new API(env('TELEGRAM_SESSION_FILE'));

        $me = $madeline->getSelf();

        var_dump($me);
    }
}
