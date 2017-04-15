<?php

namespace API\Lib;

use API\Lib\Exceptions\InvalidRequestException;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use const API\DEBUG;

abstract class Controller
{
    /**
     *
     * @var App
     */
    protected $app;

    /**
     *
     * @var Container
     */
    protected $container;

    /**
     *
     * @var array
     */
    protected $json;

    /**
     *
     * @var Request
     */
    protected $request;

    /**
     *
     * @var Response
     */
    protected $response;

    /**
     *
     * @var array
     */
    protected $args;

    /**
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
    }

    /**
     * Dispatches Controller request based on request type and calls class method to handle request
     *
     * @param Request $request
     * @param Response $response
     * @param type $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            $this->json = $request->getParsedBody();

            $this->any($request, $response, $args);

            if ($request->isGet()) {
                $this->get();
            } elseif ($request->isPost()) {
                $this->post();
            } elseif ($request->isPut()) {
                $this->put();
            } elseif ($request->isDelete()) {
                $this->delete();
            } elseif ($request->isHead()) {
                $this->head();
            } elseif ($request->isPatch()) {
                $this->patch();
            } elseif ($request->isOptions()) {
                $this->options();
            }
        } catch (InvalidRequestException $exception) {
            $this->generateJSONErrorFromException($exception, 400);
        } catch (\Exception $exception) {
            $this->generateJSONErrorFromException($exception, 500);
        }

        return $this->response;
    }

    /**
     * Generates from an Exception and JSON response
     *
     *
     * @param \Exception $exception
     * @param int $statusCode
     * @return void
     */
    private function generateJSONErrorFromException(\Exception $exception, int $statusCode): void
    {
        $result = array('status' => $statusCode,
            'code' => $exception->getCode(),
            'detail' => get_class($exception) . ': ' .
                        $exception->getMessage() . ' in ' .
                        $exception->getFile() . ':' .
                        $exception->getLine());

        if (DEBUG) {
            $result['trace'] = (array) $exception->getTrace();
        }

        $this->response = $this->response->withJson($result, $statusCode);
    }

    /**
     * Removes user sensible data from IUser Array
     * TODO: correct place here??
     *
     * @param array $user
     * @return type
     */
    protected function cleanupUserData(array $user)
    {
        $user['Password'] = null;
        $user['AutologinHash'] = null;
        $user['IsAdmin'] = null;
        $user['CallRequest'] = null;

        if (isset($user['EventUser'])) {
            $user['EventUser']['BeginMoney'] = null;
        }

        return $user;
    }

    /**
     * Converts response to JSON response object
     *
     * @param type $json
     */
    protected function withJson($json)
    {
        $this->response = $this->response->withJson($json);
    }

    /**
     * Gets called on any request to the controller
     *
     * @return void
     */
    protected function any(): void
    {
    }

    /**
     * Gets called on a POST request
     *
     * @return void
     */
    protected function post(): void
    {
    }

    /**
     * Gets called on a GET request
     *
     * @return void
     */
    protected function get(): void
    {
    }

    /**
     * Gets called on a PUT request
     *
     * @return void
     */
    protected function put(): void
    {
    }

    /**
     * Gets called on a DELETE request
     *
     * @return void
     */
    protected function delete(): void
    {
    }

    /**
     * Gets called on a HEAD request
     *
     * @return void
     */
    protected function head(): void
    {
    }

    /**
     * Gets called on a PATCH request
     *
     * @return void
     */
    protected function patch(): void
    {
    }

    /**
     * Gets called on a OPTIONS request
     *
     * @return void
     */
    protected function options(): void
    {
    }
}
