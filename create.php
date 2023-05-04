<?php

$var_value = $_GET['asin_code'];
$serviceName="ProductAdvertisigAPIn";
$region="eu-west-1";
$accessKey="amazonAccessKey";
$secretKey="amazonPrivateKey";
$partnerTag="amazonPartnerTag";

$payload="{"
    ." \"ItemIds\": ["
    ."  \"".$var_value."\""
    ." ],"
    ." \"Resources\": ["
    ."  \"BrowseNodeInfo.BrowseNodes\","
        ."  \"BrowseNodeInfo.BrowseNodes.Ancestor\","
        ."  \"BrowseNodeInfo.BrowseNodes.SalesRank\","
        ."  \"BrowseNodeInfo.WebsiteSalesRank\","
        ."  \"CustomerReviews.Count\","
        ."  \"CustomerReviews.StarRating\","
        ."  \"Images.Primary.Small\","
        ."  \"Images.Primary.Medium\","
        ."  \"Images.Primary.Large\","
        ."  \"Images.Variants.Small\","
        ."  \"Images.Variants.Medium\","
        ."  \"Images.Variants.Large\","
        ."  \"ItemInfo.ByLineInfo\","
        ."  \"ItemInfo.ContentInfo\","
        ."  \"ItemInfo.ContentRating\","
        ."  \"ItemInfo.Classifications\","
        ."  \"ItemInfo.ExternalIds\","
        ."  \"ItemInfo.Features\","
        ."  \"ItemInfo.ManufactureInfo\","
        ."  \"ItemInfo.ProductInfo\","
        ."  \"ItemInfo.TechnicalInfo\","
        ."  \"ItemInfo.Title\","
        ."  \"ItemInfo.TradeInInfo\","
        ."  \"Offers.Listings.Availability.MaxOrderQuantity\","
        ."  \"Offers.Listings.Availability.Message\","
        ."  \"Offers.Listings.Availability.MinOrderQuantity\","
        ."  \"Offers.Listings.Availability.Type\","
        ."  \"Offers.Listings.Condition\","
        ."  \"Offers.Listings.Condition.ConditionNote\","
        ."  \"Offers.Listings.Condition.SubCondition\","
        ."  \"Offers.Listings.DeliveryInfo.IsAmazonFulfilled\","
        ."  \"Offers.Listings.DeliveryInfo.IsFreeShippingEligible\","
        ."  \"Offers.Listings.DeliveryInfo.IsPrimeEligible\","
        ."  \"Offers.Listings.DeliveryInfo.ShippingCharges\","
        ."  \"Offers.Listings.IsBuyBoxWinner\","
        ."  \"Offers.Listings.LoyaltyPoints.Points\","
        ."  \"Offers.Listings.MerchantInfo\","
        ."  \"Offers.Listings.Price\","
        ."  \"Offers.Listings.ProgramEligibility.IsPrimeExclusive\","
        ."  \"Offers.Listings.ProgramEligibility.IsPrimePantry\","
        ."  \"Offers.Listings.Promotions\","
        ."  \"Offers.Listings.SavingBasis\","
        ."  \"Offers.Summaries.HighestPrice\","
        ."  \"Offers.Summaries.LowestPrice\","
        ."  \"Offers.Summaries.OfferCount\","
        ."  \"ParentASIN\","
        ."  \"RentalOffers.Listings.Availability.MaxOrderQuantity\","
        ."  \"RentalOffers.Listings.Availability.Message\","
        ."  \"RentalOffers.Listings.Availability.MinOrderQuantity\","
        ."  \"RentalOffers.Listings.Availability.Type\","
        ."  \"RentalOffers.Listings.BasePrice\","
        ."  \"RentalOffers.Listings.Condition\","
        ."  \"RentalOffers.Listings.Condition.ConditionNote\","
        ."  \"RentalOffers.Listings.Condition.SubCondition\","
        ."  \"RentalOffers.Listings.DeliveryInfo.IsAmazonFulfilled\","
        ."  \"RentalOffers.Listings.DeliveryInfo.IsFreeShippingEligible\","
        ."  \"RentalOffers.Listings.DeliveryInfo.IsPrimeEligible\","
        ."  \"RentalOffers.Listings.DeliveryInfo.ShippingCharges\","
        ."  \"RentalOffers.Listings.MerchantInfo\""
    ." ],"
    ." \"PartnerTag\": \"".$partnerTag."\","
    ." \"PartnerType\": \"Associates\","
    ." \"Marketplace\": \"www.amazon.in\""
    ."}";
$host="webservices.amazon.in";
$uriPath="/paapi5/getitems";
$awsv4 = new AwsV4 ($accessKey, $secretKey);
$awsv4->setRegionName($region);
$awsv4->setServiceName($serviceName);
$awsv4->setPath ($uriPath);
$awsv4->setPayload ($payload);
$awsv4->setRequestMethod ("POST");
$awsv4->addHeader ('content-encoding', 'amz-1.0');
$awsv4->addHeader ('content-type', 'application/json; charset=utf-8');
$awsv4->addHeader ('host', $host);
$awsv4->addHeader ('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.GetItems');
$headers = $awsv4->getHeaders ();
$headerString = "";
foreach ( $headers as $key => $value ) {
$headerString .= $key . ': ' . $value . "\r\n";
}
$params = array (
    'http' => array (
        'header' => $headerString,
        'method' => 'POST',
        'content' => $payload
    )
);
$stream = stream_context_create ( $params );

$fp = @fopen ( 'https://'.$host.$uriPath, 'rb', false, $stream );

if (! $fp) {
echo "";
}else{
$response = @stream_get_contents ( $fp );
if ($response === false) {
throw new Exception ( "Exception Occured" );
}
$itemResult = json_decode($response);
$price = str_replace(".", "", $itemResult->ItemsResult->Items[0]->Offers->Listings[0]->Price->Amount);
$i = $itemResult->ItemsResult->Items[0];

        $item = new STDclass;
        $item->detailedPageURL = $i->DetailPageURL;
        $item->title = $i->ItemInfo->Title->DisplayValue;
        $item->smallImage = $i->Images->Primary->Small->URL;
        $item->mediumImage = $i->Images->Primary->Medium->URL;
        $item->largeImage = $i->Images->Primary->Large->URL;
        $item->percentage = $i->Offers->Listings[0]->Price->Savings->Percentage;
        $item->price = $i->Offers->Listings[0]->Price->DisplayAmount;
        $item->Realprice = $i->Offers->Listings[0]->SavingBasis->Amount;
        $item->features = $i->ItemInfo->Features->DisplayValues;
        $item->ratings = $i->Offers->Listings[0]->MerchantInfo->FeedbackRating;
}


class AwsV4 {

private $accessKey = null;
private $secretKey = null;
private $path = null;
private $regionName = null;
private $serviceName = null;
private $httpMethodName = null;
private $queryParametes = array ();
private $awsHeaders = array ();
private $payload = "";

private $HMACAlgorithm = "AWS4-HMAC-SHA256";
private $aws4Request = "aws4_request";
private $strSignedHeader = null;
private $xAmzDate = null;
private $currentDate = null;

public function __construct($accessKey, $secretKey) {
    $this->accessKey = $accessKey;
    $this->secretKey = $secretKey;
    $this->xAmzDate = $this->getTimeStamp ();
    $this->currentDate = $this->getDate ();
}

function setPath($path) {
    $this->path = $path;
}

function setServiceName($serviceName) {
    $this->serviceName = $serviceName;
}

function setRegionName($regionName) {
    $this->regionName = $regionName;
}

function setPayload($payload) {
    $this->payload = $payload;
}

function setRequestMethod($method) {
    $this->httpMethodName = $method;
}

function addHeader($headerName, $headerValue) {
    $this->awsHeaders [$headerName] = $headerValue;
}

private function prepareCanonicalRequest() {
    $canonicalURL = "";
    $canonicalURL .= $this->httpMethodName . "\n";
    $canonicalURL .= $this->path . "\n" . "\n";
    $signedHeaders = '';
    foreach ( $this->awsHeaders as $key => $value ) {
        $signedHeaders .= $key . ";";
        $canonicalURL .= $key . ":" . $value . "\n";
    }
    $canonicalURL .= "\n";
    $this->strSignedHeader = substr ( $signedHeaders, 0, - 1 );
    $canonicalURL .= $this->strSignedHeader . "\n";
    $canonicalURL .= $this->generateHex ( $this->payload );
    return $canonicalURL;
}

private function prepareStringToSign($canonicalURL) {
    $stringToSign = '';
    $stringToSign .= $this->HMACAlgorithm . "\n";
    $stringToSign .= $this->xAmzDate . "\n";
    $stringToSign .= $this->currentDate . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . "\n";
    $stringToSign .= $this->generateHex ( $canonicalURL );
    return $stringToSign;
}

private function calculateSignature($stringToSign) {
    $signatureKey = $this->getSignatureKey ( $this->secretKey, $this->currentDate, $this->regionName, $this->serviceName );
    $signature = hash_hmac ( "sha256", $stringToSign, $signatureKey, true );
    $strHexSignature = strtolower ( bin2hex ( $signature ) );
    return $strHexSignature;
}

public function getHeaders() {
    $this->awsHeaders ['x-amz-date'] = $this->xAmzDate;
    ksort ( $this->awsHeaders );

    // Step 1: CREATE A CANONICAL REQUEST
    $canonicalURL = $this->prepareCanonicalRequest ();

    // Step 2: CREATE THE STRING TO SIGN
    $stringToSign = $this->prepareStringToSign ( $canonicalURL );

    // Step 3: CALCULATE THE SIGNATURE
    $signature = $this->calculateSignature ( $stringToSign );

    // Step 4: CALCULATE AUTHORIZATION HEADER
    if ($signature) {
        $this->awsHeaders ['Authorization'] = $this->buildAuthorizationString ( $signature );
        return $this->awsHeaders;
    }
}

private function buildAuthorizationString($strSignature) {
    return $this->HMACAlgorithm . " " . "Credential=" . $this->accessKey . "/" . $this->getDate () . "/" . $this->regionName . "/" . $this->serviceName . "/" . $this->aws4Request . "," . "SignedHeaders=" . $this->strSignedHeader . "," . "Signature=" . $strSignature;
}

private function generateHex($data) {
    return strtolower ( bin2hex ( hash ( "sha256", $data, true ) ) );
}

private function getSignatureKey($key, $date, $regionName, $serviceName) {
    $kSecret = "AWS4" . $key;
    $kDate = hash_hmac ( "sha256", $date, $kSecret, true );
    $kRegion = hash_hmac ( "sha256", $regionName, $kDate, true );
    $kService = hash_hmac ( "sha256", $serviceName, $kRegion, true );
    $kSigning = hash_hmac ( "sha256", $this->aws4Request, $kService, true );

    return $kSigning;
}

private function getTimeStamp() {
    return gmdate ( "Ymd\THis\Z" );
}

private function getDate() {
    return gmdate ( "Ymd" );
}
}
?>

<input type="text" autofocus value="<?php echo $item->title;?>" placeholder="Name">
<input type="text"  value="<?php echo $price;?>" name="price" placeholder="Price">
<input type="text" autofocus value="<?php echo $item->Realprice;?>">
<input type="number" value="<?php echo $item->percentage;?>" placeholder="Offer Discount">
<input type="text" autofocus value="<?php echo $item->ratings?>">
<input type="text" autofocus value="<?php echo $item->mediumImage;?>" placeholder="image src">
<input type="text" autofocus value="<?php echo $item->detailedPageURL;?>" placeholder="Product Url">

<textarea rows="3">
  <?php foreach ($item->features as $key => $value) :?>
    <?php echo $value."<br>";?>
  <?php endforeach;?>
 </textarea>



