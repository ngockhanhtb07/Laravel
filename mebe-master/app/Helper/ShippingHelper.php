<?php

namespace App\Helper;

use App\Http\Service\GHN\GHNServiceAPIClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ShippingHelper {

    const SHIPPING_NOTE_CODE_CHO_THU = 'CHOTHUHANG';
    const SHIPPING_NOTE_CODE_CHO_XEM = 'CHOXEMHANGKHONGTHU';
    const SHIPPING_NOTE_CODE_KHONG_CHO_XEM = 'KHONGCHOXEMHANG';

    protected $_serviceAPIClient;

    public function __construct(GHNServiceAPIClient $serviceAPIClient)
    {
        $this->_serviceAPIClient = $serviceAPIClient;
    }

    public function getDistrictIDByName($districtName, $provinceName = null)
    {
        $environment = App::environment();
        if ($environment == 'local' || $environment == 'staging') {
            // testing data
            $districtName = 'huyện châu thành';
            $provinceName = 'Kiên Giang';
        }

        $result = $this->_serviceAPIClient->getDistricts();
        if ($result->code) {
            $districts = $result->data;
            if (sizeof($districts) > 0) {
                foreach ($result->data as $district) {
                    if ($provinceName) {
                        $condition = strtolower($district->DistrictName) == strtolower($districtName);
                    } else {
                        $condition = strtolower($district->DistrictName) == strtolower($districtName) &&
                            strtolower($district->ProvinceName) == strtolower($provinceName);
                    }
                    if ($condition) {
                        return $district->DistrictID;
                    }
                }
            }
        }
        return false;
    }

    public function formatShippingData($districtFrom, $totalWeight, $districtTo){
        return [
            'Weight' => $totalWeight,
            'FromDistrictID' => $districtFrom,
            'ToDistrictID' => $districtTo,
            'ServiceID' => 53324
        ];
    }

    public function formatShipmentData($order) {
        $orderHelper = new OrderHelper();
        $districtFromId = $this->getDistrictIDByName($order->shop->district);
        $districtToId = $this->getDistrictIDByName($order->shippingAddress->district);
        $parameter =  [
            'FromDistrictID' => $districtFromId,
            'ToDistrictID' => $districtToId,
            'ClientContactName' => $order->shop->shop_name,
            'ClientContactPhone' => $order->shop->user->phone,
            'ClientAddress' => $order->shop->address,
            'CustomerName' => $order->customer->last_name,
            'CustomerPhone' => $order->customer->user->phone,
            'ShippingAddress' => $order->shippingAddress->street,
            'NoteCode' => self::SHIPPING_NOTE_CODE_CHO_XEM,
            'ServiceID' => 53324,
            'Weight' => $orderHelper->totalWeightShop($order->orderItems),
            'Length' => 20,
            'Width' => 20,
            'Height' => 20,
            'ReturnContactName ' => $order->shop->shop_name,
            'ReturnContactPhone ' => $order->shop->user->phone,
            'ReturnAddress ' => $order->shop->address,
            'ReturnDistrictCode' => "$districtToId",
            'ReturnDistrictID' => $districtToId,
            'ExternalReturnCode' => "",
            'AffiliateID' => 252905,
        ];
        return $parameter;
    }
}