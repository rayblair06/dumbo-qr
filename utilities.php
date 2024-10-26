<?php

use chillerlan\QRCode\Output\QROutputInterface;
use Dumbo\Context;
use Psr\Http\Message\ResponseInterface;

/*
 * Dump and die
 *
 * @param [type] ...$params
 * @return void
 */
if (!function_exists('dd')) {
    function dd(...$params)
    {
        dump($params);
        exit;
    }
}

/*
 * Take required Parameters and build error response if validation fails
 *
 * @param Context $context
 * @param array $requiredParams
 * @return array
 */
if (!function_exists('validator')) {
    function validator(Context $context, array $requiredParams): array
    {
        $body = $context->req->body();
        $errors = [];

        foreach ($requiredParams as $required) {
            if (!array_key_exists($required, $body)) {
                $errors[] = sprintf("Please specify '%s' parameter.", $required);
            }
        }

        return $errors;
    }
}

/*
 * Match `type` request parameter to our QROutputInterface
 *
 * @param string $type
 * @return string
 */
if (!function_exists('matchType')) {
    function matchFormatToQROutputInterface(string $type): string
    {
        return match ($type) {
            'svg' => QROutputInterface::MARKUP_SVG,
            'html' => QROutputInterface::MARKUP_HTML,
            'bmp' => QROutputInterface::GDIMAGE_BMP,
            'gif' => QROutputInterface::GDIMAGE_GIF,
            'jpg' => QROutputInterface::GDIMAGE_JPG,
            'png' => QROutputInterface::GDIMAGE_PNG,
            'webp' => QROutputInterface::GDIMAGE_WEBP,
            'text' => QROutputInterface::STRING_TEXT,
            'json' => QROutputInterface::STRING_JSON,
            default => throw new Error(sprintf("Type '%s' is not a valid option", $type)),
        };
    }
}

/*
 * Match QROutputInterface to Content-Type header
 *
 * @param string $type
 * @return string
 */
if (!function_exists('matchTypeToResponseContentType')) {
    function matchQROutputInterfaceToContentTypeHeader(string $type): string
    {
        return match ($type) {
            QROutputInterface::MARKUP_SVG => 'image/svg+xml',
            QROutputInterface::MARKUP_HTML => 'text/html',
            QROutputInterface::GDIMAGE_BMP => 'image/bmp',
            QROutputInterface::GDIMAGE_GIF => 'image/gif',
            QROutputInterface::GDIMAGE_JPG => 'image/jpg',
            QROutputInterface::GDIMAGE_PNG => 'image/png',
            QROutputInterface::GDIMAGE_WEBP => 'image/webp',
            QROutputInterface::STRING_TEXT => 'text/plain',
            QROutputInterface::STRING_JSON => 'application/json',
        };
    }
}

/*
 * Helper function for returning our image response
 *
 * @param Context $context
 * @param string $data
 * @param string $contentType
 * @return void
 */
if (!function_exists('response')) {
    function response(Context $context, string $data, string $contentType): ResponseInterface
    {
        $response = $context
            ->getResponse();

        $response = $response
                ->withStatus(200)
                ->withHeader('Content-Type', $contentType);

        $response
            ->getBody()
            ->write($data);

        return $response;
    }
}
