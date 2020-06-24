# MTOM Server Soap

[![Latest Version](https://img.shields.io/github/v/tag/cleidsondias/mtom-server-soap.svg?style=flat-square)](https://github.com/cleidsondias/mtom-server-soap/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)


Some Web service APIs take as parameters files that may contain binary data.

The MTOM W3C recommendation defines how binary data can be transmitted as a binary attachment. This way it avoids the size overhead and the loss in speed of the data transmission.

SwA is a W3C Note. It was submitted as a proposal, but it was not adopted by the W3C. Instead, MTOM is the W3C Recommendation for handling binary data in SOAP messages. With the release of SOAP 1.2 additionally the note SOAP 1.2 Attachment Feature was published.

This package extends the PHP SOAP server class to be able to decode binary data attachments sent in a SOAP request using MTOM (Message Transmission Optimization Mechanism) or SwA (Soap With Attachments) and can detect binary files encoded more efficiently for transmission and decode the attached binary data for regular SOAP server handling.

This package is somewhat inspired in the BeSimple SOAP server package.

To use and very simple, just follow the steps below

## Install

Via Composer

``` bash
$ composer require phpclasses/mtop-soap-server
```

## Usage

``` php
use SERVERSOAP\SERVERSoap;
use SERVERSOAP\implement\helper\SOAPhelper;
```

if you want to use MTOM
``` php
$servidorSoap = new SERVERSoap ( "some.wsdl", array (
'encoding'=>'ISO-8859-1',
'attachment_type' => SOAPhelper::ATTACHMENTS_TYPE_MTOM
) );
$servidorSoap->setClass ( "some" );
$servidorSoap->handle();
```

if you want to use the SwA
``` php
$servidorSoap = new SERVERSoap ( "some.wsdl", array (
'encoding'=>'ISO-8859-1',
'attachment_type' => SOAPhelper::ATTACHMENTS_TYPE_SWA
) );
$servidorSoap->setClass ( "some" );
$servidorSoap->handle();
```
