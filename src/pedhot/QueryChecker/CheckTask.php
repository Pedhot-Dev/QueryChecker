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

namespace pedhot\QueryChecker;

use pedhot\QueryChecker\form\QueryCheckerForm;
use pedhot\QueryChecker\libs\xPaw\MinecraftQuery;
use pedhot\QueryChecker\libs\xPaw\MinecraftQueryException;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class CheckTask extends Task
{
    /** @var QueryChecker */
    private $plugin;
    /** @var Player */
    private $player;
    /** @var MinecraftQuery */
    private $query;
    /** @var MinecraftQueryException */
    private $exception;

    private $ip, $port;

    public function __construct(QueryChecker $owner, Player $player, MinecraftQuery $query, $ip, $port) {
        $this->plugin = $owner;
        $this->player = $player;
        $this->query = $query;
        $this->ip = $ip;
        $this->port = $port;
    }

    public function onRun(int $currentTick) {
        $form = new QueryCheckerForm($this->plugin);
        $form->output($this->player, $this->query, $this->ip, $this->port);
    }

}