<?php

require __DIR__.'/../vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Dumbo\Context;
use Dumbo\Dumbo;

// Define our 'ENVIRONMENT'
define('APP_ENV', getenv('APP_ENV') ?: 'production');

$app = new Dumbo();

$app->get('/', function (Context $context) {
    return $context->html('You could place some documentation here!');
});

$app->post('/', function (Context $context) {
    try {
        // These params are required and will fail validation if not included in request
        $requiredParams = [
            'data',
        ];

        // Values for optional params
        $defaultParams = [
            'scale' => 5,
            'format' => 'svg',
            'bgColor' => 'white',
        ];

        $errors = validator($context, $requiredParams);

        if ($errors) {
            return $context->json([
                'status' => 400,
                'error' => true,
                'message' => json_encode($errors),
            ], 400);
        }

        $body = $context->req->body();

        $data = (string) $body['data'];
        $scale = (int) ($body['scale'] ?? $defaultParams['scale']);
        // $bgColor = $body['bgColor'] ?? $defaultParams['bgColor'];
        $outputType = matchFormatToQROutputInterface($body['format'] ?? $defaultParams['format']);
        $contentType = matchQROutputInterfaceToContentTypeHeader($outputType);

        // Docs: https://php-qrcode.readthedocs.io/en/stable/Usage/Configuration-settings.html
        $options = new QROptions();
        $options->version = 7;
        $options->outputBase64 = false;

        $options->outputType = $outputType;
        // $options->bgColor = $bgColor;
        $options->scale = $scale;

        $qrCode = (new QRCode($options))->render($data);

        return response(
            $context,
            $qrCode,
            $contentType
        );
    } catch (Error $exception) {
        return $context->json([
            'status' => 400,
            'error' => true,
            'message' => 'Bad Request',
        ], 400);
    } catch (Exception $exception) {
        if (APP_ENV === 'production') {
            return $context->json([
                'status' => 500,
                'error' => true,
                'message' => 'Whoops, something went wrong!',
            ], 500);
        }

        throw $exception;
    }
});
