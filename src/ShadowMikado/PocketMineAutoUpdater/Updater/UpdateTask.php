<?php

declare(strict_types=1);

namespace ShadowMikado\PocketMineAutoUpdater\Updater;

use pocketmine\scheduler\Task;
use ShadowMikado\PocketMineAutoUpdater\Main;
use ShadowMikado\PocketMineAutoUpdater\Updater\UpdateCheckerMixin;

class UpdateTask extends Task
{

    public function onRun(): void
    {

        $checker = new UpdateCheckerMixin;

        if ($checker->isConnected() == true) {
            $checker->checkUpdate();
            $checker->downloadUpdate();
            $this->getHandler()->cancel();
        } else {
            Main::getInstance()->getLogger()->critical("The server isn't connected to the internet !");
            $this->getHandler()->cancel();
        }
    }
}
