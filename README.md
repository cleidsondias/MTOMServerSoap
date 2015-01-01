MTOMServerSoap
==============

This package can read binary files sent in SOAP requests using MTOM (Message Transmission Optimization Mechanism).

It can detect binary files encoded more efficiently for transmission using the SOAP MTOM recommendation and decode the attached binary data for regular SOAP server handling.

This package is somewhat inspired in the BeSimple SOAP server package.

To use and very simple, just follow the steps below

1 - Copy the implementation to a subfolder in your project called serversoap

2 - On page you want to use the implementation of MTOM do the imports and necessary uses as:
```
require_once 'seisoap/SEISOAPServer.php';
require_once 'serversoap/implements/helper/SEISOAPhelper.php';

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
// Only process if accessed via POST
if ($_SERVER['REQUEST_METHOD']=='POST') {	
$servidorSoap->handle($HTTP_RAW_POST_DATA);
}
```
