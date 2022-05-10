# Symfony JSON Form
It generates a [JSON schema](http://json-schema.org/) representation, that serves as documentation and can be used to validate your data and, 
if you want, to generate forms using a generator. 

It can be used along with [liform-react](https://github.com/Limenius/liform-react) or [json-editor](https://github.com/jdorn/json-editor), or any other form generator based on json-schema.

## Installation

1. Download the Bundle
```bash
composer require octava/symfony-json-schema-form
```
2. Enable the Bundle

## Usage

Serializing a form into JSON Schema:
```php
$form = $this->createForm(CarType::class, $car, ['csrf_protection' => false]);
$schema = json_encode($this->get('sjsfom')->transform($form));
```
