<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\EnterpriseServiceRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class EnterpriseServiceRequestTest extends TestCase {

    private EnterpriseServiceRequest $class;
    protected function setUp(): void {
        parent::setUp();
        $this->class = new EnterpriseServiceRequest();
    }

    public function testValidRequest() {
        $data = [
            'name' => 'Service Example',
            'image' => null,
            'type' => 'Enterprise',
            'postalCode' => '12345-678',
            'street' => 'Street Example',
            'number' => '123',
            'neighborhood' => 'Downtown',
            'city' => 'City Example',
            'state' => 'NY',
            'phone' => '123456789',
            'email' => 'test@exemple.com',
            'additional' => 'second floor',
            'website' => 'https://www.exemple.com',
        ];

        $validator = Validator::make($data, $this->class->rules());

        $this->assertTrue($validator->passes());
    }

    public function it_fails_validation_with_missing_required_fields() {
        $data = [
            // 'name' => 'Service Exemple',
            'image' => 'not-an-image',
            'type' => '',
            'postalCode' => '',
            'street' => '',
            'number' => '',
            'neighborhood' => '',
            'city' => '',
            'state' => '',
            'phone' => '',
            'email' => 'invalid-email',
            'website' => 'notaurl',
        ];

        $validator = Validator::make($data, $this->class->rules());

        $this->assertFalse($validator->passes());

        $errors = $validator->errors()->messages();

        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('image', $errors);
        $this->assertArrayHasKey('type', $errors);
        $this->assertArrayHasKey('postalCode', $errors);
        $this->assertArrayHasKey('street', $errors);
        $this->assertArrayHasKey('number', $errors);
        $this->assertArrayHasKey('neighborhood', $errors);
        $this->assertArrayHasKey('city', $errors);
        $this->assertArrayHasKey('state', $errors);
        $this->assertArrayHasKey('phone', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('website', $errors);
    }
}
