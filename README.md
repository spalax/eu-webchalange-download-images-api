# eu-webchalange-download-images-api
Solution which includes server and client parts. It is provide scalable and asynchronous possibilities to download all images from target web page, and store it locally or on AWS-S3.

#### Description
This is client and server as two independent virtual hosts, 
both of them are using Zend Framework. Server built on top of Zend Framework 2, and API interface implemented with Apigility, client built on few ZF2 modules. Server provide responses in JSON HAL format (thanks to Apigility). Client not HAL aware, but it is work with server as with just another REST API. 
Client provide you possibilities to put desired url to the queue, and all images will be downloaded. You will see
the progress on interface, and will be able to review all downloaded images.

#### Thoughts

I really do not know, where it is might be usefull, 
but sometimes, i guess, you need to download all images from 
destination web page. Maybe you will like to extend this api,
and it is will download images recursivelly by walking from page to page
by hyperlinks, or download images not only from img tags, but from CSS as well.
And if you will be really freaky you might download all images inserted by JS.

#### How to install
You need Vagrant >= 1.6
```
vagrant up
```
client and server will be available in your private network.

Client available by this link: http://192.168.56.101:8091

Server API available by this address: http://192.168.56.101:8090
