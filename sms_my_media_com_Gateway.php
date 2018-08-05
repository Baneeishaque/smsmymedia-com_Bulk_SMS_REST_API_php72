<?php
/**
 * Created by PhpStorm.
 * User: Srf
 * Date: 05-08-2018
 * Time: 04:14
 */

//http://smsmymedia.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=414c1ee64b350b43f864f38c8fa4f99&message=TEST%20message%20SERVICE&senderId=AACERR&routeId=1&mobileNos=8714236354,9746140150&smsContentType=english

use Monolog\Logger;

class sms_my_media_com_Gateway
{
    public function get_configuration($log_path, $AUTH_KEY, $senderId, $routeId, $smsContentType)
    {
        $config = [
            'log' => [
                'path' => $log_path,
                'level' => Logger::DEBUG
            ],
            'gateway' => 'Custom',
            'Custom' => [
                'url' => 'http://smsmymedia.com/rest/services/sendSMS/sendGroupSms?',
                'params' => [
                    'send_to_name' => 'mobileNos',
                    'msg_name' => 'message',
                    'others' => [
                        'AUTH_KEY' => $AUTH_KEY,
                        'senderId' => $senderId,
                        'routeId' => $routeId,
                        'smsContentType' => $smsContentType
                    ]
                ]
            ]
        ];

        return $config;

    }
}