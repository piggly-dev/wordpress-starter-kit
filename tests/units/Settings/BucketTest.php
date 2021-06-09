<?php
namespace Piggly\Tests\Wordpress\Settings;

use DateTime;
use PHPUnit\Framework\TestCase;
use Piggly\Dev\Wordpress\Settings\CustomBucket;
use Piggly\Wordpress\Settings\Bucket;
use RuntimeException;

/**
 * @coversDefaultClass \Piggly\Wordpress\Settings\Bucket
 */
class BucketTest extends TestCase
{
	/**
	 * Asset if bucket object has the expected
	 * added keys and values.
	 *
	 * @covers ::set
	 * @covers ::export
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canSet ()
	{
		$added = [
			'key_1' => 'value_1',
			'key_2' => 'value_2',
			'key_3' => 'value_3',
			'key_4' => 'value_4',
			'bucket_1' => ['key_b1' => 'value_b1'],
			'bucket_2' => ['key_b2' => 'value_b2']
		];

		$bucket = new Bucket();

		$bucket
			->set('key_1', 'value_1')
			->set('key_2', 'value_2')
			->set('key_3', 'value_3')
			->set('key_4', 'value_4')
			->set('bucket_1', ['key_b1' => 'value_b1'])
			->set('bucket_2', (new Bucket())->set('key_b2', 'value_b2'));

		$this->assertEquals($added, $bucket->export());
	}

	/**
	 * Asset if bucket object can get values.
	 *
	 * @covers ::get
	 * @covers ::set
	 * @covers ::export
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canGet ()
	{ $this->assertEquals('value', (new Bucket())->set('key', 'value')->get('key')); }

	/**
	 * Asset if bucket object can remove values.
	 *
	 * @covers ::remove
	 * @covers ::set
	 * @covers ::has
	 * @covers ::export
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canRemove ()
	{ $this->assertFalse((new Bucket())->set('key', 'value')->remove('key')->has('key')); }

	/**
	 * Asset if bucket object can import values.
	 *
	 * @covers ::import
	 * @covers ::export
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canImport ()
	{
		$added = [
			'key_1' => 'value_1',
			'key_2' => 'value_2',
			'key_3' => 'value_3',
			'key_4' => 'value_4',
			'bucket_1' => ['key_b1' => 'value_b1'],
			'bucket_2' => (new Bucket())->set('key_b2', 'value_b2')
		];

		$bucket = (new Bucket())->import($added);
		$added['bucket_2'] = ['key_b2' => 'value_b2'];

		$this->assertEquals($added, $bucket->export());
	}

	/**
	 * Asset if bucket object can import values.
	 *
	 * @covers ::import
	 * @covers ::export
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canImportOverwritting ()
	{
		$added = [
			'key_1' => 'value_1',
			'key_2' => 'value_2',
			'key_3' => 'value_3',
			'key_4' => 'value_4',
			'bucket_1' => ['key_b1' => 'value_b1'],
			'bucket_2' => (new Bucket())->set('key_b2', 'value_b2')
		];

		$bucket = (new Bucket())->import($added);
		$added['bucket_2'] = ['key_b2' => 'value_b2'];

		$bucket->import(['key_3' => 'value_5']);
		$added['key_3'] = 'value_5';

		$this->assertEquals($added, $bucket->export());
	}

	/**
	 * Asset if bucket object can import values.
	 *
	 * @covers ::import
	 * @covers ::export
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canImportNotOverwritting ()
	{
		$added = [
			'key_1' => 'value_1',
			'key_2' => 'value_2',
			'key_3' => 'value_3',
			'key_4' => 'value_4',
			'bucket_1' => ['key_b1' => 'value_b1'],
			'bucket_2' => (new Bucket())->set('key_b2', 'value_b2')
		];

		$bucket = (new Bucket())->import($added);
		$added['bucket_2'] = ['key_b2' => 'value_b2'];

		$bucket->import(['key_3' => 'value_5'], false);
		$this->assertEquals($added, $bucket->export());
	}

	/**
	 * Asset if bucket object can import custom values.
	 *
	 * @covers ::import
	 * @covers ::export
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canImportCustom ()
	{
		$added = [
			'key_1' => 'value_1',
			'key_2' => 'value_2',
			'key_3' => 'value_3',
			'key_4' => 'value_4',
			'bucket_1' => ['key_b1' => 'value_b1'],
			'bucket_2' => (new Bucket())->set('key_b2', 'value_b2'),
			'number' => 10,
			'created_at' => (new DateTime())->getTimestamp()
		];

		$bucket = (new CustomBucket())->import($added);
		$added['bucket_2'] = ['key_b2' => 'value_b2'];

		$this->assertEquals($added, $bucket->export());
	}

	/**
	 * Asset if bucket object can set custom values.
	 *
	 * @covers ::set
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canSetCustom ()
	{
		$this->expectException(RuntimeException::class);
		(new CustomBucket())->set('number', 'string');
	}

	/**
	 * Asset if bucket object can get custom values.
	 *
	 * @covers ::get
	 * @test Excepting positive assertion.
	 * @return void
	 */
	public function canGetCustom ()
	{ $this->assertEquals(0, (new CustomBucket())->get('number')); }
}