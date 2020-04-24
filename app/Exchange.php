<?php declare(strict_types=1);

namespace App;
use App\ExchangeCache;
use App\Responses\CurrencyResponse;
use App\Responses\ErrorResponse;
use App\Responses\iResponse;


class Exchange
{
    private $currencyRepository;
    private $currencyRates;
    private $exchange_cache_expiry;
    public const ALLOWED_CURRENCIES = ["CAD", "JPY", "USD", "GBP", "EUR", "RUB", "HKD", "CHF"];

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->exchange_cache_expiry = (int) config('exchange.exchange_cache_expiry');
    }

    protected function isCurrencySupported($currency){
        return in_array($currency, self::ALLOWED_CURRENCIES);
    }

    protected function isValueNumeric($value){
        return is_numeric($value);
    }

    public function convert($value, string $fromCurrency, string $toCurrency):iResponse{
        // Check inputs
        if (! $this->isCurrencySupported($fromCurrency)){
            return new ErrorResponse(1, "currency code $fromCurrency not supported");
        }
        if (! $this->isCurrencySupported($toCurrency)){
            return new ErrorResponse(1, "currency code $toCurrency not supported");
        };
        if (! $this->isValueNumeric($value)){
            return new ErrorResponse(1, "value $value must be numeric");
        };
        if ($fromCurrency === $toCurrency){
            return new CurrencyResponse(0, (float) $value, 0);
        }

        $inverseCalculation = false;

        // Check cache for key pair, otherwise fetch from api
        if( $currencyPair = ExchangeCache::where('key', $fromCurrency."-".$toCurrency)->first()){
            $cache = 1;
        }
        elseif($currencyPair = ExchangeCache::where('key', $toCurrency."-".$fromCurrency)->first()){
            $cache = 1;
            $inverseCalculation = true;
        }
        else {
            try {
                $this->currencyRates = $this->currencyRepository->fetchCurrencyRates($fromCurrency)['rates'];
            } catch (\Exception $exception) {
                return new ErrorResponse(1, "Could not fetch currency rates, please try again later");
            }
            $currencyPair = new ExchangeCache();
            $currencyPair->key = $fromCurrency."-".$toCurrency;
            $currencyPair->from = $fromCurrency;
            $currencyPair->to = $toCurrency;
            $currencyPair->multiplier = $this->currencyRates[$toCurrency];
            $currencyPair->expiration = $this->exchange_cache_expiry;
            $currencyPair->save();
            $cache = 0;
        }

        // Calculate currency
        if($inverseCalculation){
            $convertedValue = $value/$currencyPair->multiplier;
        }
        else {
            $convertedValue = $value*$currencyPair->multiplier;
        }
        return new CurrencyResponse(0, $convertedValue, $cache);
    }
}
