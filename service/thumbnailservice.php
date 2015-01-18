<?php
/**
 * ownCloud - galleryplus
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Olivier Paroz <owncloud@interfasys.ch>
 *
 * @copyright Olivier Paroz 2014-2015
 */

namespace OCA\GalleryPlus\Service;

use OCA\GalleryPlus\Environment\Environment;
use OCA\GalleryPlus\Preview\Preview;
use OCA\GalleryPlus\Utility\SmarterLogger;

/**
 * Creates thumbnails for the list of images which is submitted to
 * the service
 *
 * Uses EventSource to send back thumbnails as soon as they're ready
 *
 * @package OCA\GalleryPlus\Service
 */
class ThumbnailService extends PreviewService {

	/**
	 * Constructor
	 *
	 * @param string $appName
	 * @param Environment $environment
	 * @param Preview $previewManager
	 * @param SmarterLogger $logger
	 */
	public function __construct(
		$appName,
		Environment $environment,
		Preview $previewManager,
		SmarterLogger $logger
	) {
		parent::__construct(
			$appName,
			$environment,
			$previewManager,
			$logger
		);
	}

	/**
	 * Creates thumbnails of asked dimensions and aspect
	 *
	 *    * Album thumbnails need to be 200x200 and some will be resized by the
	 *      browser to 200x100 or 100x100.
	 *    * Standard thumbnails are 400x200.
	 *
	 * Sample logger
	 * We can't just send previewData as it can be quite a large stream
	 * $this->logger->debug("[Batch] THUMBNAIL NAME : {image} / PATH : {path} /
	 * MIME : {mimetype} / DATA : {preview}", [
	 *                'image'    => $preview['data']['image'],
	 *                'path'     => $preview['data']['path'],
	 *                'mimetype' => $preview['data']['mimetype'],
	 *                'preview'  => substr($preview['data']['preview'], 0, 20),
	 *              ]
	 *            );
	 *
	 * @param string $image
	 * @param bool $square
	 * @param bool $scale
	 *
	 * @return array
	 */
	public function createThumbnail($image, $square, $scale) {
		$height = 200 * $scale;
		if ($square) {
			$width = $height;
		} else {
			$width = 2 * $height;
		}
		$preview = $this->createPreview($image, $width, $height, !$square);
		$preview['preview'] = $this->encode($preview['preview']);

		return $preview;
	}

}