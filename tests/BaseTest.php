<?php

use Dotenv\Dotenv;
use ZapMeSdk\Base as ZapMeSdk;
use PHPUnit\Framework\TestCase;
use Faker\Generator;
use Faker\Factory;

class BaseTest extends TestCase
{
    /** @var Generator */
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        Dotenv::createUnsafeImmutable(constant('HOMEPATH'))->safeLoad();

        $url    = getenv('ZAPME_TEST_URL');
        $api    = getenv('ZAPME_TEST_API');
        $secret = getenv('ZAPME_TEST_SECRET');

        if (!$url || !$api || !$secret) {
            $this->markTestSkipped('Test environment variables are not correctly set.');
        }

        $this->base = (new ZapMeSdk())
            ->toUrl($url)
            ->withApi($api)
            ->withSecret($secret);

        $this->faker = Factory::create('pt_BR');
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
        $result = $this->base->sendMessage('5571985080231', 'Test (PHP Unit)');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertEquals('created', $result['result']);
        $this->assertEquals('message_sent', $result['data']);
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
    public function it_should_be_able_to_single_message()
    {
        $all = $this->base->getMessages(true);

        if (!isset($all['data'][0]['id'])) {
            $this->markTestSkipped('No messages found.');
        }

        $single = $this->base->getMessage($all['data'][0]['id']);

        $this->assertArrayHasKey('status', $single);
        $this->assertArrayHasKey('date', $single);
        $this->assertArrayHasKey('result', $single);
        $this->assertArrayHasKey('data', $single);

        $this->assertEquals('success', $single['result']);
    }

    /** @test */
    public function it_should_be_able_to_create_contact()
    {
        $name  = $this->faker->firstName();
        $phone = $this->faker->phoneNumber();

        $result = $this->base->createContact($name, $phone);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertEquals('created', $result['result']);
        $this->assertEquals($name, $result['data']['name']);
        $this->assertEquals($phone, $result['data']['phone']);
    }

    /** @test */
    public function it_should_be_able_to_create_contact_as_disabled()
    {
        $name = $this->faker->firstName();
        $phone = $this->faker->phoneNumber();

        $result = $this->base->createContact($name, $phone, 'disable');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertEquals('created', $result['result']);
        $this->assertEquals($name, $result['data']['name']);
        $this->assertEquals($phone, $result['data']['phone']);
        $this->assertEquals('disable', $result['data']['status']);
    }

    /** @test */
    public function it_should_be_able_to_get_all_contacts()
    {
        $result = $this->base->getContacts(true);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('result', $result);
        $this->assertArrayHasKey('data', $result);

        $this->assertEquals('success', $result['result']);
    }

    /** @test */
    public function it_should_be_able_to_get_single_contact()
    {
        $result = $this->base->createContact($this->faker->firstName(), $this->faker->phoneNumber());

        $single = $this->base->getContact($result['data']['id']);

        $this->assertArrayHasKey('status', $single);
        $this->assertArrayHasKey('date', $single);
        $this->assertArrayHasKey('result', $single);
        $this->assertArrayHasKey('data', $single);

        $this->assertEquals('success', $single['result']);
    }

    /** @test */
    public function it_should_be_able_to_destroy_contact()
    {
        $contact = $this->base->createContact($this->faker->firstName(), $this->faker->phoneNumber());

        $result = $this->base->destroyContact($contact['data']['id']);

        $this->assertEquals(true, $result['status']);
        $this->assertEquals('success', $result['result']);
    }
}
