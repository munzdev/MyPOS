<?php

namespace API\Lib;

use API\Lib\Exceptions\InvalidRequestException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use const API\DEBUG;

abstract class Controller
{
    protected $app;
    protected $container;
    protected $json;
    protected $request;
    protected $response;
    protected $args;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = $app->getContainer();
    }

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

    protected function withJson($json)
    {
        $this->response = $this->response->withJson($json);
    }

    protected function any(): void
    {
    }

    protected function post(): void
    {
    }

    protected function get(): void
    {
    }

    protected function put(): void
    {
    }

    protected function delete(): void
    {
    }

    protected function head(): void
    {
    }

    protected function patch(): void
    {
    }

    protected function options(): void
    {
    }
}
