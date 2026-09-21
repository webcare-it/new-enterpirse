<?php

namespace App\Services;

use App\Mail\EmailManager;
use App\Mail\InvoiceEmailManager;
use Illuminate\Support\Facades\Mail;

class MailService
{
    // Order confirmation to customer
    public static function order_placed_customer($order = '')
    {
        if (!$order || !filter_var($order->email_address, FILTER_VALIDATE_EMAIL)) return false;

        return self::sendInvoice($order->email_address, $order, translate('Your order has been placed') . ' - ' . $order->code, 'customer');
    }

    // New order notification to admin
    public static function order_receive($order = '')
    {
        $adminEmail = get_setting('order_notification_email');
        if (!$order || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) return false;

        return self::sendInvoice($adminEmail, $order, translate('New order received') . ' - ' . $order->code, 'admin');
    }

    public static function test_email($email = '')
    {
        $array = [
            'view'    => 'emails.test',
            'subject' => translate('SMTP Test'),
            'from'    => env('MAIL_FROM_ADDRESS'),
        ];

        Mail::to($email)->send(new EmailManager($array));
    }

    protected static function sendInvoice($email, $order, $subject, $recipient)
    {
        $order->loadMissing('details.product');

        $array = [
            'view'      => 'emails.order_placed',
            'subject'   => $subject,
            'from'      => env('MAIL_FROM_ADDRESS'),
            'order'     => $order,
            'recipient' => $recipient,
        ];

        try {
            Mail::to($email)->send(new InvoiceEmailManager($array));
            return true;
        } catch (\Exception $e) {
            \Log::error('Mail order_placed (' . $recipient . ') failed: ' . $e->getMessage());
            return false;
        }
    }
}
