<?php
	class Internals{
		public static function getMaxUploadSize($parseReadable = true) {
			$max_size = -1;
			$readableSize = "";

			if ($max_size < 0) {
				// Start with post_max_size.
				$post_max_size = self::parseSize(ini_get('post_max_size'));
				if ($post_max_size > 0) {
					$max_size = $post_max_size;
					$readableSize = ini_get('post_max_size');
				}
				$upload_max = self::parseSize(ini_get('upload_max_filesize'));
				if ($upload_max > 0 && $upload_max < $max_size) {
					$max_size = $upload_max;
					$readableSize = ini_get('upload_max_filesize');
				}
			}

			if ($parseReadable){
				return $readableSize;
			}else{
				return $max_size;
			}
		}

		private static function parseSize($size) {
			$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
			$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
			if ($unit) {
				// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
				return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
			}else {
				return round($size);
			}
		}
	}
