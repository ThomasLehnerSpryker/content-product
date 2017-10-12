<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session;

interface SessionConstants
{
    const SESSION_HANDLER_COUCHBASE = 'couchbase';
    const SESSION_HANDLER_REDIS = 'redis';
    const SESSION_HANDLER_REDIS_LOCKING = 'redis_locking';
    const SESSION_HANDLER_MYSQL = 'mysql';
    const SESSION_HANDLER_FILE = 'file';

    const SESSION_LIFETIME_BROWSER_SESSION = '0';
    const SESSION_LIFETIME_1_HOUR = '3600';
    const SESSION_LIFETIME_0_5_HOUR = '1800';
    const SESSION_LIFETIME_1_DAY = '86400';
    const SESSION_LIFETIME_2_DAYS = '172800';
    const SESSION_LIFETIME_3_DAYS = '259200';
    const SESSION_LIFETIME_30_DAYS = '2592000';
    const SESSION_LIFETIME_1_YEAR = '31536000';

    const SESSION_IS_TEST = 'SESSION_IS_TEST';

    const SESSION_HANDLER_REDIS_LOCKING_TIMEOUT_MILLISECONDS = 'SESSION_HANDLER_REDIS_LOCKING_TIMEOUT_MILLISECONDS';
    const SESSION_HANDLER_REDIS_LOCKING_RETRY_DELAY_MICROSECONDS = 'SESSION_HANDLER_REDIS_LOCKING_RETRY_DELAY_MICROSECONDS';
    const SESSION_HANDLER_REDIS_LOCKING_LOCK_TTL_MILLISECONDS = 'SESSION_HANDLER_REDIS_LOCKING_LOCK_TTL_MILLISECONDS';

    const YVES_SESSION_SAVE_HANDLER = 'YVES_SESSION_SAVE_HANDLER';
    const YVES_SESSION_COOKIE_NAME = 'YVES_SESSION_NAME'; // Not YVES_SESSION_COOKIE_NAME for BC reasons!
    const YVES_SESSION_COOKIE_SECURE = 'YVES_COOKIE_SECURE'; // Not YVES_SESSION_COOKIE_SECURE for BC reasons!
    const YVES_SESSION_COOKIE_DOMAIN = 'YVES_SESSION_COOKIE_DOMAIN';
    const YVES_SESSION_COOKIE_TIME_TO_LIVE = 'YVES_SESSION_COOKIE_TIME_TO_LIVE';
    const YVES_SESSION_FILE_PATH = 'YVES_SESSION_FILE_PATH';
    const YVES_SESSION_PERSISTENT_CONNECTION = 'YVES_SESSION_PERSISTENT_CONNECTION';
    const YVES_SESSION_TIME_TO_LIVE = 'YVES_SESSION_TIME_TO_LIVE';
    const YVES_SSL_ENABLED = 'YVES_SSL_ENABLED';

    const YVES_SESSION_REDIS_PROTOCOL = 'YVES_SESSION_REDIS_PROTOCOL';
    const YVES_SESSION_REDIS_PASSWORD = 'YVES_SESSION_REDIS_PASSWORD';
    const YVES_SESSION_REDIS_HOST = 'YVES_SESSION_REDIS_HOST';
    const YVES_SESSION_REDIS_PORT = 'YVES_SESSION_REDIS_PORT';
    const YVES_SESSION_REDIS_DATABASE = 'YVES_SESSION_REDIS_DATABASE';

    const ZED_SSL_ENABLED = 'ZED_SSL_ENABLED';
    const ZED_SESSION_SAVE_HANDLER = 'ZED_SESSION_SAVE_HANDLER';
    const ZED_SESSION_COOKIE_NAME = 'ZED_SESSION_COOKIE_NAME';
    const ZED_SESSION_COOKIE_SECURE = 'ZED_COOKIE_SECURE';
    const ZED_SESSION_COOKIE_TIME_TO_LIVE = 'ZED_SESSION_COOKIE_TIME_TO_LIVE';
    const ZED_SESSION_FILE_PATH = 'ZED_SESSION_FILE_PATH';
    const ZED_SESSION_PERSISTENT_CONNECTION = 'ZED_SESSION_PERSISTENT_CONNECTION';
    const ZED_SESSION_TIME_TO_LIVE = 'ZED_SESSION_TIME_TO_LIVE';

    const ZED_SESSION_REDIS_PROTOCOL = 'ZED_SESSION_REDIS_PROTOCOL';
    const ZED_SESSION_REDIS_HOST = 'ZED_SESSION_REDIS_HOST';
    const ZED_SESSION_REDIS_PORT = 'ZED_SESSION_REDIS_PORT';
    const ZED_SESSION_REDIS_PASSWORD = 'ZED_SESSION_REDIS_PASSWORD';
    const ZED_SESSION_REDIS_DATABASE = 'ZED_SESSION_REDIS_DATABASE';
}
