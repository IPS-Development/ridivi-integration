<?php

namespace IPS\Integration\Ridivi;

class Moneda {
    const dolones = 'CRC';
    const dolares = 'USD';
    const euros = 'EUR';
}

class TipoIdentificacion {
    const cedulaNacional = 0;
    const DIMEX = 1;
    const gobierno = 2;
    const intitucionesAutonomas = 4;
    const DIDI = 5;
    const pasaporte = 9;
}

class TipoEmpresa{
    const propietarioUnico = 0;
    const sociedad = 1;
    const sinFinesDeSinLucro = 2;
    const sociedadLimitada = 3;
    const sociedadAnonima = 4;
}

class TipoTransferencia {
    const CCD = 'CCD';
    const CDD = 'CDD';
    const DTR = 'DTR';
    const TFT = 'TFT';
    const PIN = 'PIN';
}

class Options {
    const getKey = 'getKey';
    const releaseKey = 'releaseKey';
    const checkKey = 'checkKey';
    const getUser = 'getUser';
    const getAccount = 'getAccount';
    const getIbanData = 'getIbanData';
    const getADAs = 'getADAs';
    const getFee = 'getFee';
    const getHistory = 'getHistory';
    const getHistoryDetail = 'getHistoryDetail';
    const newUser = 'newUser';
    const newCompany = 'newCompany';
    const newAccount = 'newAccount';
    const uploadFiles = 'uploadFiles';
    const newADA = 'newADA';
    const uploadFile = 'uploadFile';
    const loadTransfer = 'loadTransfer';
    const sendLoadedTransfer = 'sendLoadedTransfer';
    const getLoadedTransfer = 'getLoadedTransfer';
    const updateProfileInfo = 'updateProfileInfo';
    const getExchange= 'getExchange';
    const getAccountData = 'getAccountData';
    const insertFavoriteAccount = 'insertFavoriteAccount';
    const getFavoriteAccounts = 'getFavoriteAccounts';
    const updateFavoriteAccount = 'updateFavoriteAccount';
    const deleteFavoriteAccount = 'deleteFavoriteAccount';
}

class FileKey {
    const myID = 'myID';
    const myHouse = 'myHouse';
    const myFunding = 'myFunding';
    const myFormalID = 'myFormalID';
    const myTaxes = 'myTaxes';
    const cmpOffice = 'cmpOffice';
    const mpIncome = 'mpIncome';
    const cmpRegistration = 'cmpRegistration';
}

