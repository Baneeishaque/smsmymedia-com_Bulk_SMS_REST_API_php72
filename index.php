<?php
/**
 * Created by PhpStorm.
 * User: Srf
 * Date: 05-08-2018
 * Time: 03:44
 */

require_once 'vendor/autoload.php';

use Nette\Forms\Form;
use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Stringy\StaticStringy;
use Tecdiary\Sms\Sms;

//http://smsmymedia.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=414c1ee64b350b43f864f38c8fa4f99&message=TEST%20message%20SERVICE&senderId=AACERR&routeId=1&mobileNos=8714236354,9746140150&smsContentType=english
$form = new Form;

$form->addText('AUTH_KEY', 'Authentication Key : ')
    ->setRequired('Please enter your authentication key')
    ->setDefaultValue('414c1ee64b350b43f864f38c8fa4f99');

$form->addText('message', 'Message : ')
    ->setDefaultValue('Test Message Service')
    ->setRequired('Please enter your message');

$form->addText('senderId', 'Sender ID : ')
    ->setDefaultValue('AACERR')
    ->setRequired('Please enter your sender ID');

$form->addText('routeId', 'Route ID : ')
    ->setDefaultValue('1')
    ->setHtmlType('number')
    ->setRequired('Please enter your route ID')
    ->addRule(Form::INTEGER, 'Route ID must be an integer.');

$form->addText('mobileNos', 'Receivers : ')
    ->setDefaultValue('9446827218,9895624669')
    ->setRequired('Please enter your comma separated receivers');

$form->addText('smsContentType', 'SMS Content Type : ')
    ->setRequired('Please enter sms content type')
    ->setDefaultValue('english');

$form->addSubmit('send', 'Send SMS');

echo $form; // renders the form

if ($form->isSuccess()) {

    echo 'Form was filled and submitted successfully';

    $values = $form->getValues();
    var_dump($values);

//    $config = [
//        'gateway' => 'Log',
//        'log' => [
//            'path' => __DIR__ . '/logs/sms.log',
//            'level' => 100
//        ]
//    ];
//    $sms = new Sms($config);
//    $response = $sms->send(['+919090909090', '+919090909091'], 'This is test message for Log gateway.')->response();
//    var_dump($response);

//    $result=StaticStringy::slice('fòôbàř', 0, 3);
//    var_dump($result);

    $mobileNos = StaticStringy::split($values['mobileNos'], ',');
    var_dump($mobileNos);

//    $collection = new ArrayCollection([1, 2, 3]);
//    $mappedCollection = $collection->map(function($value) {
//        return $value + 1;
//    });
//    var_dump($collection);
//    var_dump($mappedCollection);

//    $mobileNos_collection = new ArrayCollection($mobileNos);
//    $mobileNos_international_collection = $mobileNos_collection->map(function ($value) {
//        return '+91' . $value;
//    });
//    var_dump($mobileNos_collection);
//    var_dump($mobileNos_international_collection);
//
//    $mobileNos_international_collection_array = $mobileNos_international_collection->toArray();
//    var_dump($mobileNos_international_collection_array);

//    $array = ['foo', 'bar', 'baz'];
//    $res = Arrays::map($array, function ($value, $key, $arr) {
//        return $value . $value;
//    });
//    var_dump($res);

    $mobileNos_international = Arrays::map($mobileNos, function ($value, $key, $arr) {
        return '+91' . $value;
    });
    var_dump($mobileNos_international);

    $config = [
        'log' => [
            'path' => __DIR__ . '/logs/sms.log',
            'level' => 100
        ],
        'gateway' => 'Custom',
        'Custom' => [
            'url' => 'http://smsmymedia.com/rest/services/sendSMS/sendGroupSms?',
            'params' => [
                'send_to_name' => 'mobileNos',
                'msg_name' => 'message',
                'others' => [
                    'AUTH_KEY' => $values['AUTH_KEY'],
                    'senderId' => $values['senderId'],
                    'routeId' => $values['routeId'],
                    'smsContentType' => $values['smsContentType']
                ]
            ]
        ]
    ];
    $sms = new Sms($config);
    $api_response = $sms->send($mobileNos_international, $values['message'])->response();
    var_dump($api_response);

//    $expression = 'foo.*.baz';
//    $data = [
//        'foo' => [
//            'bar' => ['baz' => 1],
//            'bam' => ['baz' => 2],
//            'boo' => ['baz' => 3]
//        ]
//    ];
//    $response_result=Env::search($expression, $data);
//    var_dump($response_result);

//    $api_response_json = json_decode($api_response);
//    var_dump($api_response_json);
//    $api_response_responseCode = Env::search('responseCode', $api_response_json);
//    $api_response_response = Env::search('response', $api_response_json);
//    var_dump($api_response_responseCode);
//    var_dump($api_response_response);
//    if($api_response_responseCode=='3001')
//    {
//        echo 'SMS Send Successfully.';
//    }
//    else{
//        echo 'SMS Send Failure, Response : '.$api_response_response;
//    }

//    $serializer = SerializerBuilder::create()->build();
//    $api_response_object = $serializer->deserialize($api_response, 'sms_my_media_com_Gateway_Response', 'json');
//    var_dump($api_response_object);

//    $api_response_json = json_decode($api_response);
//    var_dump($api_response_json);
//    $mapper = new JsonMapper();
//    try {
//        $api_response_object = $mapper->map($api_response_json, new sms_my_media_com_Gateway_Response());
//        var_dump($api_response_object);
//    } catch (JsonMapper_Exception $e) {
//        echo 'Exception : ' . $e;
//    }

    // Returns an object of stdClass with attribute $variable
//    try {
//        $decoded_object = Json::decode('{"variable": true}');
//        var_dump($decoded_object);
//    } catch (\Nette\Utils\JsonException $e) {
//        echo 'Exception : ' . $e;
//    }
//
//    // Returns an array with key "variable" and value true
//    try {
//        $decoded_object_array = Json::decode('{"variable": true}', Json::FORCE_ARRAY);
//        var_dump($decoded_object_array);
//    } catch (\Nette\Utils\JsonException $e) {
//        echo 'Exception : ' . $e;
//    }

    try {
        $api_response_array = Json::decode($api_response, Json::FORCE_ARRAY);
        var_dump($api_response_array);
    } catch (\Nette\Utils\JsonException $e) {
        echo 'Exception : ' . $e;
    }
    if ($api_response_array['responseCode'] == '3001') {
        echo 'SMS Send Successfully.';
    } else {
        echo 'SMS Send Failure, Response : ' . $api_response_array['response'];
    }

//    $sms = new Sms(sms_my_media::$config);
//    $sms_my_media_com_gateway=new sms_my_media_com_Gateway();
//    $sms = new Sms($sms_my_media_com_gateway->get_configuration(__DIR__ . '/logs/sms.log',$values['AUTH_KEY'],$values['senderId'],$values['routeId'],$values['smsContentType']));


}
