# Code Challenge

---

## Technical Notes

Following are the technical & architectural notes:

I have used some symfony component for routing and request handling.

The relevant code is in the following files or DIRs:

* Tariff Provider configuration
    * [config/tariffs.php](config/tariffs.php)
* Controllers
    * [app/Http/Controllers/Api/V1/CostCalculatorController.php](app/Http/Controllers/Api/V1/CostCalculatorController.php)
* Tariff Provider as dependency injection
  * [app/Providers/AppServiceProvider.php](app/Providers/AppServiceProvider.php)
* Services
    * [CostCalculator](app/Services/CostCalculator)
    * [TariffProviders](app/Services/TariffProviders)
* Tests Cases
    * [Unit](tests/Unit)
    * [tests/Feature/ApiEndpointTest.php](tests/Feature/ApiEndpointTest.php)
* **Bonus**:
  * GitHub-Workflow for testing has been added: [.github/workflows/laravel.yml](.github/workflows/laravel.yml)
  * Terraform AWS EC2 setup has been added: [terraform](terraform)

**Note:** For cost calculation service strategy pattern is used.
I hope most of the code is self-explanatory. 
There is still room for improvement and I have tried not to expand the task unnecessary.

### Setup & Execution

The docker configuration is based on Laravel Sail

```console
composer install && ./vendor/bin/sail up -d --build
```

The server will be accessible at http://localhost

## Routes:

* Get user consumption budget : `GET api/v1/calculate-consumption`
    * Query Params:
        * `consumption:[required, integer]`
        * `sort:[optional, string(in:asc,desc)]`
* Response:

```json
[
    {
        "tariff_name": "Product A",
        "annual_cost": 1050,
        "cost_breakdown": {
            "base": 60,
            "consumed": 990,
            "total": 1050
        }
    },
    {
        "tariff_name": "Product B",
        "annual_cost": 950,
        "cost_breakdown": {
            "base": 800,
            "consumed": 150,
            "total": 950
        }
    }
]
```

### Running Tests

```console
./vendor/bin/sail artisan test
```
