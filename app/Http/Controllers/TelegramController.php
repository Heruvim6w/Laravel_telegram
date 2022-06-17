<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use App\Http\Requests\ContactRequest;
use danog\MadelineProto\API;
use Illuminate\Http\Response;

class TelegramController extends Controller
{
    const NOT_AUTHORISED = 'Вы не авторизованы';

    /**
     * Вход в Telegram
     * @param CodeRequest $request
     * @return array|null
     * @throws \Exception
     */
    public function start(CodeRequest $request): ?array
    {
        //ToDo переделать метод на нормальное получение кода (в зависимости от требований к системе)
        $data = $request->validated();

        if(file_exists( env('TELEGRAM_SESSION_FILE') ) ) {
            $madeline = new API(env('TELEGRAM_SESSION_FILE'));

            if ($data['code']){
                //Завершаем авторизацию проверкой кода
                try {
                    $madeline->complete_phone_login($data['code']);
                }
                catch (\Exception $e) {
                    return [
                        'status' => false,
                        'message' => '',
                        'errors' => $e->getMessage(),
                        Response::HTTP_EXPECTATION_FAILED
                    ];
                }
            }

            return [
                'status' => true,
                'message' => 'Вы авторизованы',
                'errors' => 0,
                Response::HTTP_OK
            ];
        }

        // Если файла сессии нет, создать новую сессию
        $madeline = new API(env('TELEGRAM_SESSION_FILE'), [
            'app_info' => [
                'api_id' => env('TELEGRAM_API_ID'),
                'api_hash' => env('TELEGRAM_API_HASH'),
            ]
        ]);
        // Принудительно сохранить сессию
        $madeline->serialize();

        // Начать авторизацию по номеру мобильного телефона
        try {
            $madeline->phone_login($data['phone']);

            return ['status' => true,
                'message' => 'Введите полученный код',
                'errors' => 0,
                Response::HTTP_OK
            ];
        }
        catch (\Exception $e) {
            return [
                'status' => false,
                'message' => '',
                'errors' => $e->getMessage(),
                Response::HTTP_EXPECTATION_FAILED
            ];
        }

    }

    /**
     * @return array
     */
    public function logout():array
    {
        if (!file_exists(env('TELEGRAM_SESSION_FILE'))) {
            return [
                'status' => false,
                'message' => '',
                'errors' => self::NOT_AUTHORISED,
                Response::HTTP_FORBIDDEN
            ];
        }

        $madeline = new API( env('TELEGRAM_SESSION_FILE') );

        $madeline->logout();
        $madeline->stop();

        array_map('unlink', glob(env('TELEGRAM_SESSION_FILE')."*"));

        return ['status' => true,
            'message' => 'Вы вышли',
            'errors' => 0,
            Response::HTTP_OK
        ];
    }

    /**
     * @return bool|array
     */
    public function me(): bool|array
    {
        if (!file_exists(env('TELEGRAM_SESSION_FILE'))) {
            return [
                'status' => false,
                'message' => '',
                'errors' => self::NOT_AUTHORISED,
                Response::HTTP_FORBIDDEN
            ];
        }
        $madeline = new API(env('TELEGRAM_SESSION_FILE'));

        $me = $madeline->getSelf();

        return $me;
    }

    /**
     * @param ContactRequest $request
     * @return array|\danog\MadelineProto\contacts
     * @throws \Exception
     */
    public function addContact(ContactRequest $request): array|\danog\MadelineProto\contacts
    {
        if (!file_exists(env('TELEGRAM_SESSION_FILE'))) {
            return [
                'status' => false,
                'message' => '',
                'errors' => self::NOT_AUTHORISED,
                Response::HTTP_FORBIDDEN
            ];
        }

        $data = $request->validated();

        $madeline = new API( env('TELEGRAM_SESSION_FILE') );
        $madeline->start();

        $inputPhoneContact = [
            '_' => 'inputPhoneContact',
            'client_id' => random_int(120, 200),
            'phone' => $data['phone'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? '',
        ];

        try {
            $updates = $madeline->contacts->importContacts(['contacts' => [$inputPhoneContact]]);
            return $updates;
        }
        catch (\Exception $e) {
            return [
                'status' => false,
                'message' => '',
                'errors' => $e->getMessage(),
                Response::HTTP_EXPECTATION_FAILED
            ];
        }
    }
}
