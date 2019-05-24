<?php

/*
 * Copyright (c) 2019 tim03we  < https://github.com/tim03we >
 * Discord: tim03we | TP#9129
 *
 * This software is distributed under "GNU General Public License v3.0".
 * This license allows you to use it and/or modify it but you are not at
 * all allowed to sell this plugin at any cost. If found doing so the
 * necessary action required would be taken.
 *
 * WorldReset is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License v3.0 for more details.
 *
 * You should have received a copy of the GNU General Public License v3.0
 * along with this program. If not, see
 * <https://opensource.org/licenses/GPL-3.0>.
 */

namespace tim03we\worldreset;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class WorldReset extends PluginBase {

    public function onEnable()
    {
        $this->saveResource("settings.yml");
        $this->cfg = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
        if(!is_array($this->cfg->get("worlds"))) {
            $this->getLogger()->alert("Please list the worlds in the config in a list. See the respective config example. Plugin is disabled.");
            $this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin("WorldReset"));
            return true;
        } else {
            foreach($this->cfg->get("worlds") as $levels) {
                $this->getServer()->loadLevel($levels);
            }
        }
        $this->getScheduler()->scheduleRepeatingTask(new ResetTask($this), $this->cfg->get("time") * 60);
        @mkdir($this->getServer()->getDataPath() . "worldreset/");
    }

    public function reset() {
        foreach($this->getServer()->getOnlinePlayers() as $player) {
            foreach($this->cfg->get("worlds") as $levels) {
                if($player->getLevel()->getLevelByName() === $levels) {
                    $player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                }
            }
        }
        foreach($this->cfg->get("worlds") as $levels) {
            if(!file_exists($this->getServer()->getDataPath() . "worlds/$levels/level.dat")) {
                $this->getLogger()->alert('A world specified in the config was not found in the "worldreset" folder. Plugin is disabled.');
                $this->getServer()->getPluginManager()->disablePlugin($this->getServer()->getPluginManager()->getPlugin("WorldReset"));
                return true;
            }
            if($this->getServer()->isLevelLoaded($levels)) {
                $this->getServer()->unloadLevel($levels);
            }
            $zip = new \ZipArchive;
            $zip->open($this->getServer()->getDataPath() . "worldreset/$levels.zip");
            $zip->extractTo($this->getServer()->getDataPath() . "worlds");
            $zip->close();
            unset($zip);
            $this->getServer()->loadLevel($levels);
            if(!$this->cfg->get("message", false)) {
                $this->getServer()->broadcastMessage($this->cfg->get("message"));
            }
        }
    }
}