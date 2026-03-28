<?php

declare(strict_types=1);

/*
 * This file is part of the Serendipity HQ Text Matrix Component.
 *
 * Copyright (c) Adamo Aerendir Crespi <aerendir@serendipityhq.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$config = new Aerendir\Bin\GitHubActionsMatrix\Config\GHMatrixConfig();

// Set the default GitHub username for the repository
// $config->setUser('your-github-username');

// Set the default branch to sync/compare
// $config->setBranch('main');

// Set the name of the file that contains the GitHub token
// $config->setTokenFile('gh_token');

return $config;
