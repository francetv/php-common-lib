<?php

/*
 * This file is part of the COMMON LIB package
 *
 * (c) France Télévisions Editions Numériques <guillaume.postaire@francetv.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ftven\Build\Common\Service\System;

use Ftven\Build\Common\Feature\ExceptionThrowerTrait;
use Ftven\Build\Common\Feature\ProcessRunnerTrait;

/**
 * @author Olivier Hoareau <olivier@phppro.fr>
 */
class SystemService implements SystemServiceInterface
{
    use ExceptionThrowerTrait;
    use ProcessRunnerTrait;
    /**
     * @param string $command
     * @param string $dir
     * @param array  $goodExitCodes
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    public function execute($command, $dir = null, $goodExitCodes = [0])
    {
        $cmd = $this->createProcess($command, $dir);

        $return = $cmd->run();

        if (null === $goodExitCodes || true === in_array($return, $goodExitCodes)) {
            return [$cmd->getOutput(), $cmd->getErrorOutput(), $return, $cmd->getExitCodeText()];
        }

        return $this->throwException(
            $return, "Error when executing [%s]: %s", $cmd->getCommandLine(), $cmd->getErrorOutput()
        );
    }
    /**
     * @return string
     */
    public function getTempDirectory()
    {
        return sys_get_temp_dir();
    }
}