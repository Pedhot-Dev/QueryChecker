<?php

/**
 *   _____         _ _           _   _____
 *  |  __ \       | | |         | | |  __ \
 *  | |__) |__  __| | |__   ___ | |_| |  | | _____   __
 *  |  ___/ _ \/ _` | '_ \ / _ \| __| |  | |/ _ \ \ / /
 *  | |  |  __/ (_| | | | | (_) | |_| |__| |  __/\ V /
 *  |_|   \___|\__,_|_| |_|\___/ \__|_____/ \___| \_/
 *
 *
 * Copyright 2021-2022 PedhotDev

 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace pedhot\QueryChecker\form;

use pedhot\QueryChecker\CheckTask;
use pedhot\QueryChecker\libs\jojoe77777\FormAPI\CustomForm;
use pedhot\QueryChecker\libs\jojoe77777\FormAPI\SimpleForm;
use pedhot\QueryChecker\libs\xPaw\MinecraftQuery;
use pedhot\QueryChecker\libs\xPaw\MinecraftQueryException;
use pedhot\QueryChecker\QueryChecker;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class QueryCheckerForm
{
    /** @var QueryChecker */
    private $plugin;

    public function __construct(QueryChecker $plugin) {
        $this->plugin = $plugin;
    }

    public function pl(Player $player, MinecraftQuery $query) {
        $form = new CustomForm(function (Player $player, array $data = null) use ($query) {
            if ($data == null) {
                return;
            }

            $ip = $data[0];
            $port = $data[1];
            $this->plugin->getScheduler()->scheduleDelayedTask(new CheckTask($this->plugin, $player, $query, $ip, $port), $this->getPing($player) * 20);
        });
        $form->setTitle("Query");
        $form->addInput("IP");
        $form->addInput("PORT");
        $form->sendToPlayer($player);
    }

    public function output(Player $player, MinecraftQuery $query, $ip, $port) {
        $form = new SimpleForm(function (Player $player, $data = null) use ($query) {
            if ($data == null) {
                return;
            }
        });
        $exceptions = "";
        try {
            $query->Connect($ip, $port);
        }catch (MinecraftQueryException $exception) {
            $exceptions .= TextFormat::RED.$exception->getMessage();
        }
        $players = $query->GetPlayers();
        $info = $query->GetInfo();
        $contentInfo = "";
        $contentPlayer = "";
        $i = 1;
        if ($players !== false){
            foreach ($players as $playerList) {
                if ($i == 51) break;
                $contentPlayer .= TextFormat::GREEN.$i . TextFormat::WHITE." - " .TextFormat::GOLD. $playerList . "\n";
                $i++;
            }
        }else{
            $contentPlayer .= TextFormat::RED."No players!\n".TextFormat::GOLD.$exceptions;
        }
        if ($info !== false) {
            foreach ($info as $key => $value) {
                $contentInfo .= TextFormat::RESET."- ".TextFormat::GREEN.$key . ": " .TextFormat::YELLOW. $value . "\n";
            }
        }else{
            $contentInfo .= TextFormat::RED."No Info!\n".TextFormat::GOLD.$exceptions;
        }
        $form->setTitle(TextFormat::BOLD.TextFormat::AQUA."Query");
        $form->setContent(TextFormat::AQUA."Info: \n".$contentInfo."\n\n".TextFormat::AQUA."List (50 player): \n".$contentPlayer);
        $form->addButton("Exit");
        $form->sendToPlayer($player);
    }

    private function getPing(Player $player) {
        $ping = $player->getPing();
        if ($ping < 100) {
            return 2;
        }elseif ($ping > 400) {
            return 4;
        }elseif ($ping > 1000) {
            return 6;
        }else{
            return 2;
        }
    }

}