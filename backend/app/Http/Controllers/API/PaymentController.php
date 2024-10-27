<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use PHPUnit\Logging\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Model\Notification\NotificationSucceeded;

class PaymentController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @throws NotFoundException
     * @throws ApiException
     * @throws ResponseProcessingException
     * @throws BadApiRequestException
     * @throws ExtensionNotFoundException
     * @throws AuthorizeException
     * @throws InternalServerError
     * @throws ForbiddenException
     * @throws TooManyRequestsException
     * @throws AuthenticationException
     * @throws UnauthorizedException
     * @throws ApiConnectionException
     */
    public function test(Request $request)
    {
        $subscriptionType = $request->input('subscriptionType') ?? 1;
        $tariff = $this->getTariff($subscriptionType);


        $client = new Client();
        $client->setAuth('350524', 'live_rahgTIcvDUz0av5GOkYu2DZl6yxuApM9glNKTNMD8u8');
        $payment = $client->createPayment(
            array(
                'amount' => array(
                    'value' => $tariff['cost'],
                    'currency' => 'RUB',
                ),
                'confirmation' => array(
                    'type' => 'redirect',
                    'return_url' => 'https://на-связи.com',
                ),
                'capture' => true,
                'description' => $tariff['name'],
            ),
            uniqid('', true)
        );

        /** @var User $user */
        $user = $request->user();
        $orderId = $payment->getId();

        if (is_null($user)) {
            throw new AuthenticationException();
        }

        $user->update(['order_id' => $orderId]);

        return $payment;
    }

    public function handlePayment(Request $request)
    {
        $content = $request->getContent();
        $requestBody = json_decode($content, true);

        try {
            $notification = ($requestBody['event'] === 'payment.succeeded')
                ? new NotificationSucceeded($requestBody)
                : null;

            if (is_null($notification)) {
                throw new Exception("Статус заказа не успешный $content");
            }

            $orderId = $notification->getObject()->getId();

            $user = User::query()->where('order_id', $orderId);

            if ($user) {
                $user->update(['has_subscription' => 1]);
            } else {
                throw new Exception("Такой пользователь не был найден для заказа $orderId");
            }

        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        return new Response();
    }

    private function getTariff($type)
    {
        $tariff = [
            'name' => '',
            'cost' => 0
        ];
        switch ($type){
            case 1:
                $tariff['name'] = 'Партнер';
                $tariff['cost'] = 19000;
                break;
            case 2:
                $tariff['name'] = 'Под ключ';
                $tariff['cost'] = 24000;
                break;
            default:
                throw new InvalidArgumentException();
        }
        return $tariff;
    }

}
