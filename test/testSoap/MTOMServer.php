<?php
	
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/Fixtures/AttachmentType.php';
    require __DIR__ . '/Fixtures/AttachmentRequest.php';

    use SERVERSOAP\SERVERSoap;
    use SERVERSOAP\implement\helper\SOAPhelper;
    
    use Fixtures\AttachmentRequest;

    $options = array(
            'soap_version'    => SOAP_1_1,
            'attachment_type' => SOAPhelper::ATTACHMENTS_TYPE_MTOM
    );

    class Mtom{
        public function attachment(AttachmentRequest $attachment){
            $b64 = $attachment->binaryData;
            file_put_contents(__DIR__.'/'.$attachment->fileName, $b64->_);
            return 'File saved succesfully.';
        }
    }

    $ss = new SERVERSoap(__DIR__.'/MTOM.wsdl', $options);
    $ss->setClass('Mtom');
    $ss->handle();