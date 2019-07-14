<?php

namespace App\Models;

use Grafika\Grafika;

class PhotoModel{

	const IMAGE_SIZES = [
		'large' => [900, 900],
		'medium' => [500, 500],
		'small' => [200, 200],
	];

	const ORIGINAL_IMAGE_REPO = 'images';

	const MODIFIED_IMAGE_REPO = 'img';

	public function saveImage($image) {
		$imageName = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = $this->getOriginalImageDestination();
        $image->move($destinationPath, $imageName);
        return $imageName;
	}

	public function modifyToStandardSize($imageName) {
		$finalPaths = [];
		$editor = Grafika::createEditor();
		$editor->open( $image, $this->getOriginalImagePath($imageName));
		$height = $newHeight = $image->getHeight();
		$width = $newWidth = $image->getWidth();
		
		foreach (self::IMAGE_SIZES as $key => $value) {
			$imageClone = clone $image;
			if ($height > $width) {
				if ($height > $value[1]) {
					$newHeight = $value[1];
					$newWidth = $width*($newHeight/$height);
				}
				$editor->resizeExact( $imageClone, $newWidth, $newHeight );
			}
			else {
				if ($width > $value[0]) {
					$newWidth = $value[0];
					$newHeight = $height*($newWidth/$width);
				}
				$editor->resizeExact( $imageClone, $newWidth, $newHeight );
			}
			$newName = $key . '_' . $newWidth . 'x' . $newHeight . '_' . $imageName;
			$editor->save( $imageClone, $this->getModifiedImagePath($newName));
			$editor->free($imageClone);
			$finalPaths [$newName] = self::MODIFIED_IMAGE_REPO . '/' . $newName;
		}
		$editor->free($image);
		return $finalPaths;
	}

	private function getOriginalImagePath($imgName) {
		return $this->getOriginalImageDestination() . '/' . $imgName;
	}

	private function getModifiedImagePath($imgName) {
		return $this->getModifiedImageDestination() . '/' . $imgName;
	}

	private function getOriginalImageDestination() {
		return public_path('/' . self::ORIGINAL_IMAGE_REPO);
	}

	private function getModifiedImageDestination() {
		return public_path('/' . self::MODIFIED_IMAGE_REPO);
	}
}

?>