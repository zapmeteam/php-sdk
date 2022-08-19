<?php

use Dotenv\Dotenv;
use ZapMeSdk\Base;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Dotenv::createUnsafeImmutable(constant('HOMEPATH'))->safeLoad();

        $url    = getenv('ZAPME_TEST_URL');
        $api    = getenv('ZAPME_TEST_API');
        $secret = getenv('ZAPME_TEST_SECRET');

        if ($url === false || $api === false || $secret === false) {
            $this->markTestSkipped('Test environment variables are not set.');
        }

        $this->base = (new Base())
            ->toUrl($url)
            ->withApi($api)
            ->withSecret($secret);
    }

    /** @test */
    public function it_should_be_able_to_get_account_data()
    {
        $result = $this->base->accountStatus();

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('service', $result['data']);
        $this->assertArrayHasKey('auth', $result['data']);

        $this->assertEquals('success', $result['result']);
    }

    /** @test */
    public function it_should_be_able_to_send_message()
    {
        $result = $this->base->sendMessage('5571985080231', 'Test');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertEquals('created', $result['result']);
    }

    /** @test */
    public function it_should_be_able_to_get_all_messages()
    {
        $result = $this->base->getMessages(true);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertEquals('success', $result['result']);
    }

    /** @test */
    public function it_should_be_able_to_single_message_all_messages()
    {
        $all = $this->base->getMessages(true);

        if (!isset($all['data']['data'][0]['id'])) {
            $this->markTestSkipped('No messages found.');
        }

        $single = $this->base->getMessage($all['data']['data'][0]['id']);

        $this->assertArrayHasKey('status', $single);
        $this->assertArrayHasKey('date', $single);
        $this->assertArrayHasKey('result', $single);
        $this->assertArrayHasKey('data', $single);

        $this->assertEquals('success', $single['result']);
    }
}
