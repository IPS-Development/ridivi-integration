<?php

namespace IPS\Integration\Ridivi;

use IPS\Common\Services\BusinessPartnerService;
use IPS\Integration\Ridivi\Exceptions\RidiviException;
use Unirest\Request;
use Unirest\Request\Body;

class RidiviIntegrationService extends BusinessPartnerService
{

    public function __construct()
    {
        parent::__construct("ridivi");
        //init api client
    }

    private function httpPost($url, $headers, $payload, &$status_code, $format_output = false)
    {
        $result = false;
        $response = Request::post($url, $headers, $payload);
        $status_code = $response->code;
        if ($format_output) {
            $result = json_decode($response->raw_body, true);
            if (!$result) {
                $result = $this->buildBasicErrorResult();
            }
        } else {
            $result - $response->raw_body;
        }
        return $result;
    }

    private function buildBasicErrorResult()
    {
        return [
            'error' => TRUE,
            'messsage' => 'Unknown internal server error'
        ];
    }

    public function getKey($settings)
    {
        $payload = [
            'option' => Options::getKey,
            'userName' => $this->getProperty(['api_settings', 'username'], $settings),
            'password' => hash('sha1', $this->getProperty(['api_settings', 'password'], $settings), false)
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getKey, $output['message']);

        return $output['key'];
    }

    public function getProperty(array $needle, array $where)
    {
        $result = $needle != null && count($needle) > 0 && $where != null && count($where) > 0 && array_key_exists($needle[0], $where) ? $where[$needle[0]] : null;
        if ($result != null && count($needle) > 1) {
            $result = self::getProperty(array_slice($needle, 1), $result);
        }
        return $result;

    }


    public function releaseKey($key, $settings)
    {
        if ($key != null && strlen($key) > 0) {

            $payload = [
                'option' => Options::releaseKey,
                'key' => $key
            ];
            $statusCode = -1;
            $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
            if ($output['error'] == TRUE)
                throw new RidiviException(Options::releaseKey, $output['message']);
        }
    }

    public function checkKey($key, $settings)
    {
        if ($key == null || strlen($key) == 0)
            throw new RidiviException(Options::checkKey, 'Null or empty key');

        $payload = [
            'option' => Options::checkKey,
            'key' => $key
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);

        if ($output['error'] == TRUE)
            throw new RidiviException(Options::checkKey, $output['message']);

        return $output['data'];
    }

    public function getUser($idNumber)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getUser,
            'key' => $key,
            'idNumber' => $idNumber
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getUser, $output['message']);

        return $output['user'];
    }

    public function getAccount($iban)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getAccount,
            'key' => $key,
            'iban' => $iban
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getAccount, $output['message']);

        return $output['account'];
    }

    public function getIbanData($iban)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getIbanData,
            'key' => $key,
            'iban' => $iban
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getIbanData, $output['message']);

        return $output['account'];
    }

    public function getADAs($idNumber, $iban)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getADAs,
            'key' => $key,
            'idNumber' => $idNumber,
            'iban' => $iban
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getADAs, $output['message']);

        return $output['account'];
    }

    public function getFee($typeName, $currency, $sourceAccount, $destAccount)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getFee,
            'key' => $key,
            'typeName' => $typeName,
            'currency' => $currency,
            'sourceAccount' => $sourceAccount,
            'destAccount' => $destAccount
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getFee, $output['message']);

        return ROUND($output['fee'], 6, PHP_ROUND_HALF_UP);
    }

    public function getHistory($iban, DateTime $from, DateTime $to, int $pageNumber = 0)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getHistory,
            'key' => $key,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'iban' => $iban,
            'pageNumber' => $pageNumber
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getHistory, $output['message']);

        return $output['transfers'];
    }

    public function getHistoryDetail($iban, int $movementNumber)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getHistoryDetail,
            'key' => $key,
            'iban' => $iban,
            'movementNumber' => $movementNumber
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getHistoryDetail, $output['message']);

        return $output['transfer'];
    }

    public function newUser($input)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::newUser,
            'key' => $key,
            'NewidNumber' => $input['idNumber'],
            'idType' => $input['idType'],
            'idLocality' => $input['idLocality'],
            'idExpDate' => $input['idExpDate']->format('d/m/Y'),
            'firstName' => $input['firstName'],
            'lastName' => $input['lastName'],
            'nationality' => $input['nationality'],
            'NewPassword' => $input['password'],
            'phone' => $input['phone'],
            'email' => $input['email']
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::newUser, $output['message']);

        return $output['user'];
    }

    public function newCompany($input)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::newCompany,
            'key' => $key,
            "NewPassword" => $input['password'],
            "Email" => $input['email'],
            "regOn" => $input['regOn']->format("Y-m-d"),
            "regLocation" => $input['regLocation'],
            "idNumber" => $input['idNumber'],
            "Name" => $input['nombre'],
            "Type" => "Individual",
            "Phone" => $input['phone'],
            "companyAddress" => $input['companyAddress'],
            "responsibleID" => $input['responsibleID'],
            "responsibleName" => $input['responsibleName'],
            "regAddress" => $input['regAddress'],
            "regCountry" => $input['regCountry']
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::newCompany, $output['message']);
    }

    public function newAccount($idNumber, $currency, $description)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::newAccount,
            'key' => $key,
            'idNumber' => $idNumber,
            'cur' => $currency,
            'name' => $description
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::newAccount, $output['message']);

        return $output['user'];
    }

    public function uploadFiles($name, $contentBase64)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::uploadFiles,
            'key' => $key,
            'name' => $name,
            'contend' => $contentBase64
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::uploadFiles, $output['message']);
    }

    public function newADA($input)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::newADA,
            'key' => $key,
            'favoriteAccountId' => '',
            'fileName' => $input['fileName'],
            'idNUmber' => $input['idNumber'],
            'cur' => $input['currency'],
            'service' => $input['service'],
            'maxAmount' => $input['maxAmount'],
            'endsOn' => $input['endsOn']->format('Y-m-d'),
            'description' => $input['description'],
            'to' => [
                'idNumber' => $input['destIdNumber'],
                'iban' => $input['destIban'],
                'name' => $input['destName']
            ],
            'from' => [
                'idNumber' => $input['sourceIdNumber'],
                'iban' => $input['sourceIban'],
                'name' => $input['sourceName']
            ]
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::newADA, $output['message']);
    }

    public function uploadFile($idNumber, $fileName, $fileKey, $fileBase64)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::uploadFile,
            'key' => $key,
            'idNumber' => $idNumber,
            'fileName' => $fileName,
            'fileKey' => $fileKey,
            'files' => $fileBase64
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::uploadFile, $output['message']);
    }


    public function loadTransfer($input)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::loadTransfer,
            'key' => $key,
            'time' => $input['time'],
            'cur' => $input['currency'],
            'idNUmber' => $input['idNumber'],
            'cur' => $input['currency'],
            'from' => [
                'id' => $input['sourceIdNumber'],
                'iban' => $input['sourceIban'],
                'name' => $input['sourceName']
            ],
            'to' => [
                'id' => $input['destIdNumber'],
                'iban' => $input['destIban'],
                'name' => $input['destName']
            ],
            'fee' => [
                'id' => $input['feeIdNumber'],
                'iban' => $input['feeIban'],
                'name' => $input['feeName']
            ],
            'amount' => $input['amount'],
            'text' => $input['description'],
            'service' => $input['service'],
            'reference' => $input['reference']
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::loadTransfer, $output['message']);

        return $output['loadKey'];
    }

    public function sendLoadedTransfer($loadKey)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::sendLoadedTransfer,
            'key' => $key,
            'loadKey' => $loadKey
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::sendLoadedTransfer, $output['message']);

        return $output['send'];
    }

    public function getLoadedTransfer($loadKey)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getLoadedTransfer,
            'key' => $key,
            'loadKey' => $loadKey
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getLoadedTransfer, $output['message']);

        return $output;
    }

    public function updateProfileInfo($input)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::updateProfileInfo,
            'key' => $key,
            'idNumber' =>  $input['idNumber'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'firstName'=> $input['firstName'],
            'lastName' => $input['lastName'],
            'nationality' => $input['nationality'],
            'idLocality' => $input['idLocality'],
            'idExpirationDate' => $input['idExpirationDate']->format('d/m/Y')
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::updateProfileInfo, $output['message']);
    }

    public function getExchange()
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getExchange,
            'key' => $key
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getExchange, $output['message']);

        return $output;
    }

    public function getAccountData($idNumber, $iban)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getAccountData,
            'key' => $key,
            'idNumber' => $idNumber,
            'iban' => $iban
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getAccountData, $output['message']);

        return $output['account'];
    }

    public function insertFavoriteAccount($input)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::insertFavoriteAccount,
            'key' => $key,
            'userIdNumber' => $input['userIdNumber'],
            'name' => $input['ownerName'],
            'idNumber' => $input['ownerIdNumber'],
            'iban' => $input['iban'],
            'currency' => $input['currency']
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::insertFavoriteAccount, $output['message']);
    }

    public function getFavoriteAccounts($idNumber)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::getFavoriteAccounts,
            'key' => $key,
            'idNumber' => $idNumber
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::getFavoriteAccounts, $output['message']);

        return $output['favoriteAccounts'];
    }

    public function updateFavoriteAccount($input)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::updateFavoriteAccount,
            'key' => $key,
            'favoriteAccountId' => $input['favoriteAccountId'],
            'userIdNumber' => $input['userIdNumber'],
            'name' => $input['ownerName'],
            'idNumber' => $input['ownerIdNumber'],
            'iban' => $input['iban'],
            'currency' => $input['currency']
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::updateFavoriteAccount, $output['message']);
    }

    public function deleteFavoriteAccount($favoriteAccountId, $userIdNumber)
    {
        $settings = self::getSettings();
        $key = $this->getKey($settings);
        $payload = [
            'option' => Options::deleteFavoriteAccount,
            'key' => $key,
            'favoriteAccountId' => $favoriteAccountId,
            'idNumber' => $userIdNumber,
        ];
        $statusCode = -1;
        $output = $this->httpPost($this->getProperty(['api_settings', 'api_context'], $settings), ['Content-Type', 'application/json'], $payload, $statusCode, true);
        if ($output['error'] == TRUE)
            throw new RidiviException(Options::deleteFavoriteAccount, $output['message']);
    }


}