<?php

namespace BrownPaperTickets\APIv2;

class ManageCart extends BptAPI
{

    protected $requireCreditCard = false;
    protected $requireWillCallNames = false;

    /**
     * The first step of the manage cart API calls. Simply returns
     * a cart ID string.
     *
     * @return string|array The Cart ID or an Array containing
     * the error from the BPT API.
     */
    public function getCartID()
    {
        $apiOptions = array(
            'endpoint' => 'cart',
            'stage' => 1
        );

        $apiResults = $this->callAPI($apiOptions);

        $cartXML = $this->parseXML($apiResults);

        if (isset($cartXML['error'])) {
            return $cartXML;
        }

        return (string) $cartXML->cart_id;
    }


     /**
     * Add Prices to the cart.
     *
     * @param array $params See Below:
     *
     * cartID              string   The ID of the cart these prices will go
     *                              into.
     * prices              array    An multidimensional array with Price Info
     *                              PriceID => array(
     *                                  'shippingMethod' => 1,
     *                                  'quantity' => Integer
     *                              );
     * prices['PriceID'] =>
     * 'shippingMethod' => integer An integer representing the shipping method
     *                             1 - Physical Tickets
     *                             2 - Will Call
     *                             3 - Print at Home
     * ['quantity'] =>     integer The number of Tickets for this Price
     *
     * affiliateID    integer (optional) An affiliate ID.
     *
     * @return  array Returns either a success or error message array.
     */

    public function addPricesToCart($params)
    {

        $addPricesError = false;

        $apiOptions = array(
            'endpoint' => 'cart',
            'stage' => 2,
            'cart_id' => $params['cartID'],
        );

        if (isset($params['affiliateID'])) {
            $apiOptions['ref'] = $params['affiliateID'];
        }

        $addPrices = array(
            'result' => '',
            'cartID' => $params['cartID']
        );

        foreach ($params['prices'] as $priceID => $values) {

            if ($values['quantity'] === 0 || $values['quantity'] === '0') {
                continue;
            }

            $apiOptions['price_id'] = $priceID;
            $apiOptions['shipping'] = $values['shippingMethod'];
            $apiOptions['quantity'] = $values['quantity'];

            $apiResponse = $this->callAPI($apiOptions);

            $addPricesXML = $this->parseXML($apiResponse);

            $addSinglePrice = array(
                'result' => 'success',
                'priceID' => $priceID,
                'status' => 'Price has been added.'
            );

            if (isset($addPricesXML['error'])) {

                $addPricesError = true;
                $addSinglePrice['result'] = 'fail';
                $addSinglePrice['status'] = $addPricesXML['error'];
                $addPrices['pricesNotAdded'][] = $addSinglePrice;

            } else {

                $addPrices['result'] = 'success.';
                $addPrices['message'] = 'All Prices were added.';
                $addPrices['cartValue'] = (integer) $addPricesXML->val;
                $addPrices['pricesAdded'][] = $addSinglePrice;

            }

        }

        if ($addPricesError) {
            $addPrices['error'] = 'Error';
            $addPrices['result'] = 'Failed to add prices.';
            $addPrices['message'] = 'Some prices could not be added.';
        }

        if (!isset($addPrices['pricesAdded'])) {
            $addPrices['result'] = 'Failed to add prices.';
            $addPrices['message'] = 'No prices were sent with a quantity.';
        }

        return $addPrices;
    }

    public function removePricesFromCart($params) {
        $removePricesError = false;

        $apiOptions = array(
            'endpoint' => 'cart',
            'stage' => 2,
            'cart_id' => $params['cartID'],
        );

        $removePrices = array(
            'result' => '',
            'cartID' => $params['cartID']
        );

        foreach ($params['prices'] as $priceID => $values) {

            if ($values['quantity'] !== 0 || $values['quantity'] !== '0') {
                continue;
            }

            $apiOptions['price_id'] = $priceID;
            $apiOptions['quantity'] = 0;

            $apiResponse = $this->callAPI($apiOptions);

            $removePricesXML = $this->parseXML($apiResponse);

            $removeSinglePrice = array(
                'result' => 'success',
                'priceID' => $priceID,
                'status' => 'Price has been removed.'
            );

            if (isset($removePricesXML['error'])) {

                $removePricesError = true;

                $removeSinglePrice['result'] = 'fail';

                $removeSinglePrice['status'] = $removePricesXML['error'];

                unset($removeSinglePrice['message']);

                $removePrices['pricesNotRemoved'][] = $removeSinglePrice;

            } else {
                $removePrices['result'] = 'All prices sent have been removed.';
                $removePrices['cartValue'] = (integer) $removePricesXML->val;
                $removePrices['pricesRemoved'][] = $removeSinglePrice;
            }

        }

        if ($removePricesError) {
            $removePrices['error'] = 'Error';
            $removePrices['result'] = 'Failed to remove prices.';
            $removePrices['message'] = 'Some prices could not be removeed.';
        }

        if (!isset($removePrices['pricesRemoved'])) {
            $removePrices['result'] = 'Failed to remove prices.';
            $removePrices['message'] = 'No prices were sent with a quantity of 0.';
        }

        return $removePrices;
    }

    public function addShippingInfoToCart($params)
    {

        $apiOptions = array(
            'endpoint' => 'cart',
            'stage' => 3,
            'cart_id' => $params['cartID'],
            'fname' => $params['shippingFirstName'],
            'lname' => $params['shippingLastName'],
            'address' => $params['shippingAddress'],
            'city' => $params['shippingCity'],
            'state' => $params['shippingState'],
            'zip' => $params['shippingZip'],
            'country' => $params['shippingCountry'],
        );

        if ($this->requireWillCallNames === true
            && (!isset($params['willCallFirstName'])
            || !isset($params['willCallLastName']))
        ) {

            return array(
                'result' => 'error',
                'message' => 'Will Call names are required.'
            );
        }
        if (isset($params['willCallFirstName'])) {
            $apiOptions['attendee_firstname'] = $params['willCallFirstName'];
        }

        if (isset($params['willCallLastName'])) {
            $apiOptions['attendee_lastname'] = $params['willCallLastName'];
        }

        $apiResponse = $this->callAPI($apiOptions);

        $shippingInfoXML = $this->parseXML($apiResponse);

        if (isset($shippingInfoXML['error'])) {
            return $shippingInfoXML;
        }

        $shippingInfo = array(
            'result' => (string) 'success',
            'message' => (string) 'Shipping method has been added.',
            'cartID' => (string) $params['cartID']
        );

        return $shippingInfo;
    }

    public function addBillingInfoToCart($params)
    {
        $apiOptions = array(
            'endpoint' => 'cart',
            'stage' => 4,
            'cart_id' => $params['cartID'],
            'type' => $params['ccType'],
            'number' => $params['ccNumber'],
            'exp_month' => $params['ccExpMonth'],
            'exp_year' => $params['ccExpYear'],
            'cvv2' => $params['ccCvv2'],
            'billing_fname' => $params['billingFirstName'],
            'billing_lname' => $params['billingLastName'],
            'billing_address' => $params['billingAddress'],
            'billing_city' => $params['billingCity'],
            'billing_state' => $params['billingState'],
            'billing_zip' => $params['billingZip'],
            'billing_country' => $params['billingCountry'],
            'email' => $params['email'],
            'phone' => $params['phone']
        );

        $apiResponse = $this->callAPI($apiOptions);

        $billingInfoXML = $this->parseXML($apiResponse);

        if (isset($billingInfoXML['error'])) {
            return $billingInfoXML;
        }

        $billingInfo = array(
            'result' => (string) 'success',
            'message' => (string) 'Purchase complete.',
            'cartID' => (string) $billingInfoXML->cart_id
        );

        return $billingInfo;
    }
}
