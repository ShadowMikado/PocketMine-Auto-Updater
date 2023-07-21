<?php

declare(strict_types=1);

namespace ShadowMikado\PocketMineAutoUpdater\Updater;

use pocketmine\utils\Internet;
use ShadowMikado\PocketMineAutoUpdater\Main;

class UpdateCheckerMixin
{

	private string $updateUrl = "";

	public function checkUpdate(): void
	{
		$response = $this->getResponse();
		$this->updateUrl = $response["download_url"];
	}

	public function downloadUpdate(): void
	{
		if ($this->isUpdatable() == true) {
			Main::getInstance()->getLogger()->warning("Downloading PocketMine update, don't stop the server !");
			file_put_contents(Main::getInstance()->getDataFolder() . "update/PocketMine-MP.phar", fopen($this->updateUrl, "r"));
			copy("PocketMine-MP.phar", Main::getInstance()->getDataFolder() . "old/PocketMine-MP.phar");
			Main::getInstance()->getLogger()->warning("Successfully downloaded update in " . Main::getInstance()->getDataFolder() . "update");
		}
	}

	public function getResponse()
	{
		$error = "";
		$response = Internet::getURL("https://update.pmmp.io/api", 4, [], $error);
		if ($response != null) {
			$response = json_decode($response->getBody(), true);
			if (is_array($response)) {
				if (isset($response["error"]) && is_string($response["error"])) {
					$error = $response["error"];
					return $error;
				} else {
					return $response;
				}
			} else {
				$error = "Invalid response data (format)";
				return $error;
			}
		} else {
			$error = "Invalid response data (null)";
			return $error;
		}
	}

	public function isUpdatable(): bool
	{
		if (Main::getInstance()->getServer()->getPocketMineVersion() != $this->getResponse()["base_version"]) {
			return true;
		} else {
			return false;
		}
	}

	public function isConnected(): bool
	{
		$connected = @fsockopen("google.com", 443);
		if ($connected) {
			$is_conn = true;
			fclose($connected);
		} else {
			$is_conn = false;
		}
		return $is_conn;
	}
}
