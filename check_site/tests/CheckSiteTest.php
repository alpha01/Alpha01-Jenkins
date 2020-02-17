<?php

/**
 * Unit Tests used by Jenkins.
 * Tests:
 *  200 http response
 *  '<!-- Google Tag Manager -->' string in body
 *  '</html>' ending close tag is present in body
 */

use PHPUnit\Framework\TestCase;

require_once('/check_site/src/CheckSite.php');


class CheckSiteTest extends TestCase
{
    protected static $_connObj;

    /*
    * Config property
    */
    protected static $_config;

    /*
    * Required environment variables from Jenkins:
     DOMAIN
     GOOGLE_GA_STRING
     CONTAINER

    * Optional environment variables from Jenkins:
     SKIP_UNIT_TESTS
    */

    public static function setUpBeforeClass() : void
    {
        self::$_config = self::_grab_config();
        self::$_connObj = new CheckSite(self::$_config);

        if (!self::$_config['SKIP_UNIT_TESTS'])
            self::$_connObj->check_site();
    }

    public static function tearDownAfterClass() : void
    {
        self::$_connObj = null;
    }

    public function setUp() : void { }
    public function tearDown(): void { }

    /** Testing Desktop */
    public function testDesktopResponseCodeIsValid()
    {
        if (self::$_config['SKIP_UNIT_TESTS']) {
            $this->markTestSkipped('Skipping Desktop http response code check.');
        } else {
            $this->assertTrue(self::$_connObj->test_status['desktop_http_response_code'] == 200,
                'Desktop: ' . self::$_config['DOMAIN'] . "\n" . 'http response code: ' . self::$_connObj->test_status['desktop_http_response_code'] . "\n");
        }
    }

    public function testDesktopGoogleTagIsValid()
    {
        if (self::$_config['SKIP_UNIT_TESTS']) {
            $this->markTestSkipped('Skipping Desktop Google Analytics string check.');
        } else {
            $this->assertStringContainsStringIgnoringCase(self::$_config['GOOGLE_GA_STRING'],
                self::$_connObj->test_status['desktop_http_response'],
                'Desktop: ' . self::$_config['DOMAIN'] . "\n" . 'GA String: ' . self::$_config['GOOGLE_GA_STRING'] . "\n"
            );

            $this->assertStringContainsStringIgnoringCase('</html>',
                self::$_connObj->test_status['desktop_http_response'],
                'Desktop: ' . self::$_config['DOMAIN'] . "\n" .
                "Ending '</html>' tag not found in desktop homepage body!"
            );
        }
    }

    /** Testing Mobile */
    public function testMobileResponseCodeIsValid()
    {
        if (self::$_config['SKIP_UNIT_TESTS']) {
            $this->markTestSkipped('Skipping Desktop http response code check.');
        } else {
            $this->assertTrue(self::$_connObj->test_status['mobile_http_response_code'] == 200,
                'Mobile: ' . self::$_config['DOMAIN'] . "\n" . 'http response code: ' . self::$_connObj->test_status['mobile_http_response_code'] . "\n");
        }
    }

    public function testMobileGoogleTagIsValid()
    {
        if (self::$_config['SKIP_UNIT_TESTS']) {
            $this->markTestSkipped('Skipping Mobile Google Analytics string check.');
        } else {
            $this->assertStringContainsStringIgnoringCase(self::$_config['GOOGLE_GA_STRING'],
				self::$_connObj->test_status['mobile_http_response'],
                'Mobile: ' . self::$_config['DOMAIN'] . "\n" . 'GA String: ' . self::$_config['GOOGLE_GA_STRING'] . "\n"
            );

            $this->assertStringContainsStringIgnoringCase('</html>',
                self::$_connObj->test_status['mobile_http_response'],
                'Mobile: ' . self::$_config['DOMAIN'] . "\n" .
                "Ending '</html>' tag not found in desktop homepage body!"
           );
        }
    }

    /** Grab unit test config from Jenkins environment variables**/
    private function _grab_config()
    {
        // Optional, it skip tests if this Jenkins variable is set
        if(getenv('SKIP_UNIT_TESTS') == 'true')
			return array('SKIP_UNIT_TESTS' => true);
        else
            $unittest_config_optional = array('SKIP_UNIT_TESTS' => false);

        $unittest_config_required = [
            'DOMAIN'           => false,
            'GOOGLE_GA_STRING' => false,
            'CONTAINER'        => false
        ];

        try {
            foreach ($unittest_config_required as $config => $value) {
                $unittest_config_required[$config] = getenv($config);

                if (!$unittest_config_required[$config])
                    throw new Exception($config);
            }
        } catch (Exception $e) {
            echo 'No "' . $e->getMessage() . '" environment variable detected!' . "\n";
            exit(1);
        }

        return array_merge($unittest_config_optional, $unittest_config_required);
    }
}

?>
