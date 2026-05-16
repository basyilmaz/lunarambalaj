<?php

namespace Tests\Unit;

use App\Support\EnhancedConversionData;
use PHPUnit\Framework\TestCase;

class EnhancedConversionDataTest extends TestCase
{
    public function test_email_is_lowercased_and_trimmed(): void
    {
        $this->assertSame('foo@bar.com', EnhancedConversionData::normalizeEmail('  FOO@Bar.COM '));
    }

    public function test_email_null_and_empty_return_null(): void
    {
        $this->assertNull(EnhancedConversionData::normalizeEmail(null));
        $this->assertNull(EnhancedConversionData::normalizeEmail('   '));
    }

    public function test_phone_with_leading_zero_gets_country_code(): void
    {
        $this->assertSame('+905551234567', EnhancedConversionData::normalizePhone('0555 123 45 67'));
    }

    public function test_phone_with_plus_is_preserved(): void
    {
        $this->assertSame('+15551234567', EnhancedConversionData::normalizePhone('+1 (555) 123-4567'));
    }

    public function test_phone_with_double_zero_prefix(): void
    {
        $this->assertSame('+15551234567', EnhancedConversionData::normalizePhone('001 555 123 4567'));
    }

    public function test_ten_digit_input_assumes_default_country(): void
    {
        $this->assertSame('+905551234567', EnhancedConversionData::normalizePhone('5551234567'));
    }
}
