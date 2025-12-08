<?php

declare(strict_types=1);

namespace Maatify\RateLimiter\Tests\Resolver;

use Maatify\RateLimiter\Drivers\RedisRateLimiter;
use Maatify\RateLimiter\Drivers\MongoRateLimiter;
use Maatify\RateLimiter\Drivers\MySQLRateLimiter;
use Maatify\RateLimiter\Resolver\RateLimiterResolver;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use ReflectionClass;

final class RateLimiterResolverTest extends TestCase
{
    public function testResolveRedis(): void
    {
        $resolver = new RateLimiterResolver([
            'driver' => 'redis',
            'redis_host' => '127.0.0.1',
        ]);
        $this->assertInstanceOf(RedisRateLimiter::class, $resolver->resolve());
    }

    public function testResolveMongo(): void
    {
        $resolver = new RateLimiterResolver([
            'driver' => 'mongo',
            'mongo_uri' => 'mongodb://127.0.0.1:27017',
        ]);
        $this->assertInstanceOf(MongoRateLimiter::class, $resolver->resolve());
    }

    public function testResolveMySQL(): void
    {
        $resolver = new RateLimiterResolver([
            'driver' => 'mysql',
            'mysql_dsn' => 'sqlite::memory:',
        ]);

        // This will try to create MySQLRateLimiter with a PDO instance.
        // Since we use sqlite::memory:, it might succeed in creating PDO,
        // and thus MySQLRateLimiter.
        // But MySQLRateLimiter might expect MySQL specific things?
        // No, it just takes PDO.

        $this->assertInstanceOf(MySQLRateLimiter::class, $resolver->resolve());
    }

    public function testResolveMySQLConnectionFail(): void
    {
        $resolver = new RateLimiterResolver([
            'driver' => 'mysql',
            'mysql_dsn' => 'mysql:host=invalid_host;dbname=test',
            'mysql_user' => 'user',
            'mysql_pass' => 'pass',
        ]);

        $this->expectException(\PDOException::class);
        $resolver->resolve();
    }

    public function testResolveThrowsExceptionForUnknownDriver(): void
    {
        $resolver = new RateLimiterResolver(['driver' => 'unknown']);
        $this->expectException(InvalidArgumentException::class);
        $resolver->resolve();
    }

    public function testGetStringConfig(): void
    {
        // We can test private methods via reflection if we want to be thorough
        // or just rely on resolving passing correctly.
        $resolver = new RateLimiterResolver(['driver' => 'redis']);
        $this->assertInstanceOf(RedisRateLimiter::class, $resolver->resolve());
    }

    public function testGetIntConfigHandlesNumericStrings(): void
    {
        // Testing private getIntConfig via Reflection or implicit via Redis port logic
        // But since we can't easily check port on a Redis object, let's use Reflection
        $resolver = new RateLimiterResolver(['port' => '6379']);
        $reflection = new ReflectionClass($resolver);
        $method = $reflection->getMethod('getIntConfig');
        $method->setAccessible(true);

        $this->assertSame(6379, $method->invoke($resolver, 'port', 1111));
    }

    public function testGetIntConfigHandlesIntegers(): void
    {
        $resolver = new RateLimiterResolver(['port' => 6379]);
        $reflection = new ReflectionClass($resolver);
        $method = $reflection->getMethod('getIntConfig');
        $method->setAccessible(true);

        $this->assertSame(6379, $method->invoke($resolver, 'port', 1111));
    }

    public function testGetIntConfigReturnsDefaultWhenMissing(): void
    {
        $resolver = new RateLimiterResolver([]);
        $reflection = new ReflectionClass($resolver);
        $method = $reflection->getMethod('getIntConfig');
        $method->setAccessible(true);

        $this->assertSame(1111, $method->invoke($resolver, 'port', 1111));
    }

    public function testGetStringConfigHandlesStrings(): void
    {
        $resolver = new RateLimiterResolver(['host' => 'localhost']);
        $reflection = new ReflectionClass($resolver);
        $method = $reflection->getMethod('getStringConfig');
        $method->setAccessible(true);

        $this->assertSame('localhost', $method->invoke($resolver, 'host', 'default'));
    }

    public function testGetStringConfigReturnsDefaultWhenMissingOrNonString(): void
    {
        $resolver = new RateLimiterResolver(['host' => 123]);
        $reflection = new ReflectionClass($resolver);
        $method = $reflection->getMethod('getStringConfig');
        $method->setAccessible(true);

        $this->assertSame('default', $method->invoke($resolver, 'host', 'default'));
    }
}
