<?php

namespace Tests\Feature\Helpers;

use App\Models\Helpers\Sanitizer;
use Tests\TestCase;

class SanitizerTest extends TestCase {

    private Sanitizer $class;

    protected function setUp(): void {
        parent::setUp();
        $this->class = new Sanitizer();
    }

    public function testSanitizeNumber() {
        $numbers = $this->makeStringNumbersToTest();

        foreach ($numbers as $number => $expected) {
            $sanitized = $this->class->sanitizeNumber($number);

            $this->assertEquals($expected, $sanitized, "Testing Equals Sanitized Numbers {$sanitized} : {$expected}");
        }
    }

    private function makeStringNumbersToTest() : array {
        // Number => Expected
        return [
            '' => '',
            '12530-000' => '12530000',
            '123.123.123-00' => '12312312300',
            '71.785.418/0001-08' => '71785418000108',
            '53A!-:*@' => '53'
        ];
    }

    public function testSanitizeName() {
        $names = $this->makeNamesToTest();

        foreach ($names as $name => $expected) {
            $sanitized = $this->class->sanitizeName($name);

            $this->assertEquals($expected, $sanitized, "Testing Equals Sanitized names {$sanitized} : {$expected}");
        }
    }

    private function makeNamesToTest(): array {
        // Number => Expected
        return [
            ' person name   ' => 'Person Name',
        ];
    }
}
