<?php // FileStorage
use Cake\Event\EventManager;
use Burzum\FileStorage\Storage\StorageUtils;
use Burzum\FileStorage\Storage\StorageManager;
use Burzum\FileStorage\Event\ImageProcessingListener;
use Burzum\FileStorage\Event\LocalFileStorageListener;
use Burzum\FileStorage\Storage\Listener\BaseListener;
use Cake\Core\Configure;
use Cake\Core\Plugin;

Plugin::load('Burzum/FileStorage');
Plugin::load('Burzum/Imagine');

// Instantiate a storage event listener
$listener = new BaseListener([
	'imageProcessing' => true, // Required if you want image processing!
	'pathBuilderOptions' => [
		'pathPrefix' => 'images',
		'modelFolder' => true,
		'preserveFilename' => false,
		'randomPath' => 'sha1'
	]
]);
// Attach the BaseListener to the global EventManager
EventManager::instance()->on($listener);
// File Storage
$listener = new LocalFileStorageListener();
EventManager::instance()->on($listener);
// For automated image processing you'll have to attach this listener as well
$listener = new ImageProcessingListener([
			'autoRotate' => ['ProductImage']
]);
EventManager::instance()->on($listener);

// Imagine
Configure::write('Imagine.salt', 'a,sndaiuy22iuw82iwgd2673t42yhlkjfauyf');

StorageManager::config('Local', [
	'adapterOptions' => [STORAGE, true],
	'adapterClass' => '\Gaufrette\Adapter\Local',
	'class' => '\Gaufrette\Filesystem'
]);

Configure::write('FileStorage', [
// Configure image versions on a per model base
	'imageSizes' => [
		'ProductImage' => [
			'large' => [
				'thumbnail' => [
					'mode' => 'inbound',
					'width' => 800,
					'height' => 800
				]
			],
			'medium' => [
				'thumbnail' => [
					'mode' => 'inbound',
					'width' => 400,
					'height' => 400
				]
			],
			'small' => [
				'thumbnail' => [
					'mode' => 'inbound',
					'width' => 120,
					'height' => 120
				]
			]
		]
	]
]);
StorageUtils::generateHashes();
