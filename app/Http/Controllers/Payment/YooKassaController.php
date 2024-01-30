<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Jobs\CheckPaymentStatusJob;
use App\Jobs\CheckPayoutStatusJob;
use App\Models\Transaction;
use Illuminate\Http\Request;
use YooKassa\Client;

class YooKassaController extends Controller
{
    public function createPayment(Request $request)
    {
        $client = new Client();
        $client->setAuth(config('services.yookassa.client_id'), config('services.yookassa.client_secret'));

        try {
            $builder = \YooKassa\Request\Payments\CreatePaymentRequest::builder();
            $builder->setAmount($request->input('amount'))
                ->setCurrency(\YooKassa\Model\CurrencyCode::RUB)
                ->setCapture(true)
                ->setDescription('Пополнение счёта на ' . $request->input('amount') .  ' руб')
                ->setMetadata([
                    'cms_name'       => 'tgteka',
                    'order_id'       => '112233',
                    'language'       => 'ru',
                    'transaction_id' => '123-456-789',
                ]);

            // Устанавливаем страницу для редиректа после оплаты
            $builder->setConfirmation([
                'type'      => \YooKassa\Model\Payment\ConfirmationType::REDIRECT,
                'returnUrl' => route('replenishment'),
            ]);

            // Можем установить конкретный способ оплаты

            // Составляем чек
            $builder->setReceiptEmail(auth()->user()->email);
            $builder->setReceiptPhone(auth()->user()->mobile_number);

            // Создаем объект запроса
            $request = $builder->build();

            // Можно изменить данные, если нужно
            $request->setDescription($request->getDescription());

            $idempotenceKey = uniqid('', true);
            $response = $client->createPayment($request, $idempotenceKey);



            $payment  = Transaction::create([
                'user_id' => auth()->id(),
                'transaction_id' => $response->getId(),
                'amount' => $request->amount->getValue(),
                'appointment' => 'replenishment'
            ]);

            CheckPaymentStatusJob::dispatch($payment);

            //получаем confirmationUrl для дальнейшего редиректа
            $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
            return response()->json([$confirmationUrl,  $response->getId()]);
        } catch (\Exception $e) {
            $response = $e;
        }
        return response()->json($response);
    }

}
