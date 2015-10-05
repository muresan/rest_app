<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');

$art_db = array (
	1	=> 'article 1',
	2	=> 'article 2',
	3	=> 'article 3',
);

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

// GET route
$app->get( '/', function () use ($art_db) {
        echo "GET without id\n";
	echo json_encode($art_db);
	echo "\n";
    }
);

$app->get( '/:id', function ($id) use ($art_db, $app) {
	echo "GET with id\n";
	if (array_key_exists($id, $art_db)) {
	        echo json_encode(array($id => $art_db[$id]));
	} else {
		$app->response->setStatus(404);
		echo "key $id not found";
	}
	echo "\n";
    }
);

// POST route
$app->post(
    '/post',
    function () {
        echo 'This is a POST route';
    }
);

$app->post(
    '/post/:id',
    function ($id) {
        echo "This is a POST route $id";
    }
);

// PUT route
$app->put('/:id/:title', function ($id, $title) use ($art_db, $app) {
        echo "PUT with id ".$id."->".$title." ";
	if (!array_key_exists($id, $art_db)) {
		$art_db[$id] = $title;
		echo json_encode ($art_db);
	} else {
		$app->response->setStatus(404);
		echo "key $id already exists, use PATCH";
	}
	echo "\n";
    }
);

// PATCH route
$app->patch('/:id/:title', function ($id, $title) use ($art_db,$app) {
        echo "PATCH with id ".$id."->".$title." ";
        if (array_key_exists($id, $art_db)) {
                $art_db[$id] = $title;
                echo json_encode ($art_db);
        } else {
		$app->response->setStatus(404);
                echo "key $id missing, use PUT";
        }
	echo "\n";
    }
);

// DELETE route
$app->delete(
    '/:id',
    function ($id) use ($art_db) {
	echo "DELETE with id\n";
	unset($art_db[$id]);
	echo json_encode ($art_db);
	echo "\n";
    }
);

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
