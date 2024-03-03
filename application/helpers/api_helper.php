<?php defined('BASEPATH') or exit('No direct script access allowed.');

/**
 * @param $key = 'SECRET_KEY_FOR_ADDITIONAL_PROTECTION' default: null, (using key that set in .env)
 * Function ini untuk proteksi tambahan yang disimpan di url,
 * jadi setiap request url endpointnya harus disertakan property key dan value yang benar
 * Contohnya: https://yourdomain.com/api/endpoint?key=SECRET_KEY_FOR_ADDITIONAL_PROTECTION
 */
function protectByKey(?string $key = null)
{
	if ($key == null) {
		$key = $_ENV['API_PATH_KEY'];
	}
	$ci = &get_instance();
	$inputedKey = $ci->input->get('key');
	if ($key != $inputedKey) {
		$ci->output->set_status_header(403);
		$ci->output->set_content_type('application/json');
		echo json_encode([
			'success' => false,
			'status' => 'fail',
			'message' => 'Forbidden'
		]);
		exit;
	}
}

/**
 * @param array $data that would be sent
 * @param int $statusCode: HTTP Response Status
 * 
 * 200 - OK: The request has succeeded.
 * 201 - Created: The request has been fulfilled and resulted in a new resource being created.
 * 
 * Note: 301 and 302 note recomended for API Response
 * 301 - Moved Permanently: The requested resource has been assigned a new permanent URI.
 * 302 - Found The requested resource resides temporarily under a different URI.
 * 
 * 400 - Bad Request: The server cannot or will not process the request due to an apparent client error.
 * 401 - Unauthorized: Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided.
 * 403 - Forbidden: The request was valid, but the server is refusing action. The user might not have the necessary permissions for a resource.
 * 404 - Not Found: The requested resource could not be found but may be available in the future.
 * 405 - Method Not Allowed: A request method is not supported for the requested resource.
 * 500 - Internal Server Error: A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.
 */
function responseJSON(array $data = [], int $statusCode = 200)
{
	$ci = &get_instance();
	$ci->output->set_status_header($statusCode);
	$ci->output->set_content_type('application/json');

	echo json_encode($data);
	exit;
}

function getRequest($index)
{
	$ci = &get_instance();
	if (isset($_SERVER['CONTENT_TYPE'])) {
		$isFormUrlEncoded = strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') !== false;
		$isFormData = strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false;
		if ($isFormUrlEncoded || $isFormData) {
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				return $ci->input->get($index, true);
			} else {
				return $ci->input->post($index, true);
			}
		}
	}
	$request = $ci->input->json();
	return isset($request[$index]) ? $request[$index] : null;
}
