<?php

declare(strict_types=1);

namespace ShadowMikado\PocketMineAutoUpdater;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use ShadowMikado\PocketMineAutoUpdater\Updater\UpdateTask;

class Main extends PluginBase implements Listener
{

    public static Main $main;

    public function onLoad(): void
    {

        if (!is_dir($this->getDataFolder() . "update")) {
            @mkdir($this->getDataFolder() . "update");
        }

        if (!is_dir($this->getDataFolder() . "old")) {
            @mkdir($this->getDataFolder() . "old");
        }
        $this->getScheduler()->scheduleRepeatingTask(new UpdateTask, 0);
    }

    public function onEnable(): void
    {
        self::$main = $this;
    }

    public function onDisable(): void
    {
    }

    public static function getInstance(): Main
    {
        return self::$main;
    }
}
