<?php

namespace App\Http\Controllers;

use App\Traits\ResponseFormatterTrait;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="專案名稱",
 *     version="1.0.0",
 *     description="<專案名稱>的 API 文件"
 *   )
 * )
 * @OA\SecurityScheme(
 *   type="http",
 *   description="登入驗證",
 *   name="Token based Based",
 *   in="header",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 *   securityScheme="apiAuth",
 * )
 */
class Controller extends BaseController
{
    use ResponseFormatterTrait;

    public const HTTP_CONTINUE = 100;
    public const HTTP_SWITCHING_PROTOCOLS = 101;
    // RFC2518
    public const HTTP_PROCESSING = 102;
    // RFC8297
    public const HTTP_EARLY_HINTS = 103;
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_ACCEPTED = 202;
    public const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_RESET_CONTENT = 205;
    public const HTTP_PARTIAL_CONTENT = 206;
    // RFC4918
    public const HTTP_MULTI_STATUS = 207;
    // RFC5842
    public const HTTP_ALREADY_REPORTED = 208;
    // RFC3229
    public const HTTP_IM_USED = 226;
    public const HTTP_MULTIPLE_CHOICES = 300;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_SEE_OTHER = 303;
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_USE_PROXY = 305;
    public const HTTP_RESERVED = 306;
    public const HTTP_TEMPORARY_REDIRECT = 307;
    // RFC7238
    public const HTTP_PERMANENTLY_REDIRECT = 308;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_PAYMENT_REQUIRED = 402;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_NOT_ACCEPTABLE = 406;
    public const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    public const HTTP_REQUEST_TIMEOUT = 408;
    public const HTTP_CONFLICT = 409;
    public const HTTP_GONE = 410;
    public const HTTP_LENGTH_REQUIRED = 411;
    public const HTTP_PRECONDITION_FAILED = 412;
    public const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    public const HTTP_REQUEST_URI_TOO_LONG = 414;
    public const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    public const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    public const HTTP_EXPECTATION_FAILED = 417;
    // RFC2324
    public const HTTP_I_AM_A_TEAPOT = 418;
    // RFC7540
    public const HTTP_MISDIRECTED_REQUEST = 421;
    // RFC4918
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    // RFC4918
    public const HTTP_LOCKED = 423;
    // RFC4918
    public const HTTP_FAILED_DEPENDENCY = 424;
    // RFC-ietf-httpbis-replay-04
    public const HTTP_TOO_EARLY = 425;
    // RFC2817
    public const HTTP_UPGRADE_REQUIRED = 426;
    // RFC6585
    public const HTTP_PRECONDITION_REQUIRED = 428;
    // RFC6585
    public const HTTP_TOO_MANY_REQUESTS = 429;
    // RFC6585
    public const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    public const HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;
    public const HTTP_BAD_GATEWAY = 502;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    public const HTTP_GATEWAY_TIMEOUT = 504;
    public const HTTP_VERSION_NOT_SUPPORTED = 505;
    // RFC2295
    public const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;
    // RFC4918
    public const HTTP_INSUFFICIENT_STORAGE = 507;
    // RFC5842
    public const HTTP_LOOP_DETECTED = 508;
    // RFC2774
    public const HTTP_NOT_EXTENDED = 510;
    // RFC6585
    public const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;
}
