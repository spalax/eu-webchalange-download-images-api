<?php
return [
    'Application\\V1\\Rest\\Pages\\Controller' => [
        'description' => 'Pages queue',
        'collection' => [
            'description' => 'Collection of all ever queued pages',
            'GET' => [
                'description' => 'Fetch all queued pages',
                'response' => '{
   "_links": {
       "self": {
           "href": "/pages"
       },
       "first": {
           "href": "/pages?page={page}"
       },
       "prev": {
           "href": "/pages?page={page}"
       },
       "next": {
           "href": "/pages?page={page}"
       },
       "last": {
           "href": "/pages?page={page}"
       }
   }
   "_embedded": {
       "pages": [
           {
               "_links": {
                   "self": {
                       "href": "/pages[/:page_id]"
                   }
               }
              "site_url": "Url where you want worker to download all DOM images. Url must be in format scheme://[path, user, password].[tld] on example http://rambler.ru is correct but rambler.ru is incorrect"
           }
       ]
   }
}',
            ],
            'POST' => [
                'description' => 'Add new page to the queue',
                'request' => '{
   "site_url": "Url where you want worker to download all DOM images. Url must be in format scheme://[path, user, password].[tld] on example http://rambler.ru is correct but rambler.ru is incorrect"
}',
                'response' => '{
   "_links": {
       "self": {
           "href": "/pages[/:page_id]"
       }
   }
   "site_url": "Url where you want worker to download all DOM images. Url must be in format scheme://[path, user, password].[tld] on example http://rambler.ru is correct but rambler.ru is incorrect"
}',
            ],
        ],
        'entity' => [
            'description' => 'Entity of the page',
            'GET' => [
                'description' => 'Get information about queued page',
                'response' => '{
   "_links": {
       "self": {
           "href": "/pages[/:page_id]"
       }
   }
   "site_url": "Url where you want worker to download all DOM images. Url must be in format scheme://[path, user, password].[tld] on example http://rambler.ru is correct but rambler.ru is incorrect"
}',
            ]
        ],
    ],
    'Application\\V1\\Rest\\Images\\Controller' => [
        'description' => 'Download page images',
        'collection' => [
            'description' => 'Retrieve downloaded images of the page as a list, with details of every image (links to remote and local file path, width, height, size, content-type]',
            'GET' => [
                'description' => 'Retrieve images of the page as a list',
                'response' => '{
   "_links": {
       "self": {
           "href": "/pages/:page_id/images"
       },
       "first": {
           "href": "/pages/:page_id/images?page={page}"
       },
       "prev": {
           "href": "/pages/:page_id/images?page={page}"
       },
       "next": {
           "href": "/pages/:page_id/images?page={page}"
       },
       "last": {
           "href": "/pages/:page_id/images?page={page}"
       }
   }
   "_embedded": {
       "images": [
           {
               "_links": {
                   "self": {
                       "href": "/pages/:page_id/images"
                   }
               }

           }
       ]
   }
}',
            ],
        ],
    ],
];
