<?php
/**
 * User: zach
 * Date: 6/18/13
 * Time: 5:24 PM
 */

namespace Sherlock\Composers\Document;

use Elasticsearch\Client;
use Sherlock\common\exceptions\InvalidArgumentException;
use Sherlock\common\exceptions\RuntimeException;
use Sherlock\Facades\Document\DocumentFacade;
use Sherlock\Responses\ResponseFactory;

/**
 * Class DocumentComposer
 * @package Sherlock\Composers\Document
 */
class DocumentComposer
{
    /** @var \Elasticsearch\Client  */
    private $transport;

    /** @var \Sherlock\Responses\ResponseFactory  */
    private $responseFactory;

    /** @var  DocumentFacade */
    private $facade;

    /** @var array  */
    private $requestQueue = array();


    /**
     * @param Client          $transport
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Client $transport, ResponseFactory $responseFactory)
    {
        $this->transport       = $transport;
        $this->responseFactory = $responseFactory;

    }


    /**
     * @param DocumentFacade $facade
     */
    public function setFacade(DocumentFacade $facade)
    {
        $this->facade = $facade;
    }


    /**
     * @param array $request
     *
     * @return DocumentFacade
     */
    public function enqueueIndex($request)
    {
        $this->checkEnqueuedRequest($request);
        $request = array('index' => $request);
        return $this->enqueue($request);
    }


    /**
     * @param array $request
     *
     * @return DocumentFacade
     */
    public function enqueueDelete($request)
    {
        $this->checkEnqueuedRequest($request);
        $request = array('delete' => $request);
        return $this->enqueue($request);
    }

    /**
     * @param array $request
     *
     * @return DocumentFacade
     */
    public function enqueueGet($request)
    {
        $this->checkEnqueuedRequest($request);
        $request = array('get' => $request);
        return $this->enqueue($request);
    }


    /**
     * @param array $request
     *
     * @return DocumentFacade
     */
    public function enqueueExists($request)
    {
        $this->checkEnqueuedRequest($request);
        $request = array('exists' => $request);
        return $this->enqueue($request);
    }


    /**
     * @return array
     */
    public function execute()
    {
        $responses = array();
        if (count($this->requestQueue) === 0) {
            return $responses;
        }

        foreach ($this->requestQueue as $request) {
            $responses[] = $this->executeDocumentMethod($request);
        }

        return $responses;
    }


    /**
     * @param array $request
     *
     * @return array
     */
    private function executeDocumentMethod($request)
    {
        reset($request);
        $key   = key($request);
        $value = $request[$key];

        switch ($key) {
            case 'index':
                return $this->transport->index($value);

            case 'delete':
                return $this->transport->delete($value);

            case 'get':
                return $this->transport->get($value);

            case 'exists':
                return $this->transport->exists($value);

            default:
                return array();
        }
    }


    /**
     * @param $request
     *
     * @return DocumentFacade
     */
    private function enqueue($request)
    {
        $this->requestQueue[] = $request;
        $this->checkIfFacadeSet();
        return $this->facade;
    }


    /**
     * @param array $request
     *
     * @throws \Sherlock\common\exceptions\RuntimeException
     */
    private function checkEnqueuedRequest($request)
    {
        if (is_array($request) !== true || count($request) === 0) {
            throw new RuntimeException('Cannot enqueue an empty request.');
        }
    }


    /**
     * @throws \Sherlock\common\exceptions\RuntimeException
     */
    private function checkIfFacadeSet()
    {
        if (isset($this->facade) !== true) {
            throw new RuntimeException('Critical internal error: facade is not set. Please report this!');
        }
    }
}