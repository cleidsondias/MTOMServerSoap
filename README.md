MTOM Server Soap
==============

Some Web service APIs take as parameters files that may contain binary data.

The MTOM W3C recommendation defines how binary data can be transmitted as a binary attachment. This way it avoids the size overhead and the loss in speed of the data transmission.

SwA is a W3C Note. It was submitted as a proposal, but it was not adopted by the W3C. Instead, MTOM is the W3C Recommendation for handling binary data in SOAP messages. With the release of SOAP 1.2 additionally the note SOAP 1.2 Attachment Feature was published.

This package extends the PHP SOAP server class to be able to decode binary data attachments sent in a SOAP request using MTOM (Message Transmission Optimization Mechanism) or SwA (Soap With Attachments) and can detect binary files encoded more efficiently for transmission and decode the attached binary data for regular SOAP server handling.

This package is somewhat inspired in the BeSimple SOAP server package.

To use and very simple, just follow the steps below

1 - Add the dependencie in your projetct
```
{
  "require":
  {
    "phpclasses/mtop-soap-server": ">=1.0.8"
  },
  "repositories":
  [
    {
      "type": "composer",
      "url": "https:\/\/www.phpclasses.org\/"
    },
    {
      "packagist": false
    }
  ]
}
```

2 - On page you want to use the implementation of MTOM do the imports and necessary uses as:
```
use SERVERSOAP\SEISOAPServer;
use SERVERSOAP\implement\helper\SEISOAPhelper;
```

3 - Create the object you want to use as follows

if you want to use MTOM
```
$servidorSoap = new SEISOAPServer ( "some.wsdl", array (
'encoding'=>'ISO-8859-1',
'attachment_type' => SEISOAPhelper::ATTACHMENTS_TYPE_MTOM
) );
$servidorSoap->setClass ( "some" );
```
if you want to use the SwA
```
$servidorSoap = new SEISOAPServer ( "some.wsdl", array (
'encoding'=>'ISO-8859-1',
'attachment_type' => SEISOAPhelper::ATTACHMENTS_TYPE_SWA
) );
$servidorSoap->setClass ( "some" );
```

And finally call the object as an example below
```
$servidorSoap->handle();

```
