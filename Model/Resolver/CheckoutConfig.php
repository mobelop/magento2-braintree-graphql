<?php
declare(strict_types=1);

namespace Mobelop\BraintreeGraphQl\Model\Resolver;

use Magento\Braintree\Model\Ui\ConfigProvider as BrainTreeConfigProvider;
use Magento\Braintree\Model\Ui\PayPal\ConfigProvider as BrainTreePaypalConfigProvider;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * CMS page field resolver, used for GraphQL request processing
 */
class CheckoutConfig implements ResolverInterface
{
    /**
     * @var BrainTreeConfigProvider
     */
    private $configProvider;
    private $paypalConfigProvider;

    /**
     * @param BrainTreeConfigProvider $configProvider
     * @param BrainTreePaypalConfigProvider $paypalConfigProvider
     */
    public function __construct(
        BrainTreeConfigProvider $configProvider,
        BrainTreePaypalConfigProvider $paypalConfigProvider
    )
    {
        $this->configProvider = $configProvider;
        $this->paypalConfigProvider = $paypalConfigProvider;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        $data = $this->getCheckoutConfig();

        return $data;
    }

    /**
     * @param int $pageId
     * @return array
     * @throws GraphQlNoSuchEntityException
     */
    private function getCheckoutConfig(): array
    {
        $data = "";
        $dataPaypal = "";
        try {
            $data = json_encode($this->configProvider->getConfig());
            $dataPaypal = json_encode($this->paypalConfigProvider->getConfig());
        } catch(\Exception $e) {
            $data = $e->getMessage();
        }
        
        return [
            "braintree" => $data,
            "braintree_paypal" => $dataPaypal,
        ];
    }
}
