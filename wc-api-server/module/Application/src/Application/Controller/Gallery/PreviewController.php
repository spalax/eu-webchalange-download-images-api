<?php
namespace Application\Controller\Gallery;

use Application\Data\Gallery\ConfigurationData;
use Application\Service\Instagram\AuthenticationService;
use Application\Service\Instagram\Images\CollectionFactory;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\ViewModel;

class PreviewController extends AbstractController
{
    /**
     * @var AuthenticationService
     */
    protected $authenticationService = null;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory = null;

    /**
     * @var ConfigurationData
     */
    protected $configurationData = null;

    /**
     * @var SessionContainer
     */
    protected $sessionContainer = null;

    /**
     * @param AuthenticationService $authenticationService
     * @param CollectionFactory $collectionFactory
     * @param SessionContainer $sessionContainer
     * @param ConfigurationData $configurationData
     */
    public function __construct(AuthenticationService $authenticationService,
                                CollectionFactory $collectionFactory,
                                SessionContainer $sessionContainer,
                                ConfigurationData $configurationData)
    {
        $this->authenticationService = $authenticationService;
        $this->collectionFactory = $collectionFactory;
        $this->sessionContainer = $sessionContainer;
        $this->configurationData = $configurationData;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $e)
    {
        $viewModel = new ViewModel();

        $data = $this->authenticationService->getAuthData();
        $viewModel->setVariable('user', $data->user);
        $valid = $this->configurationData->isValid();
        $viewModel->setVariable('validInputs', $this->configurationData->getValidInput());

        if (!$valid) {
            $viewModel->setTemplate('application/gallery/error');
            $viewModel->setVariable('messages', $this->configurationData->getMessages());
            return $e->setResult($viewModel);
        }

        $collectionService = $this->collectionFactory->createCollection($this->configurationData);
        $images = $collectionService->getImages($this->configurationData);
        $viewModel->setVariable('images', $images);

        $this->sessionContainer->images = $images;
        $this->sessionContainer->valuesHash = md5(serialize($this->configurationData->getValues()));

        $viewModel->setTemplate('application/gallery/preview');

        return $e->setResult($viewModel);
    }
}
