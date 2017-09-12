<?php


namespace App\Http\Api;

use App\Http\Controller;
use Illuminate\Http\Response;


/**
 * Class ApiController
 * @package App\Http\Api
 */
class ApiController extends Controller
{

    protected $statusCode = Response::HTTP_OK;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $resource
     * @return mixed
     */
    public function respondWithSuccess($resource = [])
    {
        return $this->respond($resource);
    }

    /**
     * @param $message
     * @return mixed
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'errors' => [
                'status' => $this->getStatusCode(),
                'errors' => $message
            ]
        ]);
    }

    /**
     * @return mixed
     * @param $message
     */
    public function respondUnauthorized($message = 'The requested resource failed authorization')
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($message = 'The requested resource could not be found')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }


    /**
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($message = 'An internal server error has occurred')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function respondUnprocessableEntity($message = 'The request cannot be processed with the given parameters')
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }


    /**
     * @param array $resource
     * @return mixed
     */
    public function respondCreated($resource = [])
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respondWithSuccess($resource);
    }


    /**
     * @param array $resource
     * @return mixed
     */
    public function respondUpdated($resource = [])
    {
        return $this->setStatusCode(Response::HTTP_OK)->respondWithSuccess($resource);
    }

    /**
     * @return mixed
     */
    public function respondNoContent()
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)->respondWithSuccess();
    }

    /**
     * @param null $message
     * @return mixed
     */
    public function respondHttpConflict($message = null)
    {
        return $this->setStatusCode(Response::HTTP_CONFLICT)->respondWithError($message);
    }


    //Base Documentation
    /**
     * @SWG\Swagger(
     *     basePath=L5_SWAGGER_CONST_BASE,
     *     schemes={"http"},
     *     host=L5_SWAGGER_CONST_HOST,
     *     @SWG\Info(
     *         version=L5_SWAGGER_VERSION,
     *         title=L5_SWAGGER_CONST_TITLE,
     *     ),
     *     consumes={"application/x-www-form-urlencoded", "application/json"},
     *     produces={"application/json"},
     * )
     */

// Security definition using a JWT Token
    /**
     * @SWG\SecurityScheme(
     *   securityDefinition="bearer",
     *   type="apiKey",
     *   in="header",
     *   name="Authorization"
     * )
     */




}