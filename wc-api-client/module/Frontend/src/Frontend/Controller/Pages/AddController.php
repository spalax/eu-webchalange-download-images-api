<?php
namespace Frontend\Controller\Pages;

use Frontend\API\Rest\Resources\Exception\ApiException;
use Frontend\API\Rest\Resources\PagesResource;
use Frontend\Controller\AbstractController;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\MvcEvent;

class AddController extends AbstractController
{
    /**
     * @var PagesResource
     */
    protected $pagesResource;

    /**
     * @var InputFilter
     */
    protected $inputFilter;

    /**
     * @param PagesResource $pagesResource
     * @param InputFilterFactory $inputFilterFactory
     */
    public function __construct(PagesResource $pagesResource, InputFilterFactory $inputFilterFactory)
    {
        $this->pagesResource = $pagesResource;
        $this->inputFilterFactory = $inputFilterFactory;
        $this->inputFilter = $this->inputFilterFactory->createInputFilter([
            [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'Uri',
                        'options' => [
                            'allowRelative' => false,
                        ],
                    ],
                ],
                'name' => 'site_url',
                'error_message' => 'Url is incorrect',
            ]
        ]);
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->inputFilter->setData($this->params()->fromPost());

        if (!$this->inputFilter->isValid()) {
            $this->flashMessenger()
                 ->addErrorMessage($this->inputFilter->getMessages());
            return $this->redirect()->toRoute('frontend');
        }

        try {
            $this->pagesResource->download($this->inputFilter->getValue('site_url'));
            $this->flashMessenger()->addSuccessMessage('Url successfully queued for download all images');
        } catch (ApiException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        $this->redirect()->toRoute('frontend');
    }
}
