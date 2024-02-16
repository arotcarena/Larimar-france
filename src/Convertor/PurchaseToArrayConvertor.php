<?php
namespace App\Convertor;

use App\Convertor\AddressToArrayConvertor;
use App\Entity\Purchase;
use App\Convertor\ConvertorTrait;
use App\Entity\PurchaseLine;
use App\Twig\Runtime\PriceFormaterExtensionRuntime;

class PurchaseToArrayConvertor
{
    use ConvertorTrait;
    
    public function __construct(
        private AddressToArrayConvertor $addressConvertor,
        private PriceFormaterExtensionRuntime $priceFormater
    )
    {

    }

    private string $mode;

    /**
     * @param Purchase|Purchase[] $data
     * @param string $mode 
     * @return array
     */
    public function convert($data, $mode = 'full'): array
    {
        $this->mode = $mode;
        return $this->convertOneOrMore($data);
    }
    
    public function convertOne(Purchase $purchase): array 
    {
        if($this->mode !== 'full')
        {
            return [
                'id' => $purchase->getId(),
                'ref' => $purchase->getRef(),
                'status' => $purchase->getStatusLabel(),
                'total' => $purchase->getTotalPrice() + $purchase->getShippingInfo()['price'],
                'createdAt' => $purchase->getCreatedAt()->format('d/m/Y H:i')
            ];
        }
        return [
            'id' => $purchase->getId(),
            'ref' => $purchase->getRef(),
            'status' => $purchase->getStatusLabel(),
            'totalPrice' => $purchase->getTotalPrice(),
            'createdAt' => $purchase->getCreatedAt()->format('d/m/Y H:i'),
            'tracking' => $purchase->getTracking(),
            'deliveryDetail' => $this->addressConvertor->convert(
                $purchase->getDeliveryDetail()
            ),
            'invoiceDetail' => $this->addressConvertor->convert(
                $purchase->getInvoiceDetail()
            ),
            'purchaseLines' => $this->convertPurchaseLines(
                $purchase->getPurchaseLines()
            ),
            'shippingInfo' => $purchase->getShippingInfo()
        ];
    }

    /**
     * Undocumented function
     *
     * @param PurchaseLine[] $purchaseLines
     * @return void
     */
    private function convertPurchaseLines($purchaseLines): array 
    {
        $purchaseLinesArray = [];
        foreach($purchaseLines as $purchaseLine)
        {
            $purchaseLinesArray[] = $this->convertPurchaseLine($purchaseLine);
        }
        return $purchaseLinesArray;
    }

    private function convertPurchaseLine(PurchaseLine $purchaseLine): array 
    {
        return [
            'id' => $purchaseLine->getId(),
            'product' => $purchaseLine->getProduct(),
            'quantity' => $purchaseLine->getQuantity(),
            'totalPrice' => $purchaseLine->getTotalPrice()
        ];
    }
}
